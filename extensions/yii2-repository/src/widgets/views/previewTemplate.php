<?php

use yii\web\View;

/** @var $this View */

$src = 'data:image/svg+xml;base64,' . base64_encode('<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg version="1.1" width="100" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 317.001 317.001" style="enable-background:new 0 0 317.001 317.001;" xml:space="preserve">
<path d="M270.825,70.55L212.17,3.66C210.13,1.334,207.187,0,204.093,0H55.941C49.076,0,43.51,5.566,43.51,12.431V304.57c0,6.866,5.566,12.431,12.431,12.431h205.118c6.866,0,12.432-5.566,12.432-12.432V77.633C273.491,75.027,272.544,72.51,270.825,70.55z M55.941,305.073V12.432H199.94v63.601c0,3.431,2.78,6.216,6.216,6.216h54.903l0.006,222.824H55.941z"/>
</svg>');
?>
<div class="col-sm-6 col-md-2">
    <div class="thumbnail">
        <img class="img-thumbnail" src="<?= $src ?>" alt="" data-dz-thumbnail>
        <div class="caption">
            <p class="label label-success" data-dz-name></p>
            <p data-dz-size></p>
            <p class="label label-warning" data-dz-errormessage></p>
            <div class="progress">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0"
                     aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
            </div>
            <p>
                <a href="#" class="btn btn-danger" role="button" data-dz-remove>
                    Удалить
                </a>
            </p>
        </div>
    </div>
</div>
