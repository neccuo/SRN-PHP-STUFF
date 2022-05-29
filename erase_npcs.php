<?php
	include 'mysql_connect.php';
	
	$deleteinventoryquery = "DELETE FROM pilot_inventories";
	$killnpcsquery = "DELETE FROM pilots";
	
	mysqli_query($link, $deleteinventoryquery) or die("2: Delete NPC inventories query failed");
	mysqli_query($link, $killnpcsquery) or die("3: Kill NPCs query failed");
	
	echo("0: ALL NPCs SUCCESSFULLY ERASED"); // SUCCESS
?>