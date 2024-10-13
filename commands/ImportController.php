<?php

declare(strict_types=1);

namespace app\commands;

use app\modules\application\models\Application;
use app\modules\application\models\ApplicationData;
use app\modules\contract\models\Contract;
use app\modules\contract\models\ContractSpecification;
use app\modules\device\models\Device;
use app\modules\device\models\DeviceGroup;
use app\modules\device\models\DeviceInfoVerification;
use app\modules\device\models\DeviceName;
use app\modules\device\models\DeviceProperty;
use app\modules\device\models\DeviceRejection;
use app\modules\device\models\DeviceStandard;
use app\modules\device\models\DeviceToImpact;
use app\modules\device\models\DeviceToUnit;
use app\modules\device\models\DeviceType;
use app\modules\device\models\DeviceVerification;
use app\modules\impact\models\Impact;
use app\modules\industry\models\Machine;
use app\modules\industry\models\OrderList;
use app\modules\industry\models\OrderRationing;
use app\modules\industry\models\OrderRationingData;
use app\modules\industry\models\OrderRationingDataClose;
use app\modules\industry\models\OrderToImpact;
use app\modules\industry\models\OrderToProduct;
use app\modules\industry\models\OrderToUnit;
use app\modules\industry\models\PresentationBook;
use app\modules\industry\models\PresentationBookDataDeviceRepair;
use app\modules\industry\models\PresentationBookDataProduct;
use app\modules\industry\models\RepairProduct;
use app\modules\institution\models\Institution;
use app\modules\nso\models\Stand;
use app\modules\nso\models\StandChart;
use app\modules\nso\models\StandDateService;
use app\modules\personal\models\Personal;
use app\modules\personal\modules\group\models\PersonalGroup;
use app\modules\personal\modules\special\models\PersonalSpecial;
use app\modules\product\models\Product;
use app\modules\product\models\ProductBlock;
use app\modules\product\models\ProductNode;
use app\modules\rationing\models\RationingDevice;
use app\modules\rationing\models\RationingDeviceData;
use app\modules\rationing\models\RationingProduct;
use app\modules\rationing\models\RationingProductData;
use app\modules\resource\models\Resource;
use app\modules\unit\models\Unit;
use Exception;
use Yii;
use yii\console\Controller;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\Json;

/**
 *
 * @property-write array $data
 */
