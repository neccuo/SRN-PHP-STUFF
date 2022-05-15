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

    $pilot = $_POST["pilot_id"];
	$itemid = $_POST["item_id"];
	$quantity = $_POST["quantity"];

    $check_inventory = "SELECT quantity FROM pilot_inventories WHERE pilot_id = ". $pilot ." AND item_id = ". $itemid .";";
	$check_i_result = mysqli_query($link, $check_inventory) or die("2: Inventory check query failed");
    
    if (mysqli_num_rows($check_i_result) == 0)
    {
        $buy_item_query = "INSERT INTO pilot_inventories (pilot_id, item_id, quantity) VALUES (" . $pilot . ", " . $itemid . ", " . $quantity . ");";
        mysqli_query($link, $buy_item_query) or die("3: Buy item for NPC query failed");
	    echo("[BUY] 0: Transaction of *" . $quantity . "* item(s) [id: " . $itemid . "] by pilot [id: " . $pilot . "] is successful");

    }
    else if (mysqli_num_rows($check_i_result) == 1)
    {
        $inc_item_query = "UPDATE pilot_inventories SET quantity = quantity + " . $quantity . " WHERE pilot_id = ". $pilot ." AND item_id = ". $itemid .";";
        mysqli_query($link, $inc_item_query) or die("4: Increment item for NPC query failed");
	    echo("[INC] 0: Transaction of *" . $quantity . "* item(s) [id: " . $itemid . "] by pilot [id: " . $pilot . "] is successful");
    }
    else
    {
        throw new Exception("5: Something is not right!");
    }
?>