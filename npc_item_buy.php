<?php
	include 'mysql_connect.php';

	mysqli_autocommit($link, false);

    $pilot_id = $_POST["pilot_id"];
	$item_id = $_POST["item_id"];
	$quantity = $_POST["quantity"];

	include 'pilot_item_check.php';

	$pilot_name = $row["pilot_name"];
	$pilot_credits = intval($row["pilot_credits"]);
	$total_price = intval($row["total_price"]);

	if($pilot_credits < $total_price)
	{
		echo("-1: " . $pilot_name . " could not afford " . strval($total_price) . " credits of item(s)");
		exit();
	}
	$pilot_new_money = $pilot_credits - $total_price;
	
	// CREATE OR INCREMENT
	$check_inventory = "SELECT pilots.name as pilot_name, items.name as item_name, pilot_inventories.quantity as inventory_quantity FROM pilot_inventories, pilots, items WHERE pilot_id = " . $pilot_id . " AND item_id = " . $item_id
	. " AND pilots.id = pilot_inventories.pilot_id AND items.id = pilot_inventories.item_id;";
	$check_i_result = mysqli_query($link, $check_inventory) or die("2: Inventory check query failed");

	$error = array();
	$update_money = "UPDATE pilots SET credits = " . $pilot_new_money . " WHERE id = " . $pilot_id . ";";
	mysqli_query($link, $update_money) or array_push($error, "Money Update Error");
    
    if (mysqli_num_rows($check_i_result) == 0) // buy
    {
        $buy_item_query = "INSERT INTO pilot_inventories (pilot_id, item_id, quantity) VALUES (" . $pilot_id . ", " . $item_id . ", " . $quantity . ");";
        mysqli_query($link, $buy_item_query) or array_push($error, "Item Insert Error");
	    // echo("[BUY] 0: Transaction of *" . $quantity . "* item(s) [" . $item_id . "] by pilot [" . $pilot_id . "] is successful");
    }
    else if (mysqli_num_rows($check_i_result) == 1) // inc
    {
        $inc_item_query = "UPDATE pilot_inventories SET quantity = quantity + " . $quantity . " WHERE pilot_id = ". $pilot_id ." AND item_id = ". $item_id .";";
        mysqli_query($link, $inc_item_query) or array_push($error, "Item Update Error");
	    // echo("[INC] 0: Transaction of *" . $quantity . "* item(s) [" . $item_id . "] by pilot [" . $pilot_id . "] is successful");
    }
    else{throw new Exception("5: Something is not right!");}

    $operation = "BUY";

	include 'fill_trans_history.php';

	if(empty($error)) // no errors
	{
		mysqli_commit($link);
	}
	else // has errors
	{
		print_r($error);
		mysqli_rollback($link);
	}
?>