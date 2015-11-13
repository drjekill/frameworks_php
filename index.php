<?php
	define('SITE_PATH',realpath(dirname(__FILE__)).'/');
	define('FOLDER','/project1/');
	require_once('./application/config.php');

	try{
		Router::route(new Request);
	}catch(Exception $e){
		$controller = new errorController;
		$controller->error($e->getMessage());
	}
