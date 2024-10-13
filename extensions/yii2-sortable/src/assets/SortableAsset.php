<?php

declare(strict_types=1);

namespace kilyanov\sortable\assets;

use yii\bootstrap5\BootstrapAsset;
use yii\jui\JuiAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\web\View;

/**
 * Class SortableAsset
 * @package kilyanov\sortable
 */
class SortableAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@kilyanov/sortable/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'sortable.js',
    ];

    /**
     * @var array
     */
    public $css = [
        'sortable.css',
    ];

    /**
     * @var array
     */
    public $depends = [
        JuiAsset::class,
        JqueryAsset::class,
        BootstrapAsset::class,
    ];

    /**
     * @param View $view
     */
    public function registerAssetFiles($view): void
    {
        parent::registerAssetFiles($view);
    }

}
