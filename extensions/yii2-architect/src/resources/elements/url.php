<?php

use kilyanov\architect\entity\UrlEntity;
use yii\bootstrap5\Html;

/**
 * @var UrlEntity $element;
 */

echo Html::a($element->getName(), $element->getUrl(), $element->getOptions());
