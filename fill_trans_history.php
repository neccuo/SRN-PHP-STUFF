<?php
    $pilot_id;
    $item_id;
    $operation;
    $total_price;

    $error;
    $link;

    $fill_history = "INSERT INTO transaction_history (pilot_id, item_id, operation, total_price)
    VALUES (" . $pilot_id . ", " . $item_id . ", '" . $operation . "', " . $total_price . ");";

    if(mysqli_query($link, $fill_history))
    {
	    echo("0: [" .$operation. "] Transaction of *" . $quantity . "* item(s) [" . $item_id . "] 
        by pilot [" . $pilot_name . "] for [" . $total_price . "] credits is successful.");
    }
    else
    { 
        array_push($error, "Transaction Record Entry Failed");
    }
?>