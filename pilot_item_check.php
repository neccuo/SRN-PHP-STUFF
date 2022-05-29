<?php
    /*$pilotid = $_POST["pilot_id"];
	$itemid = $_POST["item_id"];
	$quantity = $_POST["quantity"];*/

    $checkout_price = "SELECT pilots.name as pilot_name, pilots.credits as pilot_credits, items.price * " . $quantity . " as total_price FROM pilots, items WHERE pilots.id = " . $pilotid 
	. " AND items.id = " . $itemid . ";";
	$checkout_result = mysqli_query($link, $checkout_price) or die("2: Price check query failed");

	$row = mysqli_fetch_assoc($checkout_result); // 1 OR 0 ROW IS EXPECTED
	if($row == null)
	{
		echo "Item or Pilot couldn't be found";
		exit();
	}
?>