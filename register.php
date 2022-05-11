<?php
	
	/*ini_set("display_errors", 1);
	ini_set("log_errors", 1);
	ini_set("error_log", dirname(__FILE__), "/log.txt";
	error_reporting(E_ALL);*/
	$user = 'root';
	$password = 'root';
	$db = 'unityaccess';
	$host = 'localhost';
	$port = 3307;

	$link = mysqli_init();
	$success = mysqli_real_connect(
	   $link,
	   $host,
	   $user,
	   $password,
	   $db,
	   $port
	);

	/*
	$con = mysqli_connect("localhost", "root", "root", "unityaccess", 3307);
	*/
	if (mysqli_connect_errno())
	{
		echo "1: connection failed " . mysqli_connect_error(); // error code #1 = connection failed
		exit();
	}
	
	// BOK;
	
	$pilotid = $_POST["id"];
	$pilotname = $_POST["name"]; // $_POST["name"];
	$pilotcredit = $_POST["credits"];
	$pilotrace = $_POST["race"];
	$pilothullid = $_POST["hull_id"];
	$x_axis = $_POST["x_axis"];
	$y_axis = $_POST["y_axis"];
	
	
	// check if id exists
	$idcheckquery = "SELECT id FROM pilots WHERE id = " . $pilotid . ";";
	
	$idcheck = mysqli_query($link, $idcheckquery) or die("2: id check query failed"); // error code #2 = name check query failed
	if (mysqli_num_rows($idcheck) > 0)
	{
		throw new Exception("3: id already exists");
	}
	
	// strings will be inside ''
	$insertpilotquery = "INSERT INTO pilots (id, name, credits, race, hull_id, x_axis, y_axis) VALUES (" . $pilotid . ", '" . $pilotname . "', " . $pilotcredit . ", '" . $pilotrace . "', " . $pilothullid . ", ".$x_axis.", ".$y_axis.");";
	// $insertpilotquery = "INSERT INTO pilots(name) VALUES (' " . $pilotname . " ');";
	// $insertpilotquery = "INSERT INTO pilots (name, credits, race, hull_id) VALUES ('DENEME', 1500, 'human', 0);";
	// $res = mysqli_query($link, $insertpilotquery); // or die("4: Insert Pilot query failed");
	if(!mysqli_query($link, $insertpilotquery))
	{
		throw new Exception("4: Insert Pilot query failed");
	}
		
	
	echo("0"); // SUCCESS
?>







