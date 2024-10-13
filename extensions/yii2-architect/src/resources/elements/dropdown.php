<?php

use kilyanov\architect\entity\DropdownEntity;
use yii\bootstrap5\Html;

/**
 * @var DropdownEntity $element ;
 */

?>
<div class="btn-group">
    <div class="dropdown">
        <?= Html::a($element->getName(), '#', $element->getOptions()); ?>
        <ul class="dropdown-menu">
            <?php foreach ($element->getItems() as $item): ?>
                <li><?= $item ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
