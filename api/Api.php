<?php
namespace api;

use lib\App;

abstract class Api
{

    public function beforeAction(){
        
    }

    public function result($message = '', $payload = array())
    {
        $result = [
            'status' => 200,
        ];
        if ($message) {
            $result['message'] = $message;
        }
        if ($payload) {
            $result['data'] = $payload;
        }
        return $result;
    }
}