<?php
	
	//config database
	
	define('SERVER_DB', 'localhost' );
	define('USER_DB', 'root' );
	define('PASS_DB', '1q2w3e' );
	define('NAME_DB', 'vielmisa' );

	require_once('./application/request.php');
	require_once('./application/router.php');
	require_once('./application/baseController.php');
	require_once('./application/baseModel.php');
	require_once('./application/load.php');
	require_once('./application/registry.php');
	require_once('./controllers/errorController.php');