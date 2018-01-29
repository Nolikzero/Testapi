<?php
namespace lib;

use api\Api;
use models\User;

/**
 * Class App
 * @package lib
 */
class App
{
    /**
     * @var Medoo
     */
    public static $db;
    /**
     * @var array
     */
    public static $config;
    /**
     * @var array
     */
    public static $request;

    /**
     * @var array
     */
    public static $files;

    /**
     * @var User
     */
    public static $user;

    public static $uploadDir;

    /**
     * App constructor.
     * @param array $config
     */
    function __construct($config = array())
    {
        self::$config = $config;
        self::$request = $_REQUEST;
        self::$files = $_FILES;
        self::$db = new Medoo(self::$config['db']);
        self::$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/upload';

        $this->authorization();
    }

    public function authorization(){
        if(isset($_SESSION['user_id'])){
            self::$user = User::findModel($_SESSION['user_id']);
        }
    }


    /**
     * @param $uri
     * @return array|bool
     */
    public function findRoute($request)
    {
        foreach (self::$config['api']['route'] as $uri => $method) {
            if (preg_match('|^(?:/?)' . $uri . '/(?P<action>[a-zA-Z]+)(?:/?)(?P<id>\d*)$|', $request, $matches)) {
                return array($uri, $method, $matches);
            }
        }
        return false;
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        if (isset(self::$request['request']) && self::$request['request'] != "") {
            list($uri, $method, $arguments) = $this->findRoute(self::$request['request']);
            if (class_exists($method)) {

                /**
                 * @var $api Api
                 */
                $api = new $method();
                if (isset($arguments['action']) && $arguments['action'] != "") {
                    $parts = explode('-', strtolower($arguments['action']));
                    $action = '';
                    foreach ($parts as $part) {
                        $action .= strtoupper(substr($part, 0, 1)) . substr($part, 1);
                    }
                    $action .= 'Action';

                    if (method_exists($api, $action)) {
                        $api->beforeAction();
                        $reflection = new \ReflectionMethod($api, $action);
                        $parameters = $reflection->getParameters();
                        $required_parameters = [];
                        foreach($parameters as $param){
                          if($param->isDefaultValueAvailable()) $required_parameters[$param->getPosition()] = $param->getDefaultValue();
                          if(array_key_exists($param->name, $arguments) && trim($arguments[$param->name])) $required_parameters[$param->getPosition()] = $arguments[$param->name];
                          if(!$param->isOptional() && !isset($required_parameters[$param->getPosition()])) throw new \Exception('Не передан обязательный параметр ' . $param->getName(), 500);
                        }

                        return $reflection->invokeArgs($api, $required_parameters);

                    }else{
                        throw new \Exception('Такой action в данном методе не найден', 404);
                    }
                }else{
                    throw new \Exception('Введите название action', 404);
                }
            }else{
                throw new \Exception('Неверный метод api', 404);
            }
        } else {
            throw new \Exception('Не передан метод', 404);
        }
    }
}
