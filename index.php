<?php
session_start();

require_once (__DIR__ . '/vendor/autoload.php');
$config = require(__DIR__ . '/config/app.php');

header('Content-type:application/json;charset=utf-8');

try
{
    $result = (new \lib\App($config))->run();
    echo json_encode($result);
}catch (\Exception $exception)
{
    echo json_encode([
        'status' => $exception->getCode(),
        'message' => $exception->getMessage()
    ]);
}
