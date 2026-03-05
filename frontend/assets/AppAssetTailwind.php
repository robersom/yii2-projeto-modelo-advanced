<?php

namespace frontend\assets;

use yii\web\AssetBundle;

//=================================================================================================================================
// To revert to using Tailwind, delete the AppAsset.php file, copy the AppAssetTailwind.php file, and rename it to AppAsset.php.
// To revert to using Bootstrap, delete the AppAsset.php file, copy the AppAssetOriginal.php file, and rename it to AppAsset.php.
//=================================================================================================================================
// Para voltar a usar o Tailwind, apague o arquivo AppAsset.php, copie o AppAssetTailwind.php e o renomeie para AppAsset.php
// Para voltar a usar o bootstrap, apague o arquivo AppAsset.php, copie o AppAssetOriginal.php e o renomeie para AppAsset.php
//=================================================================================================================================

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'frontend\assets\TailwindAsset', // Adicionamos a dependência do seu novo Asset
        // 'yii\bootstrap5\BootstrapAsset', // REMOVA ou COMENTE esta linha
    ];
}
