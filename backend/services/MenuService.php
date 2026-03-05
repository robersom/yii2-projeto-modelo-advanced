<?php

namespace backend\services;

use Yii;

class MenuService
{
    public static function getMenu(): array
    {
        $menu = [];

        // 1. Grupo Principal (Sempre visível)
        $menu[] = [
            'label' => 'PRINCIPAL',
            'header' => true,
        ];
        $menu[] = [
            'label' => 'Dashboard',
            'icon' => 'tachometer-alt',
            'url' => ['/site/index'],
        ];

        // 2. Grupo Super Usuário (Visível apenas para quem tem a permissão 'superUser')
        $menu[] = [
            'label' => 'SUPER USUÁRIO',
            'header' => true,
            'permission' => 'superUser',
        ];
        $menu[] = [
            'label' => 'Permissões',
            'icon' => 'lock',
            'url' => ['/admin'],
            'permission' => 'superUser',
        ];

        // 3. Grupo de Cadastros
        $menu[] = [
            'label' => 'CADASTROS',
            'header' => true,
            'permission' => 'accessBackend',
        ];
        $menu[] = [
            'label' => 'Usuários',
            'icon' => 'users',
            'url' => ['/user/index'],
            'permission' => 'accessBackend',
        ];

        // 4. Grupo Financeiro (Exemplo de Submenu)
        $menu[] = [
            'label' => 'FINANCEIRO',
            'header' => true,
            'permission' => 'financeiro',
        ];
        $menu[] = [
            'label' => 'Contas',
            'icon' => 'file-invoice-dollar',
            'permission' => 'admin',
            'items' => [
                ['label' => 'Contas a Pagar', 'url' => ['/financeiro/contas-pagar']],
                ['label' => 'Contas a Receber', 'url' => ['/financeiro/contas-receber']],
            ],
        ];

        return self::filterMenu($menu);
    }

    private static function getRelatorioVendas(array &$menu)
    {
        $menu[] = [
            'label' => 'RELATÓRIOS',
            'header' => true,
            'permission' => 'admin',
        ];
        $menu = [
            'label' => 'Vendas',
            'icon' => 'chart-line',
            'url' => ['/relatorios/vendas'],
            'permission' => 'admin',
        ];
        return $menu;
    }

    private static function filterMenu(array $items): array
    {
        $filtered = [];

        foreach ($items as $item) {
            // Se o item tem permissão e o usuário NÃO a tem, pula
            if (isset($item['permission']) && !Yii::$app->user->can($item['permission'])) {
                continue;
            }

            // Se tem sub-itens, filtra recursivamente
            if (isset($item['items']) && is_array($item['items'])) {
                $item['items'] = self::filterMenu($item['items']);

                // Se o submenu ficou vazio após o filtro, remove o item pai
                if (empty($item['items'])) {
                    continue;
                }
            }

            $filtered[] = $item;
        }

        // Ajuste Final: Remover Headers que ficaram "sozinhos" (sem itens logo abaixo deles)
        return self::cleanupHeaders($filtered);
    }

    private static function cleanupHeaders(array $items): array
    {
        $clean = [];
        $count = count($items);

        foreach ($items as $index => $item) {
            if (isset($item['header']) && $item['header'] === true) {
                // Se for um header, verifica se o próximo item existe e NÃO é outro header
                $nextExists = isset($items[$index + 1]);
                $nextIsHeader = $nextExists && isset($items[$index + 1]['header']);

                if (!$nextExists || $nextIsHeader) {
                    continue; // Pula este header pois ele está vazio
                }
            }
            $clean[] = $item;
        }

        return $clean;
    }
}
