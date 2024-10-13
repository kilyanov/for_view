<?php

declare(strict_types=1);

namespace app\modules\device\widgets;

use app\modules\device\models\Device;
use app\modules\device\models\DeviceGroup;
use Exception;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class FilterWidget extends Widget
{
    /**
     * @var string
     */
    public string $template = 'device-filter';

    public array $limits = [20 => 20, 50 => 50, 100 => 100, 200 => 200, 500 => 500, 1000 => 1000];

    public array $deviceGroupList = [];

    /**
     * @return string
     * @throws Exception
     */
    public function run(): string
    {
        $params = Yii::$app->request->get('DeviceSearch');
        $params = ArrayHelper::merge($params,[
            'deviceGroupData' => DeviceGroup::find()->hidden()->asDropDown(),
            'deviceGroupValue' => $this->getDeviceGroupValue($params),
            'statusData' => Device::getStatusList(),
            'statusValue' => $this->getStatusValue($params),
            'limitData' => $this->limits,
            'pageLimitValue' => $this->getPageLimitValue($params),
        ]);

        return $this->render($this->template, [
            'params' => $params,
        ]);
    }

    /**
     * @param array|null $params
     * @return string|null
     * @throws Exception
     */
    protected function getStatusValue(?array $params): ?string
    {
        if (ArrayHelper::getValue($params, 'status')) {
            return Device::getStatusList()[$params['status']];
        }

        return null;
    }

    /**
     * @param array|null $params
     * @return string|null
     * @throws Exception
     */
    protected function getPageLimitValue(?array $params): ?string
    {
        if (ArrayHelper::getValue($params, 'pageLimit')) {
            return (string)$this->limits[$params['pageLimit']];
        }

        return null;
    }

    /**
     * @param $params
     * @return string|null
     * @throws Exception
     */
    protected function getDeviceGroupValue($params): ?string
    {
        if (ArrayHelper::keyExists('deviceGroupId', $params)) {
            return (ArrayHelper::getValue($this->deviceGroupList, $params['deviceGroupId']));
        }

        return null;
    }
}
