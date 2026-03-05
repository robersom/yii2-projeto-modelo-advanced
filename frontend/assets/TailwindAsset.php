<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class TailwindAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/tailwind.min.css', // Agora como arquivo CSS real e LOCAL
    ];
    public $js = [
        // Remova o link do CDN daqui!
    ];
}
