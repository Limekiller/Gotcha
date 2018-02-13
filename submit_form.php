<?php
session_start();
require_once './config.php';
if(!empty($_SESSION['lusername']) && !($_SESSION['lusername'] == '')){
	$sql = "SELECT target FROM users WHERE username = '".$_SESSION['lusername']."';";
	$result = $link->query($sql);
	$row = mysqli_fetch_array($result);
	if ($row["target"] == "killed"){
	header("Location: /");
	} else {
	$result = $link->query("show tables like 'game_running';");
	if($result->num_rows == 0){
		header("Location: /");
	} else {	
	$login = true;
	$display1 = 'none';
	$display2 = 'inherit';
	}
	}
} else{
	header("Location: /");
	$login = false;
	$display1 = 'inherit';
	$display2 = 'none';
}

$type = $person = $report_type = $comments = "";
$person_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(empty(trim($_POST["target"]))){
		$person_err = "Hey, you have to make a report ABOUT someone, my guy.";
	} else {
		$comments = trim($_POST["comments"]);
		$report_type = trim($_POST["report_type"]);
		$person = trim($_POST["target"]);
		$sql = "SELECT username FROM users WHERE username = ?";
		if($stmt = mysqli_prepare($link, $sql)){
			mysqli_stmt_bind_param($stmt, "s", $param_username);
			$param_username = $person;
			if(mysqli_stmt_execute($stmt)){
				mysqli_stmt_store_result($stmt);
				if(mysqli_stmt_num_rows($stmt)==1){
					$sql = "INSERT INTO reports (filed_by, against, type, comment) VALUES (?, ?, ?, ?)";  
					if($stmt = mysqli_prepare($link, $sql)){
						mysqli_stmt_bind_param($stmt, "ssss", $p_username, $p_person, $p_report_type, $p_comments);
						
						$p_username = $_SESSION['lusername'];
						$p_person = $person;
						$p_report_type = $report_type;
						$p_comments = $comments;

						if(mysqli_stmt_execute($stmt)){
							header('Location: /?submit=true');
						}	
					}
				}
			}
		}
	}
}
mysqli_close($link);
?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
	<title>Goshen Gotcha | Spies</title>
	<link rel="stylesheet" href="./styles/submit_form.css" />
        <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
        <link rel="stylesheet" href="http://gregfranko.com/jquery.selectBoxIt.js/css/jquery.selectBoxIt.css" />
</head>
<body>
<div class="page_wrapper">
<div class="header">SUBMIT FORM</div>
<div style="height:13vw;"></div>
<div class="content">
		<form id="report" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <label for="form_type">Report Type</label><br>
		<div class="selectbox">
			<select name="report_type">
				<option value="kill">I got somebody, heck yeah</option>
				<option value="killed">I got got, dang it</option>
				<option value="dispute">Somebody tried to get me and might submit a report but they're wrong lol</option>
			</select>
		</div>

		<label for="users">Person</label><br>
		<input autocomplete="off" name="target" style="margin-bottom:35px;margin-top:5px;" list="users">
		<p style="font-size:12px;margin-top:-35px;"><?php echo $person_err;?></p>
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
			</datalist><br>
		<label for="report">Comments</label><br>
		<textarea name="comments" style="margin-bottom:35px;margin-top:5px;font-family:Franklin Gothic Medium;"form="report" placeholder="Show me the deets"></textarea><br>

                <input type="submit" name="submit_btn" value="Submit">
            	</form>
	</div>
</div>
<div class="footer">
<p style="display:inline;float:left;margin:13px;"><a href="/">Home</a>Created by Bryce Yoder, 2018</p>
<a href='./logout.php' class='logout' style='float:right;display:<?php if($login == true){echo 'inline';}else{echo 'none';}?>'>Logout</a>
</div>
</body>
</html>
