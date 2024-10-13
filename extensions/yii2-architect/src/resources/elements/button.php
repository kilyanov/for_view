<?php

use kilyanov\architect\entity\ElementEntity;
use yii\bootstrap5\Html;

/**
 * @var ElementEntity $element;
 */

echo Html::button($element->getName(), $element->getOptions());
