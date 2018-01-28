<?php
namespace models;

use lib\App;

/**
 * Родительский класс для модели
 * Class Model
 * @package models
 */
class Model
{

    /**
     * @var int
     */
    public $id;

    /**
     * Model constructor.
     * @param null $values
     */
    function __construct($values = null)
    {
        $this->setAttributes($values);

    }

    /**
     * @param null $id
     * @return bool|Model
     */
    public static function findModel($id = null)
    {
        if ($id > 0) {
            $where['id'] = $id;
            return new self(App::$db->get(self::tableName(),
                '*',
                $where
            ));
        }
        return false;
    }

    /**
     * @param null $where
     * @return Model[]
     */
    public static function find($where = null)
    {
        $records = App::$db->select(self::tableName(), "*", $where);
        $models = [];

        foreach ($records as $record) {
            $class = get_called_class();
            $models[] = new $class($record);
        }

        return $models;
    }


    /**
     * @param null $where
     * @return
     */
    public static function findOne($where = null)
    {
        if($model = App::$db->get(self::tableName(),
            '*',
            $where
        ))
          return new self($model);
        else
          return false;
    }


    /**
     * @return Model[]
     */
    public static function findAll()
    {
        return self::find();
    }

    public function toArray(){
        $attributes = $this->attributes();
        $arr = [];
        foreach ($attributes as $attribute) {
            $arr[$attribute] = $this->$attribute;
        }
        return $arr;
    }

    /**
     * @param $where
     * @return bool|\PDOStatement
     */
    public static function deleteWhere($where)
    {
        return App::$db->delete(self::tableName(), $where);
    }

    /**
     * @param null $data
     */
    public function load($data = null)
    {
        if(isset($data['id']) && $data['id'] != ""){
          throw new \Exception('Записи с таким id не найдено', 404);
        }
        $this->setAttributes($data);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        return self::deleteWhere(['id' => $this->id]);
    }

    /**
     * @return bool
     */
    public function save()
    {
        if ($this->id) {
            return App::$db->update(self::tableName(), $this->toArray(), ['id' => $this->id]);
        } else {
            return App::$db->insert(self::tableName(), $this->toArray());
        }
    }

    /**
     * @return string
     */
    private static function tableName()
    {
        return strtolower(current(array_slice(explode('\\', get_called_class()), -1, 1)));
    }

    /**
     * Присваивает значение публичным аттрибутам класса
     * @param null $values
     */
    private function setAttributes($values = null)
    {
        if (is_array($values)) {
            $attributes = array_flip($this->attributes());

            foreach ($values as $name => $value) {
                if (isset($attributes[$name])) {
                    $this->$name = $value;
                }
            }
        }
    }

    /**
     * Возвращает значение публичных аттрибутов классов
     * @return mixed
     */
    private function values()
    {
        return array_values($this->toArray());
    }

    /**
     * Возвращает публичные аттрибуты класса
     * @return array
     */
    private function attributes()
    {
        $class = new \ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }

        return $names;
    }
}
