<?php

declare(strict_types=1);

namespace kilyanov\repository\widgets;

use kilyanov\dropzone\DropzoneWidget;
use kilyanov\repository\models\Repository;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 *
 * @property-read array $items
 */
class DropzoneInputWidget extends InputWidget
{
    /**
     * @var array
     */
    public array $dropzoneOptions = [];

    /**
     * @var array
     */
    public array $clientOptions = [];

    /**
     * @var array
     */
    public array $clientEvents = [];

    /**
     * @var bool
     */
    public bool $autoDiscover = false;

    /**
     * @var Repository[]
     */
    protected array $repositories = [];

    /**
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        $this->options = array_merge($this->options, [
            'multiple' => true,
            'class' => 'hidden',
        ]);
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function run(): string
    {
        $html = [];

        if ($this->hasModel()) {
            $html[] = Html::activeDropDownList($this->model, $this->attribute, $this->getItems(), $this->options);
        } else {
            $html[] = Html::dropDownList($this->name, $this->value, $this->getItems(), $this->options);
        }

        $this->registerClientOptions();
        $this->registerClientEvents();
        $this->registerExistingFile();

        $html[] = DropzoneWidget::widget([
            'options' => $this->dropzoneOptions,
            'clientOptions' => $this->clientOptions,
            'clientEvents' => $this->clientEvents,
            'autoDiscover' => $this->autoDiscover,
        ]);

        $html[] = $this->renderPreviewsContainer();

        return implode(PHP_EOL, $html);
    }

    /**
     * @return array
     */
    protected function getItems(): array
    {
        return ArrayHelper::map($this->getRepositories(), 'id', 'src');
    }

    /**
     * @return Repository[]
     */
    protected function getRepositories(): array
    {
        if (empty($this->repositories)) {
            $this->repositories = Repository::find()->where([
                Repository::tableName() . '.[[id]]' => $this->model->{$this->attribute},
            ])->all();
        }

        return $this->repositories;
    }

    /**
     * @return void
     */
    protected function registerClientOptions(): void
    {
        $this->clientOptions = array_merge([
            'dictDefaultMessage' => 'Перетащите файлы сюда или нажмите, чтобы загрузить.',
            'dictCancelUploadConfirmation' => 'Вы уверены, что хотите отменить загрузку этого элемента?',
            'dictRemoveFileConfirmation' => 'Вы уверены, что хотите удалить этот элемент?',
            'thumbnailWidth' => 100,
            'thumbnailMethod' => 'contain',
            'previewTemplate' => $this->renderPreviewTemplate(),
            'previewsContainer' => '#' . $this->getId() . '-previews-container',
        ], $this->clientOptions);
    }

    /**
     * @return string
     */
    protected function renderPreviewTemplate(): string
    {
        return $this->render('previewTemplate.php');
    }

    /**
     * @return void
     */
    protected function registerClientEvents(): void
    {
        $this->clientEvents = array_merge([
            'success' => new JsExpression('function(file, data) { file.repository = data.repository; jQuery("#' . $this->options['id'] . '").append(jQuery("<option>", { value: file.repository.id, text: file.repository.src, selected: true })).trigger("change"); }'),
            'removedfile' => new JsExpression('function(file) { if (file.repository) { jQuery("#' . $this->options['id'] . ' option[value=" + file.repository.id + "]").prop("selected", false).trigger("change"); } }'),
        ], $this->clientEvents);

        if (isset($this->clientOptions['maxFiles'])) {
            $this->clientEvents = array_merge([
                'maxfilesexceeded' => new JsExpression('function(file) { this.removeFile(file); }'),
            ], $this->clientEvents);
        }
    }

    /**
     * @return void
     */
    protected function registerExistingFile(): void
    {
        $repositories = $this->getRepositories();

        $js = [];

        foreach ($repositories as $repository) {
            $mime = $repository->meta['mime'] ?? null;
            $size = $repository->meta['size'] ?? null;

            $filename = 'repository_' . $repository->id;
            $mockup = [
                'name' => $repository->title,
                'size' => $size,
                'accepted' => true,
                'status' => 'success',
                'repository' => [
                    'id' => $repository->id,
                    'src' => $repository->src,
                ],
            ];

            $js[] = new JsExpression('let ' . $filename . ' = ' . Json::encode($mockup) . ';');

            if ($mime && $this->hasImage($mime)) {
                $url = Url::to(DIRECTORY_SEPARATOR . Yii::getAlias('@storage') . DIRECTORY_SEPARATOR . $repository->src);

                $js[] = new JsExpression('this.displayExistingFile(' . $filename . ', "' . $url . '", null, "anonymous");');
            } else {
                $js[] = new JsExpression('this.emit("addedfile", ' . $filename . ');');
                $js[] = new JsExpression('this.emit("complete", ' . $filename . ');');
            }

            $js[] = new JsExpression('this.files.push(' . $filename . ');');
        }

        $this->clientOptions = array_merge([
            'init' => new JsExpression('function() { ' . implode(PHP_EOL, $js) . ' }'),
        ], $this->clientOptions);
    }

    /**
     * @param string $mime
     *
     * @return bool
     */
    protected function hasImage(string $mime): bool
    {
        return (bool)strstr($mime, 'image/');
    }

    /**
     * @return string
     */
    protected function renderPreviewsContainer(): string
    {
        return Html::tag('div', '', [
            'id' => $this->getId() . '-previews-container',
            'class' => 'row',
        ]);
    }
}
