<?php

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

/**
 * @var array $items
 */

NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top'],
    'innerContainerOptions' => ['class' => 'container-fluid'],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => $items
]);
NavBar::end();
?>