class ImportController extends Controller
{
    /**
     * @var array
     */
    private array $listImport = [
        [
            'class' => Unit::class,
            'filename' => 'tbl_unit.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => PersonalGroup::class,
            'filename' => 'tbl_personal_group.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => PersonalSpecial::class,
            'filename' => 'tbl_personal_special.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => Personal::class,
            'filename' => 'tbl_personal.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => Product::class,
            'filename' => 'tbl_product.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => ProductNode::class,
            'filename' => 'tbl_product_node.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => ProductBlock::class,
            'filename' => 'tbl_product_block.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => Impact::class,
            'filename' => 'tbl_impact.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => Institution::class,
            'filename' => 'tbl_institution.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => Contract::class,
            'filename' => 'tbl_contract.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => ContractSpecification::class,
            'filename' => 'tbl_contract_specification.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => Resource::class,
            'filename' => 'tbl_resource.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => DeviceGroup::class,
            'filename' => 'tbl_device_group.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => DeviceType::class,
            'filename' => 'tbl_device_type.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => DeviceName::class,
            'filename' => 'tbl_device_name.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => DeviceProperty::class,
            'filename' => 'tbl_device_property.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => Device::class,
            'filename' => 'tbl_device.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => DeviceInfoVerification::class,
            'filename' => 'tbl_device_info_verification.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => DeviceToUnit::class,
            'filename' => 'tbl_device_to_unit.json',
            'status' => false,
            'callback' => null,
            'detachBehavior' => ['IdAttributeBehavior', 'StatusBehavior']
        ],
        [
            'class' => DeviceToImpact::class,
            'filename' => 'tbl_device_to_impact.json',
            'status' => false,
            'callback' => null,
            'detachBehavior' => ['IdAttributeBehavior', 'StatusBehavior']
        ],
        [
            'class' => DeviceVerification::class,
            'filename' => 'tbl_device_verification.json',
            'status' => false,
            'callback' => null,
            'detachBehavior' => ['IdAttributeBehavior', 'StatusBehavior']
        ],
        [
            'class' => DeviceStandard::class,
            'filename' => 'tbl_device_standard.json',
            'status' => false,
            'callback' => null,
            'detachBehavior' => ['IdAttributeBehavior', 'StatusBehavior']
        ],
        [
            'class' => DeviceRejection::class,
            'filename' => 'tbl_device_rejection.json',
            'status' => false,
            'callback' => null,
            'detachBehavior' => ['IdAttributeBehavior', 'StatusBehavior']
        ],
        [
            'class' => DeviceInfoVerification::class,
            'filename' => 'tbl_device_info_verification.json',
            'status' => false,
            'callback' => null,
            'detachBehavior' => ['IdAttributeBehavior', 'StatusBehavior']
        ],
        [
            'class' => Machine::class,
            'filename' => 'tbl_machine.json',
            'status' => false,
            'callback' => null,
            'detachBehavior' => null
        ],
        [
            'class' => RepairProduct::class,
            'filename' => 'tbl_repair_product.json',
            'status' => false,
            'callback' => null,
            'detachBehavior' => null
        ],
        [
            'class' => OrderList::class,
            'filename' => 'tbl_order_list.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => OrderToProduct::class,
            'filename' => 'tbl_order_to_product.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => OrderToImpact::class,
            'filename' => 'tbl_order_to_impact.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => OrderToUnit::class,
            'filename' => 'tbl_order_to_unit.json',
            'status' => false,
            'callback' => null
        ],
        [
            'class' => RationingDevice::class,
            'filename' => 'tbl_rationing_device.json',
            'status' => false,
            'callback' => 'prepareRationingDevice',
            'detachBehavior' => ['IdAttributeBehavior']
        ],
        [
            'class' => RationingDeviceData::class,
            'filename' => 'tbl_rationing_device_data.json',
            'status' => false,
            'detachBehavior' => ['IdAttributeBehavior']
        ],
        [
            'class' => RationingProduct::class,
            'filename' => 'tbl_rationing_product.json',
            'status' => false,
            'detachBehavior' => ['IdAttributeBehavior']
        ],
        [
            'class' => RationingProductData::class,
            'filename' => 'tbl_rationing_product_data.json',
            'status' => false,
        ],
        [
            'class' => OrderRationing::class,
            'filename' => 'tbl_order_rationing.json',
            'status' => false,
            'detachBehavior' => ['IdAttributeBehavior']
        ],
        [
            'class' => OrderRationingData::class,
            'filename' => 'tbl_order_rationing_data.json',
            'status' => false,
            'detachBehavior' => ['IdAttributeBehavior']
        ],
        [
            'class' => OrderRationingDataClose::class,
            'filename' => 'tbl_order_rationing_data_close.json',
            'status' => false,
            'detachBehavior' => ['IdAttributeBehavior']
        ],
        [
            'class' => Application::class,
            'filename' => 'tbl_application.json',
            'status' => false,
        ],
        [
            'class' => ApplicationData::class,
            'filename' => 'tbl_application_data.json',
            'status' => false,
        ],
        [
            'class' => Stand::class,
            'filename' => 'tbl_stand.json',
            'status' => false,
        ],
        [
            'class' => StandDateService::class,
            'filename' => 'tbl_stand_date_service.json',
            'status' => false,
        ],
        [
            'class' => StandChart::class,
            'filename' => 'tbl_stand_chart.json',
            'status' => false,
        ],
        [
            'class' => PresentationBook::class,
            'filename' => 'tbl_presentation_book.json',
            'status' => true,
            'detachBehavior' => ['IdAttributeBehavior']
        ],
        [
            'class' => PresentationBookDataDeviceRepair::class,
            'filename' => 'tbl_presentation_book_data_device_repair.json',
            'status' => true,
        ],
        [
            'class' => PresentationBookDataProduct::class,
            'filename' => 'tbl_presentation_book_data_product.json',
            'status' => true,
        ],
    ];

