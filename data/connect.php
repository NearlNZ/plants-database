<?php
	$server='localhost';
	$username='root';
	$password='';
	$database='bpcs';
	$bpcsDB = new mysqli($server, $username, $password, $database);
	if($bpcsDB->connect_error) die('<br>'.$bpcsDB->connect_error);
	$bpcsDB->query('SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci');
	$bpcsDB->query('SET character_set_results=utf8');
	$bpcsDB->query('SET character_set_client=utf8');
	$bpcsDB->query('SET character_set_connection=utf8');
	date_default_timezone_set('Asia/Bangkok');
?>