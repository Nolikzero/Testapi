<?php
namespace models;

use lib\App;

/**
 * Class User
 * @package models
 */
class Recipe extends Model
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $photo;

    /**
     * @var int
     */
    public $author;


    /**
     * @return bool
     * @throws \Exception
     */
    public function save()
    {
        $this->author = App::$user->id;

        if(!$this->name){
            throw new \Exception("Задайте название рецепта", 500);
        }
        if(!$this->photo){
            throw new \Exception("Загрузите фотографию рецепта", 500);
        }

        if($this->id){
            if($recipe = Recipe::findOne(['id' => $this->id])){
                if($recipe['author'] != $this->author){
                    throw new \Exception('У вас нет доступа редактировать этот рецепт', 405);
                }
            }
        }
        return parent::save(); // TODO: Change the autogenerated stub
    }

    /**
     * @param null $data
     * @throws \Exception
     */
    public function load($data = null)
    {
        if(is_array(App::$files) && count(App::$files)) {
            foreach (App::$files as $file){
                if(!in_array($file['type'],
                    [
                        'jpg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif'
                    ]
                )){
                    throw new \Exception('Неверный формат загружаемого файла', 500);
                }

                $type = pathinfo($file['name']);
                $name = uniqid('files_') .'.'. $type['extension'];
                $path = App::$uploadDir .'/'.$name;
                move_uploaded_file($file['tmp_name'],$path);
                $this->photo = $name;
            }
        }
        parent::load($data); // TODO: Change the autogenerated stub
    }

}