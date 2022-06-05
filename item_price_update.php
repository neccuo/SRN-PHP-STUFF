<?php
	include 'mysql_connect.php';

	mysqli_autocommit($link, false);

    $get_items_query = "SELECT * FROM items WHERE 1";

    $result = mysqli_query($link, $get_items_query) or die("2: Get Items query failed");

    $items = array();
    $error = array();

    while($item = mysqli_fetch_assoc($result))
    {
        $items[] = $item;
        $id = intval($item["id"]);
        $item_price = intval($item["price"]);
        $symbol = plus_or_minus();
        $change = changing_amount();
        $final_price = price_security_check($item_price, $symbol, $change);

        $query = update_price_query($final_price, $id);

        mysqli_query($link, $query) or array_push($error, "3: Item Update Query for (id: ".$item_id.") failed");
        
    }
    // print_r($items[0]["price"]);

    if(empty($error))
    {
        echo "Price Update Successful";
        mysqli_commit($link);
    }
    else
    {
        print_r($error);
        mysqli_rollback($link);
    }

    /*$result = mysqli_query($link, $get_items_query) or die("2: Get Items query failed");

    $itema = mysqli_fetch_all($result);
    print_r($itema);*/

    function update_price_query($new_price, $item_id)
    {
        return "UPDATE items SET price = " . $new_price . " WHERE id = " . $item_id . ";";
    }

    function plus_or_minus()
    {
        $r_num = rand(0, 1);
        if($r_num == 1)
            return 1;
        else
            return -1;
    }

    function changing_amount()
    {
        return rand(0, 100);
    }

    function price_security_check($item_price, $symbol, $change) // prevent items to go extremely low or extremely high
    {
        $final = $item_price + ($symbol * $change);
        if($final > 3000 or $final < 50)
            return $item_price;
        return $final;
    }

?>

