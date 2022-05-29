<?php
	include 'mysql_connect.php';
	
	$json = $_POST["npcpos_json"];
	
	$arr = json_decode($json, true);
	
	$saveposquery = "";
	
	$counter = 0;
	$log = "";

	
	foreach($arr as $pilot) // DON'T FORGET THE COMMENTED LINES
	{
		$saveposquery = "UPDATE pilots SET x_axis = " . $pilot['x_axis']. ", y_axis = " . $pilot['y_axis'] . " WHERE id = " . $pilot['id'] . ";";
		$log = $log . $saveposquery;
		// mysqli_query($link, $saveposquery); // or die("2: failed to save pilot info (id: " . $pilot['id'] . ") ");
		if(!mysqli_query($link, $saveposquery))
		{
			throw new Exception("2: failed to save pilot info (id: " . $pilot['id'] . ") ");
		}
		$counter += 1;
	}
	
	
	echo($log); // SUCCESS
?>