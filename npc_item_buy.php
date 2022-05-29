<?php

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

	if (mysqli_connect_errno())
	{
		echo "1: connection failed " . mysqli_connect_error(); // error code #1 = connection failed
		exit();
	}

	mysqli_autocommit($link, false);

	// CHANGED THE VALUES FOR TESTING, UPDATE THEM ACCORDINGLY LATER
    $pilotid = $_POST["pilot_id"];
	$itemid = $_POST["item_id"];
	$quantity = $_POST["quantity"];



    $checkout_price = "SELECT pilots.name as pilot_name, pilots.credits as pilot_credits, items.price * " . $quantity . " as total_price FROM pilots, items WHERE pilots.id = " . $pilotid 
	. " AND items.id = " . $itemid . ";";
	$checkout_result = mysqli_query($link, $checkout_price) or die("2: Price check query failed");

	$row = mysqli_fetch_assoc($checkout_result); // 1 OR 0 ROW IS EXPECTED
	if($row == null)
	{
		echo "Item or Pilot couldn't be found";
		exit();
	}
	$pilot_name = $row["pilot_name"];
	$pilot_credits = intval($row["pilot_credits"]);
	$total_price = intval($row["total_price"]);
	if($pilot_credits < $total_price)
	{
		echo("" . $pilot_name . " could not afford " . strval($total_price) . " credits of item(s)");
		exit();
	}
	$pilot_new_money = $pilot_credits - $total_price;
	// echo($pilot_new_money);
	// exit();
	
	// CREATE OR INCREMENT
	$check_inventory = "SELECT pilots.name as pilot_name, items.name as item_name, pilot_inventories.quantity as inventory_quantity FROM pilot_inventories, pilots, items WHERE pilot_id = " . $pilotid . " AND item_id = " . $itemid
	. " AND pilots.id = pilot_inventories.pilot_id AND items.id = pilot_inventories.item_id;";
	$check_i_result = mysqli_query($link, $check_inventory) or die("2: Inventory check query failed");

	$error = array();
	$update_money = "UPDATE pilots SET credits = " . $pilot_new_money . " WHERE id = " . $pilotid . ";";
	mysqli_query($link, $update_money) or array_push($error, "Money Update Error");
    
    if (mysqli_num_rows($check_i_result) == 0) // buy
    {
        $buy_item_query = "INSERT INTO pilot_inventories (pilot_id, item_id, quantity) VALUES (" . $pilotid . ", " . $itemid . ", " . $quantity . ");";
        mysqli_query($link, $buy_item_query) or array_push($error, "Item Insert Error");
	    // echo("[BUY] 0: Transaction of *" . $quantity . "* item(s) [" . $itemid . "] by pilot [" . $pilotid . "] is successful");
    }
    else if (mysqli_num_rows($check_i_result) == 1) // inc
    {
        $inc_item_query = "UPDATE pilot_inventories SET quantity = quantity + " . $quantity . " WHERE pilot_id = ". $pilotid ." AND item_id = ". $itemid .";";
        mysqli_query($link, $inc_item_query) or array_push($error, "Item Update Error");
	    // echo("[INC] 0: Transaction of *" . $quantity . "* item(s) [" . $itemid . "] by pilot [" . $pilotid . "] is successful");
    }
    else{throw new Exception("5: Something is not right!");}

	if(empty($error)) // no errors
	{
	    echo("0: Transaction of *" . $quantity . "* item(s) [" . $itemid . "] by pilot [" . $pilot_name . "] for [" . $total_price . "] credits is successful.");
		mysqli_commit($link);
	}
	else // has errors
	{
		print_r($error);
		mysqli_rollback($link);
	}
?>