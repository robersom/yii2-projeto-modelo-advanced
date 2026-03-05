<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 * Controlador para gerenciar RBAC (Role-Based Access Control) via console.
 * Permite criar permissões, roles, atribuir roles a usuários e sincronizar permissões com base nos controllers da aplicação.
 * Exemplos de uso:
 * # php yii rbac/init - Inicializa o RBAC criando a permissão accessBackend e a role admin, atribuindo a permissão à role.
 * # php yii rbac/create-admin <nome> <email> <senha> - Cria um usuário admin e atribui a role admin.
 * # php yii rbac/crud <nome-do-recurso> - Cria permissões CRUD para um recurso específico.
 *  * Exemplo: php yii rbac/crud cliente
 *  Gerará as seguintes permissões:
 *      cliente.view
 *      cliente.create
 *      cliente.update
 *      cliente.delete
 * # php yii rbac/sync - Sincroniza os controllers do backend com as permissões RBAC, criando permissões para cada ação dos controllers.
 *  Se existir: ClienteController, PedidoController
 *      Gerará permissões como:
 *      cliente.index
 *      cliente.view
 *      cliente.create
 *      cliente.update
 *      cliente.delete
 *      pedido.index
 *      pedido.view
 *      pedido.create
 *      pedido.update
 *      pedido.delete
 * # php yii rbac/assign <role> <userId> - Atribui uma role a um usuário específico.
 * # php yii rbac/list - Lista todas as roles e permissões existentes.
 */
class RbacController extends Controller
{

    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Permissão accessBackend
        $permission = $auth->getPermission('accessBackend');

        if (!$permission) {
            $permission = $auth->createPermission('accessBackend');
            $permission->description = 'Permissão para acessar o painel administrativo';
            $auth->add($permission);

            echo "Permissão accessBackend criada.\n";
        }

        // Role admin
        $admin = $auth->getRole('admin');

        if (!$admin) {
            $admin = $auth->createRole('admin');
            $auth->add($admin);

            echo "Role admin criada.\n";
        }

        // Relacionar role → permissão
        if (!$auth->hasChild($admin, $permission)) {
            $auth->addChild($admin, $permission);
            echo "Permissão vinculada ao admin.\n";
        }

