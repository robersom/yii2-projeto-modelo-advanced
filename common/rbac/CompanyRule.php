<?php

namespace common\rbac;

use yii;
use yii\rbac\Rule;

/**
 * Regra de RBAC para verificar se um cliente pertence à mesma empresa do usuário logado.
 * Essa regra pode ser usada para garantir que um usuário só possa acessar ou manipular dados de clientes que pertencem à sua própria empresa.
 * Para usar essa regra, você precisará associá-la a uma permissão e depois verificar essa permissão no seu código, passando o modelo do cliente como parâmetro.
 * Exemplo de uso no controller:
 * if (Yii::$app->user->can('accessCompanyClient', ['client' => $cliente])) {
 *    // O usuário pode acessar o cliente
 * }
 * cliente.update
 *   rule: isCompanyClient
 */
class CompanyRule extends Rule
{
    public $name = 'isCompanyClient';

    public function execute($user, $item, $params)
    {
        return $params['client']->company_id == Yii::$app->user->identity->company_id;
    }
}
