<?php
	include 'mysql_connect.php';

	mysqli_autocommit($link, false);

    $pilot_id = $_POST["pilot_id"];
	$item_id = $_POST["item_id"];
	$quantity = $_POST["quantity"]; // requested amount to sell

    include 'pilot_item_check.php'; // check if pilot and item exists

	$pilot_name = $row["pilot_name"];
	$pilot_credits = intval($row["pilot_credits"]);
	$total_price = intval($row["total_price"]);

    // CHECK IF PILOT HAS THE ITEM AND QUANTITY
    // 1 or 0 rows expected
    $can_pilot_sell = "SELECT * FROM pilot_inventories WHERE pilot_id = " . $pilot_id . " AND item_id = " . $item_id . " AND quantity <= " . $quantity . ";";
    $res = mysqli_query($link, $can_pilot_sell) or die("2: Inventory check query failed");

    if(mysqli_num_rows($res) == 1)
    {
        $row = mysqli_fetch_assoc($res);
    }
    else if(mysqli_num_rows($res) == 0)
    {
        echo("6: ". $pilot_name ." doesn't have the requested amount of items to sell in their inventroy");
        exit();
    }
    $inventory_quantity = intval($row["quantity"]);

    $item_new_quantity = intval($inventory_quantity) - intval($quantity);
	$pilot_new_money = $pilot_credits + $total_price;

	$error = array();
    $update_money = "UPDATE pilots SET credits = " . $pilot_new_money . " WHERE id = " . $pilot_id . ";";
	mysqli_query($link, $update_money) or array_push($error, "Money Update Error");

    if($item_new_quantity == 0) // delete
    {
        $delete_item_query = "DELETE FROM pilot_inventories WHERE pilot_id = ". $pilot_id ." AND item_id = ". $item_id .";";
        mysqli_query($link, $delete_item_query) or array_push($error, "Item Delete Error");
    }
    else if($item_new_quantity > 0) // update
    {
        $decrease_item_query = "UPDATE pilot_inventories SET quantity = quantity + " . $item_new_quantity . " WHERE pilot_id = ". $pilot_id ." AND item_id = ". $item_id .";";
        mysqli_query($link, $decrease_item_query) or array_push($error, "Item Decrease Error");
    }
    else {throw new Exception("5: Something is not right!");} // wtf

    $operation = "SELL";

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