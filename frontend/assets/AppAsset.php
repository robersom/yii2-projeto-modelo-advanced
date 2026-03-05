<?php

namespace frontend\assets;

use yii\web\AssetBundle;

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
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'frontend\assets\TailwindAsset', // Adicionamos a dependência do seu novo Asset
        // 'yii\bootstrap5\BootstrapAsset', // REMOVA ou COMENTE esta linha
    ];
}
