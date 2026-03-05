<?php

/** @var \yii\web\View $this */
/** @var string $content */

use frontend\assets\AppAsset; // Mantenha o AppAsset que carrega o Tailwind e o YiiAsset
use yii\helpers\Html;
use common\widgets\Alert; // Importante para mostrar mensagens de sucesso/erro

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-full">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="flex flex-col h-full bg-gray-100 font-sans text-gray-900">
<?php $this->beginBody() ?>

    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="<?= Yii::$app->homeUrl ?>" class="text-2xl font-extrabold text-blue-600 tracking-tight">
                        🚀 Meu Modelo <span class="text-gray-700">Advanced</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#" class="text-gray-600 hover:text-blue-600 font-medium transition">Home</a>
                    
                    <?php if (Yii::$app->user->isGuest): ?>
                        <a href="<?= \yii\helpers\Url::to(['/site/login']) ?>" class="text-gray-600 hover:text-blue-600 font-medium">Login</a>
                    <?php else: ?>
                        <span class="text-gray-500 text-sm italic"><?= Yii::$app->user->identity->username ?></span>
                        <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'inline'])
                            . Html::submitButton('Sair', ['class' => 'text-red-500 hover:text-red-700 font-medium ml-2 cursor-pointer bg-transparent border-0'])
                            . Html::endForm() ?>
                    <?php endif; ?>

                    <a href="/admin" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-md transition transform hover:scale-105">
                        Painel Admin
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto mt-8 px-4 pb-12">
        <div class="max-w-4xl mx-auto">
            <?= Alert::widget() ?>
            
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <?= $content ?>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-6">
        <div class="container mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; <?= date('Y') ?> - Desenvolvido com Yii2 + Tailwind
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
