<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// required headers
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "classApi.php";
require_once "classAppApi.php";
require_once "config.php";
require_once "vendor/autoload.php";
use \Firebase\JWT\JWT;

$app = new AppApi($auth['host'], $auth['user'], $auth['password'], $auth['db'],$secret_key );

$app->addRoute('POST','register','doRegister',['data' => 'json']);
$app->addRoute('POST','user','postUsers',['data' => 'json']);
$app->addRoute('POST','image','postImage',['data' => 'filedata']);
$app->addRoute('POST','imageUrl','postUrl',['data' => 'url']);
$app->addRoute('GET','user','getUsers',['id'=>'string']);
$app->addRoute('GET','menu','getMenus',['id'=>'int']);
$app->addRoute('GET','hash','getHash',['val'=>'string']);
$app->addRoute('GET','auth','getAuth',['email' => 'string','password'=>'string']);
$app->addRoute('GET','image','getImage',['data' => 'filedata']);


$app->handleRequest();