    /**
     * @throws Exception
     */
    public function actionIndex(?int $indexStart = null, ?int $indexEnd = null): void
    {
        foreach ($this->listImport as $key => $item) {
            if ($indexStart !== null) {
                if ($indexStart > $key) {
                    continue;
                }
                else {
                    if ($indexEnd === $key) {
                        break;
                    }
                    else {
                        $this->setData($item);
                    }
                }
            }
            else {
                if (ArrayHelper::getValue($item, 'status', false) === true) {
                    $this->setData($item);
                }
            }
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function actionBmProductOrder(): void
    {
        $content = file_get_contents(
            Yii::getAlias('@app') .
            DIRECTORY_SEPARATOR . 'commands/data/0187ac29-b191-7025-9f0d-ca9134231c65.json'
        );
        $data = Json::decode($content);
        $orders = ['#1', '#2', '#3',];
        foreach ($orders as $order) {
            $orderModel = OrderList::find()->andWhere(['number' => $order])->one();
            foreach ($data as $add) {
                $orderProduct = new OrderToProduct($add);
                $orderProduct->orderId = $orderModel->id;
                $orderProduct->save();
            }
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function actionBmApplicationOrder(): void
    {
        $content = file_get_contents(
            Yii::getAlias('@app') .
            DIRECTORY_SEPARATOR . 'commands/data/03dc2127-b5a7-4dbd-9c2f-2b01b459117f.json'
        );

        $orders = ['#1', '#2', '#3',];
        foreach ($orders as $order) {
            echo '$order: ' . $order; echo PHP_EOL;
            $orderModel = OrderList::find()->andWhere(['number' => $order])->one();
            echo '$orderModel->id: ' . $orderModel->id; echo PHP_EOL;
            $data = Json::decode($content);
            foreach ($data as $add) {
                $application = Application::find()->andWhere(['id' => $add['id']])->one();
                echo '$application->id: ' . $application->id; echo PHP_EOL;
                unset($add['id']);
                $orderApplication = new Application($add);
                $orderApplication->orderId = $orderModel->id;
                $orderApplication->number = str_replace($application->orderRelation->number, $order, $orderApplication->number);
                $product = OrderToProduct::find()->andWhere([
                    'orderId' => $orderModel->id,
                    'productId' => $application->productRelation->productRelation->id
                ])->one();
                echo '$product->id: ' . $product->id; echo PHP_EOL;
                $orderApplication->productId = $product->id;
                $orderApplication->comment = $product->getFullName();
                $orderApplication->save();
                var_dump($orderApplication->errors);
                if (count($application->applicationDatasRelation) > 0) {
                    foreach ($application->applicationDatasRelation as $data) {
                        $addData = new ApplicationData($data->getAttributes());
                        $addData->applicationId = $orderApplication->id;
                        $addData->save();
                    }
                }
            }
        }
    }

    /**
     * @param array $item
     * @return void
     * @throws Exception
     */
    private function setData(array $item): void
    {
        $filename = ArrayHelper::getValue($item, 'filename');
        $prefix = getenv('PREFIX_IMPORT');
        if ($prefix != '') {
            $filename = str_replace($prefix, '', $filename);
        }
        $content = file_get_contents(
            Yii::getAlias('@import') .
            DIRECTORY_SEPARATOR . $filename
        );
        $content = substr($content, 1);
        $models = Json::decode($content);
        if (!empty($models['data'])) {
            $classname = ArrayHelper::getValue($item, 'class');
            $classname::deleteAll(['not', ['id' => null]]);
            $total = count($models['data']);
            $all = $create = $error = 0;

            foreach ($models['data'] as $model) {
                $all++;
                $callback = ArrayHelper::getValue($item, 'callback');
                if ($callback !== null) {
                    $model = $this->{$callback}($model);
                }
                /** @var ActiveRecord $obj */
                $obj = new $classname($model);
                $detachBehavior = ArrayHelper::getValue($item, 'detachBehavior');
                if ($detachBehavior !== null) {
                    foreach ($detachBehavior as $detach) {
                        $obj->detachBehavior($detach);
                    }
                }
                else {
                    $obj->detachBehavior('IdAttributeBehavior');
                }
                if ($obj->save()) {
                    $create++;
                }
                else {
                    $error++;
                    //var_dump($obj->errors);
                }
                Console::updateProgress($all, $total);
            }
            Console::endProgress(true);
        }

        print $classname . ' All: ' . $all . '; create: ' . $create . '; error: ' . $error . PHP_EOL;
    }


    /**
     * @param array $model
     * @return array
     */
    protected function prepareRationingDevice(array $model): array
    {
        return [
            'id' => $model['id'],
            'paragraph' => $model['paragraph'],
            'name' => $model['name'],
            'norma' => $model['norma'],
            'sort' => (integer)$model['paragraph'],
            'hidden' => $model['hidden'],
        ];
    }
}
