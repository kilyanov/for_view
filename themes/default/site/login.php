<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\LoginForm;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveForm $form
 * @var LoginForm $model
 */

$this->title = 'Вход в систему';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
            ]); ?>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>

            <div class="form-group">
                <div>
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
