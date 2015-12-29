<?php
/*
@ Author: Ewere Diagboya
@ Date: 21-11-2015
@ Time: 1:04pm
@ Location: Ajah, Lagos
@ Project: Superstore WS
*/
require_once "Slim/Slim.php";
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

// Import Kernel/Db Functions
$kernel = (array)glob('kernel/*.php');
foreach ($kernel as $kernelFile) {
    require $kernelFile;
}

$app->get('/lickey/:key/:deviceid', function($key,$deviceid) use($app) {
	$params = array('key'=>$key, 'deviceid'=>$deviceid);
    $response = License::ValidateLicense($params);
	$app->response()->header("Content-Type", "application/json");
	echo json_encode($response, JSON_FORCE_OBJECT);
});


$app->post("/storeinfo", function () use($app) {
   $params = $app->request()->post();
   $response = License::StoreInfo($params);
   $app->response()->header("Content-Type", "application/json");
   echo json_encode($response, JSON_FORCE_OBJECT);
});

$app->run();