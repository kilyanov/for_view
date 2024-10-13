<?php

declare(strict_types=1);

use yii\helpers\Html;

/**
 * @var $items array
 */

?>
<div class="card" style="border: 0;">
    <div class="card-body">
        <?php
        foreach ($items as $item) {
            echo Html::a(
                $item['text'],
                $item['url'],
                $item['options']
            );
        }
        ?>
    </div>
</div>
