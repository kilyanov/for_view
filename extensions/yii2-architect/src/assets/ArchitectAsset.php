<?php

declare(strict_types=1);

namespace kilyanov\architect\assets;

use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\web\YiiAsset;

/**
 * Class ArchitectAsset
 * @package kilyanov\architect
 */
class ArchitectAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@kilyanov/architect/assets/dist';

    /**
     * @var array
     */
    public $css = [];

    /**
     * @var array
     */
    public $js = [
        'ModalRemote.js',
        'architect.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class
    ];

    /**
     * @param View $view
     */
    public function registerAssetFiles($view): void
    {
        parent::registerAssetFiles($view);
    }
}
