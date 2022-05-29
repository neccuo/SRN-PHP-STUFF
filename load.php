<?php
	include 'mysql_connect.php';

	$selectpilot = "SELECT * FROM pilots;";
	
	$result = mysqli_query($link, $selectpilot) or die("2: query error");
	
	$ret = json_encode(mysqli_fetch_all($result));	
	
	/* $rows = array();
	
	while($r = mysqli_fetch_assoc($result))
	{
		$rows[] = $r;
	}
	
	$ret = json_encode($rows); */
	
	echo($ret);
?>