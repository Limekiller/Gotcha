<?php
session_start();
$link = new mysqli('localhost', 'root', 'codepurple', 'gotcha');
if(!empty($_SESSION['lusername']) && !($_SESSION['lusername'] == '')){
	$login = true;
	$display1 = 'none';
	$display2 = 'inherit';
	$result = $link->query("select * from users where username = '".$_SESSION['lusername']."' and admin = 1;");
	if($result->num_rows == 0){
		header("Location: /");
	}
} else{
	header("Location: /");
	$login = false;
	$display1 = 'inherit';
	$display2 = 'none';
}
$person = $person_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	if(empty(trim($_POST["target"]))){
		$person_err = "You have to enter in a name, ".$_SESSION["lusername"];
	} else {
		$person = trim($_POST["target"]);
		$result = $link->query("select * from users where username = '".$person."';");
		if($result->num_rows == 0){
			$person_err = "That user doesn't exist, fella!";
		}else{
			$result = $link->query("select * from users where username = '".$person."' and admin is NULL;");
			if($result->num_rows == 0){
				$link->query("update users set admin = NULL where username = '".$person."';");
	 			header("Location: /");
			} else{
				$link->query("update users set admin = 1 where username = '".$person."';");
	 			header("Location: /");
			}
		}
	}
}

if (isset($_GET['start_game'])){
	if ($_GET['start_game']){
		exec("python ./scripts/assign_targets.py 2>&1", $output);
		header('Location: ./admin.php');
	}
}elseif (isset($_GET['reset_game'])){
	if ($_GET['reset_game']){
		$sql = "delete from users where admin is NULL;";
		$link->query($sql);
		$sql = "delete from reports;";
		$link->query($sql);
		$sql = "update users set target = NULL;";
		$link->query($sql);
		header('Location: ./admin.php');
	}
}
$link->close();
?>

<!DOCTYPE html>
<link rel="stylesheet" type="text/css" href="./styles/admin.css" />
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
	<title>Goshen Gotcha | Spies</title>
</head>
<body>
<div class="wrapper">
<div class="header">ADMIN PANEL</div>
<div style="height:100px;"></div>
<div style="text-align:center;">
<label><h2>Game controls</h2></label>
<a class='button'href="?start_game=true" style="text-decoration:none;"><div style="margin-top:25px;"class="submit_btn">Start Game</div></a><br>
<a class='button'href="?reset_game=true" style="text-decoration:none;"><div class="submit_btn">Reset Game</div></a>
		<form id="report" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
		<div style="height:50px;"></div>
		<label style="margin-top:100px;"for="users"><h2>Toggle admin for user</h2></label><br>
		<input autocomplete="off" name="target" style="margin-bottom:10px;margin-top:5px;" list="users">
		<datalist name="users" id="users" >
			<?php
			require './config.php';
			
			$sql = "SELECT username, description FROM users";
			$result = $link->query($sql);
			
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					echo'<option value="'.$row["username"].'">';
				}
			} else {
				echo "0 results";
			}
			$link->close();
			?>
			</datalist>
		<br>
                <input type="submit" name="submit_btn" value="Submit">
            	</form>
		<div style="height:75px;"></div>
		<label><h2 style="margin-bottom:-15px;">Submitted reports</h2></div>
		<?php
		$link = new mysqli("localhost", "root", "codepurple", "gotcha");
		$result = $link->query("select * from reports order by time desc;");		
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				if($row["type"] == "kill"){
					$email1 = $link->query("select email from users where username = '".$row["filed_by"]."';")->fetch_assoc();
					$email2 = $link->query("select email from users where username = '".$row["against"]."';")->fetch_assoc();
					echo '<div class="report"><h2>'.$row["filed_by"].' killed '.$row["against"].' at '.$row["time"].'</h2><h4>'.$email1["email"].'<br>'.$email2["email"].'</h4><p>'.$row["comment"].'</p></div>';
				} else { echo '<div class="report"><h2>'.$row["filed_by"].' was killed by '.$row["against"].' at '.$row["time"].'</h2><h4>'.$email2["email"].'<br>'.$email1["email"].'</h4><p>'.$row["comment"].'</p></div>';}
			}
		} else{ echo "<h4 style='text-align:center;color:white;'>There's nothing here!</h4>";}
		$link->close();
?>
<div style="height:50px;"></div>
</div>
</div>
<div class="footer">
	<p style="display:inline;float:left;margin:13px;"><a href="/">Home</a>Created by Bryce Yoder, 2018</p>
	<a href='./logout.php' class='logout' style='float:right;display:<?php if($login == true){echo 'inline';}else{echo 'none';}?>'>Logout</a>
</div>
</div>
</body>
</html>
