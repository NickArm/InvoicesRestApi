<?php 
require_once('../controller/db.php');
require_once('../model/Response.php');
try {
	$maindb = DB::connectDB();
	$response = new Response();
	$response -> setHttpStatusCode(200);
	$response -> setSuccess(true);
	$response -> addMessage("DB connection ok");
	$response -> send();
	exit;
}
catch(PDOException $error_response){
	$response = new Response();
	$response -> setHttpStatusCode(500);
	$response -> setSuccess(false);
	$response -> addMessage("DB connect error");
	$response -> send();
	exit;
}