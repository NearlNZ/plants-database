<?php
	$server='localhost';
	$username='root';
	$password='';
	$databaseName='comsci-plants';
	$database = new mysqli($server, $username, $password, $databaseName);
	if($database->connect_error) die('<br>'.$database->connect_error);
	$database->query('SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci');
	$database->query('SET character_set_results=utf8');
	$database->query('SET character_set_client=utf8');
	$database->query('SET character_set_connection=utf8');
	date_default_timezone_set('Asia/Bangkok');
?>