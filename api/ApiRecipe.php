<?php
namespace api;

use lib\App;

use models\Recipe;
use models\User;

/**
 * Class ApiRecipe
 * @package api
 */
class ApiRecipe extends Api
{
    public function beforeAction()
    {
        if(!App::$user || !App::$user->id){
            throw new \Exception('У вас нет доступа к данному методу', 205);
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    function IndexAction()
    {
        $models = Recipe::find(['author' => App::$user->id]);

        if ($models) {
            $arr = [];
            foreach ($models as $model){
                $arr[] = $model->toArray();
            }
            return parent::result('', $arr);
        } else {
            throw new \Exception('Рецептов не найдено', 404);
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    function CreateAction()
    {
        $model = new Recipe();
        $model->load(App::$request);
        if ($model->save()) {
            return parent::result('Рецепт успешно создан');
        } else {
            throw new \Exception('Ошибка при создании рецепта', 500);
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    function UpdateAction($id)
    {
        $model = new Recipe();
        $model->id = $id;
        $model->load(App::$request);
        if ($model->save()) {
            return parent::result('Рецепт успешно отредактирован');
        } else {
            throw new \Exception('Ошибка при редактировании рецепта', 500);
        }
    }

    /**
     * @param $id
     * @return array
     * @throws \Exception
     */
    function ViewAction($id)
    {
        if ($recipe = Recipe::findOne(['id' => $id, 'author' => App::$user->id])) {
            return parent::result('', $recipe->toArray());
        }else {
            throw new \Exception('Рецепт с таким id не существует или у вас к нему нет доступа', 500);
        }
    }

    /**
     * @param $id
     * @return array
     * @throws \Exception
     */
    function DeleteAction($id)
    {
        if ($recipe = Recipe::findOne(['id' => $id, 'author' => App::$user->id])) {
            if($recipe->delete()){
                return parent::result('Рецепт успешно удален');
            }else{
                throw new \Exception('Ошибка при удалении рецепта', 500);
            }
        }else {
            throw new \Exception('Рецепт с таким id не существует или у вас к нему нет доступа', 500);
        }
    }

}
