<?php
/**
 * Script de Limpeza do Modelo Yii2 Advanced
 * Remove arquivos temporários, logs e assets para compressão.
 */

$folders = [
    'frontend/runtime',
    'backend/runtime',
    'console/runtime',
    'frontend/web/assets',
    'backend/web/assets',
];

echo "--- Iniciando limpeza do modelo ---\n";

foreach ($folders as $folder) {
    $path = __DIR__ . DIRECTORY_SEPARATOR . $folder;
    
    if (is_dir($path)) {
        echo "Limpando: $folder... ";
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            // Não deleta o arquivo .gitignore se ele existir (mantém a estrutura)
            if ($fileinfo->getFilename() === '.gitignore') continue;
            
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        echo "OK!\n";
    }
}

echo "--- Limpeza concluída! Agora você pode zipar a pasta. ---\n";
