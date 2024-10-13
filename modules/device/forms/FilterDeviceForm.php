<?php

declare(strict_types=1);

namespace app\modules\device\forms;

use DateTime;
use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 *
 * @property-read array $urlRedirect
 */
class FilterDeviceForm extends Model
{
    /**
     * @var string|null
     */
    public ?string $date = null;

    /**
     * @var string|null
     */
    public ?string $datePeriod = null;
    /**
     * @var string|null
     */
    public ?string $dateNext = null;

    /**
     * @var string|null
     */
    public ?string $datePeriodNext = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['date', 'datePeriod', 'dateNext', 'datePeriodNext'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'date' => 'Поверить до',
            'datePeriod' => 'Период следующей поверки',
            'dateNext' => 'Поверить до',
            'datePeriodNext' => 'Период следующей поверки',
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getUrlRedirect(): array
    {
        $url = ArrayHelper::merge(['index'], Yii::$app->request->getQueryParams());
        if (!empty($this->date)) {
            $url = ArrayHelper::merge($url, ['DeviceSearch[dateVerification]' => $this->date]);
        }
        if (!empty($this->dateNext)) {
            $url = ArrayHelper::merge($url, ['DeviceSearch[dateVerificationNext]' => $this->dateNext]);
        }
        $periodDate = $this->getPeriodDate('datePeriod');
        if (!empty($periodDate)) {
            $url = ArrayHelper::merge(
                $url,
                ['DeviceSearch[dateVerificationStart]' => ArrayHelper::getValue($periodDate,'start')]
            );
            $url = ArrayHelper::merge(
                $url,
                ['DeviceSearch[dateVerificationEnd]' => ArrayHelper::getValue($periodDate,'end')]
            );
        }
        $periodDateNext = $this->getPeriodDate('datePeriodNext');
        if (!empty($periodDateNext)) {
            $url = ArrayHelper::merge(
                $url,
                ['DeviceSearch[dateVerificationStartNext]' => ArrayHelper::getValue($periodDateNext,'start')]
            );
            $url = ArrayHelper::merge(
                $url,
                ['DeviceSearch[dateVerificationEndNext]' => ArrayHelper::getValue($periodDateNext,'end')]
            );
        }

        return $url;
    }

    /**
     * @throws Exception
     */
    protected function getPeriodDate(string $attribute): array
    {
        $periodData = [];
        $valueData = $this->{$attribute};

        if (!empty($valueData)) {
            $datePeriod = explode(' - ', $valueData);
            if (!empty($datePeriod[0])) {
                $dateTime = new DateTime($datePeriod[0]);
                $periodData['start'] = $dateTime->format('Y-m-d');
            }
            if (!empty($datePeriod[0])) {
                $dateTime = new DateTime($datePeriod[1]);
                $periodData['end'] = $dateTime->format('Y-m-d');
            }
        }

        return $periodData;
    }
}
