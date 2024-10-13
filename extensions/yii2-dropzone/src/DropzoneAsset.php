<?php

declare(strict_types=1);

namespace kilyanov\dropzone;

use yii\web\AssetBundle;
use yii\web\YiiAsset;

class DropzoneAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@kilyanov/dropzone/dist';

    /**
     * @var array
     */
    public $js = [
        'dropzone.js',
    ];

    /**
     * @var array
     */
    public $css = [
        'min/basic.min.css',
    ];

    /**
     * @var array
     */
    public $depends = [
        YiiAsset::class,
    ];
}