        echo "RBAC inicializado com sucesso!\n";
    }


    /**
     * Como usar no console:
     * # php yii rbac/create-admin <nome> <email> <senha>
     *    php yii rbac/create-admin admin admin@email.com 123456
     */
    public function actionCreateAdmin($username, $email, $password)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {

            if (\common\models\User::find()->where(['username' => $username])->exists()) {
                echo "Usuário já existe.\n";
                return;
            }

            $user = new \common\models\User();
            $user->username = $username;
            $user->email = $email;
            $user->setPassword($password);
            $user->generateAuthKey();
            $user->status = \common\models\User::STATUS_ACTIVE;

            if (!$user->save()) {
                throw new \Exception(implode(', ', $user->getFirstErrors()));
            }

            $auth = Yii::$app->authManager;

            $adminRole = $auth->getRole('admin');

            if (!$adminRole) {
                throw new \Exception('Role admin não encontrada. Rode: php yii rbac/init');
            }

            $auth->assign($adminRole, $user->id);

            $transaction->commit();

            echo "Usuário admin criado com sucesso!\n";
        } catch (\Throwable $e) {

            $transaction->rollBack();

            echo "Erro: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Cria CRUD RBAC
     * Exemplo de uso:
     * # php yii rbac/crud post
     * Gerará as seguintes permissões:
     * - post.view
     * - post.create
     * - post.update
     * - post.delete
     * - post.manage (que é a permissão pai de todas as anteriores)
     * Essas permissões podem ser atribuídas a roles ou usuários para controlar o acesso às ações relacionadas ao recurso "post".
     * O comando é útil para criar rapidamente um conjunto de permissões padrão para um recurso específico, facilitando a implementação de controle de acesso baseado em RBAC (Role-Based Access Control) em uma aplicação Yii2.
     * 
     */
    public function actionCrud($resource)
    {
        $auth = Yii::$app->authManager;

        $actions = ['view', 'create', 'update', 'delete'];

        foreach ($actions as $action) {

            $name = $resource . '.' . $action;

            if (!$auth->getPermission($name)) {

                $permission = $auth->createPermission($name);
                $permission->description = "$action $resource";

                $auth->add($permission);

                echo "Criado: $name\n";
            } else {
                echo "Permissão já existe: {$name}\n";
            }
        }

        echo "CRUD RBAC criado\n";
    }

    /**
     * Remove manualmente todas as permissões CRUD de um recurso
     * Exemplo de uso:
     * # php yii rbac/crud-delete post
     *  Removendo permissões de cliente...
     *      Removido: cliente.view
     *      Removido: cliente.create
     *      Removido: cliente.update
     *      Removido: cliente.delete
     *      Removido: cliente.manage
     *      Todas as permissões CRUD para cliente foram removidas.
     */
    public function actionCrudDelete($resource)
    {
        $auth = Yii::$app->authManager;
        $permissions = $auth->getPermissions();
        $deleted = 0;

        echo "Removendo permissões de $resource...\n";

        foreach ($permissions as $permission) {
            if (str_starts_with($permission->name, "$resource.") || $permission->name === "$resource.manage") {
                $auth->remove($permission);
                echo "Removido: {$permission->name}\n";
                $deleted++;
            }
        }

        if ($deleted === 0) {
            echo "Nenhuma permissão encontrada para $resource\n";
        } else {
            echo "Todas as permissões CRUD para $resource foram removidas.\n";
        }
    }

    /**
     * Sincroniza controllers → permissões
     * Exemplo de uso:
     * # php yii rbac/sync
     * 
     */
    public function actionSync()
    {
        $auth = Yii::$app->authManager;

        $path = Yii::getAlias('@backend/controllers');

        $files = scandir($path);

        foreach ($files as $file) {

            if (!str_contains($file, 'Controller.php')) {
                continue;
            }

            $controllerName = str_replace('Controller.php', '', $file);

            $class = "backend\\controllers\\$controllerName";

            if (!class_exists($class)) {
                continue;
            }

            $methods = get_class_methods($class);

            $resource = strtolower($controllerName);
            $resource = str_replace('controller', '', $resource);

            foreach ($methods as $method) {

                if (str_starts_with($method, 'action')) {

                    $action = lcfirst(substr($method, 6));

                    $permissionName = "$resource.$action";

                    if (!$auth->getPermission($permissionName)) {

                        $permission = $auth->createPermission($permissionName);
                        $permission->description = "$action $resource";

                        $auth->add($permission);

                        echo "Criado: $permissionName\n";
                    }
                }
            }
        }

        echo "RBAC sincronizado\n";
    }

    /**
     * Atribui role a usuário
     * Exemplo de uso:
     * # php yii rbac/assign admin 1
     * 
     */
    public function actionAssign($role, $userId)
    {
        $auth = Yii::$app->authManager;

        $roleObj = $auth->getRole($role);

        if (!$roleObj) {
            echo "Role não existe\n";
            return;
        }

        $auth->assign($roleObj, $userId);

        echo "Role $role atribuída ao usuário $userId\n";
    }

    /**
     * Lista roles e permissões
     * Exemplo de uso:
     * # php yii rbac/list
     * 
     */
    public function actionList()
    {
        $auth = Yii::$app->authManager;

        echo "\nROLES\n";

        foreach ($auth->getRoles() as $role) {
            echo "- " . $role->name . "\n";
        }

        echo "\nPERMISSÕES\n";

        foreach ($auth->getPermissions() as $perm) {
            echo "- " . $perm->name . "\n";
        }
    }
}
