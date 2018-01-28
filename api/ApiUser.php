<?php
namespace api;

use lib\App;

use models\User;

class ApiUser extends Api
{

    function CreateAction()
    {
        $model = new User();
        $model->load(App::$request);
        if ($model->save()) {
            return parent::result('Пользователь успешно создан');
        } else {
            throw new \Exception('Ошибка при создании пользователя', 500);
        }
    }

    function LoginAction()
    {
        $username = App::$request['username'];
        $password = md5(App::$request['password']);

        if ($user = User::findOne(['username' => $username, 'password' => $password])) {
            $_SESSION['user_id'] = $user->id;
            return parent::result('Вы успешно авторизовались');
        }else {
            throw new \Exception('Неверный логин или пароль', 500);
        }
    }

    function LogoutAction()
    {
        unset($_SESSION['user_id']);
        return parent::result('Вы вышли');
    }

}
