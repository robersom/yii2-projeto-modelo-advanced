<?php

use yii\db\Migration;

class m260304_161232_seed_rbac_super_admin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // 1. Criar a Permissão "Tudo" (Coringa)
        $allPrivileges = $auth->createPermission('/*');
        $allPrivileges->description = 'Acesso total a todas as rotas';
        $auth->add($allPrivileges);

        // 2. Criar a Role Admin
        $superAdmin = $auth->createRole('superAdmin');
        $superAdmin->description = 'Acesso a todo o sistema';
        $auth->add($superAdmin);

        // 3. Dar a permissão "/*" para a Role "admin"
        $auth->addChild($superAdmin, $allPrivileges);

        // 4. Atribuir a Role ao seu usuário (ID 1 geralmente é o primeiro)
        // Certifique-se de que o usuário com ID 1 existe na tabela "user"
        $userExists = (new \yii\db\Query())->from('{{%user}}')->where(['id' => 1])->exists();

        if ($userExists) {
            $auth->assign($superAdmin, 1);
        }

        // 2. Criar a Permissão "Admin"
        $allPrivileges = $auth->createPermission('accessBackend');
        $allPrivileges->description = 'Permissão para acessar o painel administrativo';
        $auth->add($allPrivileges);

        // 2. Criar a Role Admin
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrador do Sistema';
        $auth->add($admin);

        // 3. Dar a permissão "/*" para a Role "admin"
        $auth->addChild($admin, $allPrivileges);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m260304_161232_seed_rbac_super_admin cannot be reverted.\n";
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260304_161232_seed_rbac_super_admin cannot be reverted.\n";

        return false;
    }
    */
}
