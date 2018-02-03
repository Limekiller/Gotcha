<?php
session_start();
if(!empty($_SESSION['lusername']) && !($_SESSION['lusername'] == '')){
	$login = true;
	$display1 = 'none';
	$display2 = 'inherit';
} else{
	$login = false;
	$display1 = 'inherit';
	$display2 = 'none';
}
?>

<!DOCTYPE html>
<link rel="stylesheet" type="text/css" href="./styles/view_spies.css" />
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
	<title>Goshen Gotcha | Spies</title>
</head>
<body>
<div class="wrapper">
<div class="header">VIEW SPIES</div>
<div style="height:150px;"></div>
<?php
require './config.php';

$sql = "SELECT username, description, target FROM users where admin != 1 or admin is NULL;";
$result = $link->query($sql);

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		if ($row["target"] == "killed"){
			$color = "background-color:#363636;";
		} else { $color = "background-color:#2d0e44;";}
		echo'<div class="spy" style="'.$color.'"><h1>'.$row["username"].'</h1><p>'.$row["description"].'</p></div>';
	}
	echo '<div style="height:150px;"></div>';
} else {
	echo "<h2 style='font-family:Franklin Gothic Medium;text-align:center;color:white;'>There's nothing here!</h2>";
}
$link->close();
?>
</div>
<div class="footer">
	<p style="display:inline;float:left;margin:13px;"><a href="/">Home</a>Created by Bryce Yoder, 2018</p>
	<a href='./logout.php' class='logout' style='float:right;display:<?php if($login == true){echo 'inline';}else{echo 'none';}?>'>Logout</a>
</div>
</body>
</html>
