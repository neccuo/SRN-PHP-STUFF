<?php
	if($_SERVER["REQUEST_METHOD"] === "POST")
	{
		$number = $_POST["number"];
		
		if($number != 5)
		{
			echo "$number is incorrect";
		}
		else
		{
			echo "$number is correct";
		}
	}

?>
<form method="POST">
<input type="TEXT" name="number" />
<input type="SUBMIT" />
</form>