<?php
	include 'mysql_connect.php';
	
	$deleteinventoryquery = "DELETE FROM pilot_inventories;";
	$delete_trans_history = "DELETE FROM transaction_history;";
	$killnpcsquery = "DELETE FROM pilots;";
	
	mysqli_query($link, $deleteinventoryquery) or die("2: Delete NPC inventories query failed");
	mysqli_query($link, $delete_trans_history) or die("3: Delete Transaction History query failed");
	mysqli_query($link, $killnpcsquery) or die("4: Kill NPCs query failed");
	
	echo("0: ALL NPCs SUCCESSFULLY ERASED"); // SUCCESS
?>