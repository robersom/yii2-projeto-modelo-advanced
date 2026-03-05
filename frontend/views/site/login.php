<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */ // Note que o padrão é $model

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Acesso ao Sistema';
?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg border border-gray-100">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 tracking-tight">
                Bem-vindo de volta
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Entre com suas credenciais para acessar o painel
            </p>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'mt-8 space-y-6'],
            // Removemos os labels padrão do Bootstrap para um visual mais limpo com Tailwind
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700'],
                'errorOptions' => ['class' => 'text-red-500 text-xs mt-1'],
            ],
        ]); ?>

            <div class="rounded-md shadow-sm -space-y-px">
                <?= $form->field($model, 'username')->textInput([
                    'autofocus' => true,
                    'placeholder' => 'Seu usuário',
                    'class' => 'appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm'
                ])->label('Usuário') ?>

                <?= $form->field($model, 'password')->passwordInput([
                    'placeholder' => 'Sua senha',
                    'class' => 'appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm'
                ])->label('Senha') ?>
            </div>

            <div class="flex items-center justify-between mt-4">
                <?= $form->field($model, 'rememberMe', [
                    'template' => "<div class=\"flex items-center\">{input} {label}</div>\n{error}",
                ])->checkbox([
                    'class' => 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded',
                    'uncheck' => null 
                ], false)->label('Lembrar-me', ['class' => 'ml-2 block text-sm text-gray-900']) ?>
            </div>

            <div>
                <?= Html::submitButton('Entrar no Sistema', [
                    'class' => 'group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out',
                    'name' => 'login-button'
                ]) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
