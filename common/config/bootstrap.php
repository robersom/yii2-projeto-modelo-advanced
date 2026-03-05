<?php

// Carrega as variáveis do arquivo .env
if (file_exists(dirname(__DIR__, 2) . '/.env')) {
    // Usar UnsafeImmutable garante que as funções getenv() funcionem
    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(dirname(__DIR__, 2));
    $dotenv->load();
}

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
// Isso resolve o problema de classes antigas procurando pelo Bootstrap 3
Yii::setAlias('@yii/bootstrap', dirname(__DIR__, 2) . '/vendor/yiisoft/yii2-bootstrap5/src');
//Yii::$classMap['yii\bootstrap\NavBar'] = '@vendor/yiisoft/yii2-bootstrap5/src/NavBar.php';
//Yii::$classMap['yii\bootstrap\Nav'] = '@vendor/yiisoft/yii2-bootstrap5/src/Nav.php';



// Define as constantes do Yii baseadas no .env
//defined('YII_DEBUG') or define('YII_DEBUG', getenv('YII_DEBUG') === 'true');
//defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV') ?: 'prod');
