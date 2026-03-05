<?php

namespace common\rbac;

use yii\rbac\Rule;

/**
 * Verifica se o usuário é o autor do post
 * Essa regra pode ser usada para permitir que usuários editem apenas seus próprios posts, por exemplo.
 * Para usar essa regra, você precisará associá-la a uma permissão e depois verificar essa permissão no seu código, passando o modelo do post como parâmetro.
 * Exemplo de uso no controller:
 * if (Yii::$app->user->can('updateOwnPost', ['model' => $post])) {
 *     // O usuário pode atualizar o post
 * }
 * Lembre-se de registrar essa regra no seu RBAC e associá-la às permissões que desejar.
 */
class AuthorRule extends Rule
{
    public $name = 'isAuthor'; // Nome identificador da regra

    /**
     * @param string|int $user o ID do usuário logado
     * @param \yii\rbac\Item $item a permissão ou role associada
     * @param array $params parâmetros passados pelo código (ex: o model do post)
     * @return bool verdadeiro se a regra permitir o acesso
     */
    public function execute($user, $item, $params)
    {
        // Se o parâmetro 'model' não for passado, nega por segurança
        // O $params['model'] virá do seu controller mais tarde
        return isset($params['model']) ? $params['model']->created_by == $user : false;
    }
}

/*
EXEMPLO DE USO NO CONTROLLER:
public function actionUpdate($id)
{
    $model = $this->findModel($id);

    // O Yii vai rodar a classe AuthorRule e passar o $model para o método execute()
    if (!Yii::$app->user->can('updateOwnPost', ['model' => $model])) {
        throw new \yii\web\ForbiddenHttpException('Você só pode editar seus próprios posts.');
    }

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
    }

    return $this->render('update', [
        'model' => $model,
    ]);
}
*/
