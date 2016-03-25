<html>
<head>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</head>
<body>
<?php

$description = array
('lc_id' => "ID",
 'lc_internal_name' => "Internal name",
 'lc_reg_name' => "Registration name",
 'lc_connection' => "Connection name",
 'lc_address' => "address",
 'lc_post_code' => "Post code",
 'lc_city' => "City",
 //country
 'lc_email' => "email",
 'lc_site' => "website",
 );


$i = 0;

foreach ($LC as $row)
{
	echo $row->lc_internal_name . ": <br>";

	if($has_board[$row->lc_id] == 0)
		continue;
	echo "<table class='table table-hover'>";

	$board = $current_boards[$row->lc_id];

	$j = 0;

	foreach($board as $member)
	{
		if($j == 0)
		{
			echo "<tr>";
			foreach($member as $key => $value)
			{
				echo "<th>" . $key . "</th>";
			}
			echo "</tr>";
		}
		
		echo "<tr>";
		foreach($member as $value)
		{
			echo "<td>" . $value . "</td>";
		}
		echo "</tr>";

		$j++;

	}

	echo "</table>";

}
<<<<<<< HEAD
echo "<br><br><br>" ;
echo "<table class='table table-hover'>";
=======
echo "<br><br><br>";
echo "<table border='1'>";
>>>>>>> origin/master
foreach ($LC as $row)
{
	if($i == 0)
	{
		echo "<tr>";
		foreach($row as $key => $value)
		{
			echo "<th>" . $description[$key] . "</th>";
		}
		
		echo "</tr>";
	}
	
	echo "<tr>";
	foreach($row as $value)
	{
		echo "<td>" . $value . "</td>";
	}
	echo "</tr>";

	$i++;
}
echo "</table>";



/*
foreach ($LC as $row)
{
	echo "LC Name: " . $row["lc_internal_name"] . "<br>";

	foreach($row["current_board"] as $position)
	{
		foreach($position as $key => $value)
		{
			echo $key . ": " . $value . "<br>";
		}
	}
	echo "<br>"
}
*/
//print_r($database_stuff->fetch_array(MYSQLI_NUM));

?>
</body>
</html>