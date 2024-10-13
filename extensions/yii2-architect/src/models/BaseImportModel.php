<?php

declare(strict_types=1);

namespace kilyanov\architect\models;

use Exception;
use kilyanov\architect\interfaces\ImportInterface;
use kilyanov\behaviors\ActiveRecord;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use UUID\UUID;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 *
 * @property-read array $importMap
 * @property-read string $importFileMap
 */
class BaseImportModel extends Model implements ImportInterface
{
    /**
     * @var array
     */
    public array $cfgParams = [];

    /**
     * @var array
     */
    public array $fileMap = [];

    /**
     * @var string|null
     */
    public ?string $importModel = null;

    /**
     * @var UploadedFile|null
     */
    public $file;

    /**
     * @var int
     */
    private int $rowAll = 0;

    /**
     * @var int
     */
    private int $rowExist = 0;

    /**
     * @var int
     */
    private int $rowSuccess = 0;

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['file',], 'required'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => self::FILE_EXTENSIONS],
            [['cfgParams',], 'safe'],
        ];
    }

    /**
     * @return bool
     */
    public function beforeValidate(): bool
    {
        $this->file = UploadedFile::getInstance($this, 'file');

        return parent::beforeValidate();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'file' => 'Файл импорта',
        ];
    }

    /**
     * @return bool
     */
    public function upload(): bool
    {
        if ($this->validate()) {
            $this->file->name = UUID::uuid7() . '.' . $this->file->extension;
            $this->file->saveAs(
                Yii::getAlias(ImportInterface::DIR_IMPORT_FILE) .
                DIRECTORY_SEPARATOR . $this->file->baseName . '.' . $this->file->extension
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function readFileImport(): array
    {
        $filename = Yii::getAlias(ImportInterface::DIR_IMPORT_FILE) . DIRECTORY_SEPARATOR . $this->file->name;
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $reader = null;
        switch ($ext) {
            case 'xls':
                $reader = new Xls();
                break;
            case 'xlsx':
                $reader = new Xlsx();
                break;
        }
        if ($reader === null) {
            throw new NotFoundHttpException('Не определён класс для чтения файла, проверьте расширение загружаемого файла.');
        }
        $spreadsheet = $reader->load($filename);
        unlink($filename);
        return $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    }

    /**
     * @return bool
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function importData(): bool
    {
        $this->upload();
        $models = $this->readFileImport();
        return $this->saveData($models);
    }

    /**
     * @param array $models
     * @return bool
     * @throws Exception
     */
    protected function saveData(array $models = []): bool
    {
        if (empty($models)) {
            $this->addError('file', 'Данные для импорта не найдены.');
            return false;
        } else {
            $setting = $this->getImportMap();
            $existMethod = ArrayHelper::getValue($setting, ImportInterface::TYPE_EXIST);
            unset($setting[ImportInterface::TYPE_EXIST]);

            $beforeImportMethod = ArrayHelper::getValue($setting, ImportInterface::TYPE_BEFORE_IMPORT);
            unset($setting[ImportInterface::TYPE_BEFORE_IMPORT]);

            $transaction = Yii::$app->db->beginTransaction();
            $this->rowAll = count($models);

            if ($beforeImportMethod !== null) {
                call_user_func($beforeImportMethod, $this->cfgParams);
            }
            foreach ($models as $model) {
                $addData = [];
                foreach ($model as $key => $value) {
                    if (array_key_exists($key, $setting)) {
                        $addData = ArrayHelper::merge($addData, $this->getDataValue($setting, $model, $key));
                    }
                }
                if (!empty($this->cfgParams)) {
                    $addData = ArrayHelper::merge($addData, $this->cfgParams);
                }
                if ($existMethod !== null) {
                    if (call_user_func($existMethod, $addData) === null) {
                        if (!$this->saveObject($addData)) {
                            $transaction->rollBack();
                            return false;
                        }
                    } else {
                        $this->rowExist++;
                    }
                } else {
                    if (!$this->saveObject($addData)) {
                        $transaction->rollBack();
                        return false;
                    }
                }
            }
            $transaction->commit();
            return true;
        }
    }

    /**
     * @param $addData
     * @return bool
     * @throws Exception
     */
    protected function saveObject($addData): bool
    {
        /** @var ActiveRecord $object */
        $object = new $this->importModel($addData);
        if ($object->canGetProperty('sort')) {
            $sort = $this->importModel::find()->where($this->cfgParams)->count();
            $sort++;
            $object->setAttribute('sort', $sort);
        }
        if (!$object->save()) {
            $this->addError('file', implode(', ', $object->getErrorSummary(true)));
            return false;
        } else {
            $this->rowSuccess++;
            return true;
        }
    }

    /**
     * @return bool
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function save(): bool
    {
        return $this->importData();
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function getImportModel(): string
    {
        if ($this->importModel === null) {
            throw new NotFoundHttpException('Модель не определена.');
        }
    }

    /**
     * @return array
     */
    public function getImportMap(): array
    {
        return $this->fileMap;
    }

    /**
     * @param array $data
     * @param array $row
     * @param string $columnAddress
     * @return array
     */
    public function getDataValue(array $data, array $row, string $columnAddress): array
    {
        switch ($data[$columnAddress]['type']) {
            case ImportInterface::TYPE_CLOSURE:
                if (empty($row[$columnAddress])) {
                    return [$data[$columnAddress]['attribute'] => null];
                } else {
                    return [$data[$columnAddress]['attribute'] => call_user_func($data[$columnAddress] ['value'], $row)];
                }
            case ImportInterface::TYPE_INTEGER:
                return empty($row[$columnAddress]) ? [$data[$columnAddress]['attribute'] => null] :
                    [$data[$columnAddress]['attribute'] => (int)$row[$columnAddress]];
            case ImportInterface::TYPE_STRING:
                return empty($row[$columnAddress]) ?
                    [$data[$columnAddress]['attribute'] => null] :
                    [$data[$columnAddress]['attribute'] => trim((string)$row[$columnAddress])];
            case ImportInterface::TYPE_FLOAT:
                return empty($row[$columnAddress]) ?
                    [$data[$columnAddress]['attribute'] => null] :
                    [$data[$columnAddress]['attribute'] => (float)$row[$columnAddress]];
        }
    }

    /**
     * @return int
     */
    public function getRowAll(): int
    {
        return $this->rowAll;
    }

    /**
     * @return int
     */
    public function getRowSuccess(): int
    {
        return $this->rowSuccess;
    }

    public function getRowExist(): int
    {
        return $this->rowExist;
    }
}
