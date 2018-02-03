<?php
$show = 'none';
session_start();
if (empty($_SESSION['try_count'])) {
	$_SESSION['try_count'] = 1;
	$_SESSION['start_time'] = time();
} else {
	if (time() - $_SESSION['start_time'] > 60){
		$_SESSION['try_count'] = 0;
	} else {
		$_SESSION['try_count']++;
	}
}

if(!empty($_SESSION['lusername']) && !($_SESSION['lusername'] == '')){
	header('location: ./index.php');
}

require_once './config.php';

$username = $password = $confirm_password = $email = $description = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";
$lusername_err = $lpassword_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if ($_POST['submit_btn'] == "Login"){
		if(empty(trim($_POST["lusername"]))){
			$lusername_err = "Buddy, you have to enter a username.";
		} else{
			$username = trim($_POST["lusername"]);
		}
		if(empty(trim($_POST["lpassword"]))){
			$lpassword_err = "Yo you think you're gonna get in without entering a password??";
		}else {
			$password = trim($_POST['lpassword']);
		}
		if(empty($lusername_err) && empty($lpassword_err)){
			$sql = "SELECT username, password FROM users WHERE username = ?";
			if($stmt = mysqli_prepare($link, $sql)){
				mysqli_stmt_bind_param($stmt, "s", $param_username);
				$param_username = $username;
				if(mysqli_stmt_execute($stmt)){
					mysqli_stmt_store_result($stmt);
					if(mysqli_stmt_num_rows($stmt) == 1){
						mysqli_stmt_bind_result($stmt, $username, $hashed_password);
						if(mysqli_stmt_fetch($stmt)){
							if(password_verify($password, $hashed_password)){
								session_start();
								$_SESSION['lusername'] = $username;
								header("location: /");	
							} else {
								$lpassword_err = "That password ain't right!";
							}
						}
					} else{
						$lusername_err = "No accounts with that name, bro.";
					}
				}
			}
			mysqli_stmt_close($stmt);
		}
		mysqli_close($link);
	}
	elseif ($_POST['submit_btn'] == "Register"){ 
		if(empty(trim($_POST["username"]))){
			$username_err = "Boi what are you trying to do without entering a name?";
		} else{
			$sql = "SELECT id FROM users WHERE username = ?";
	
			if($stmt = mysqli_prepare($link, $sql)){
				mysqli_stmt_bind_param($stmt, "s", $param_username);
	
				$param_username = trim($_POST["username"]);
	
				if(mysqli_stmt_execute($stmt)){
					mysqli_stmt_store_result($stmt);
	
					if(mysqli_stmt_num_rows($stmt) == 1){
						$username_err = "Bruh, that username is taken.";
					} else{
						$username = trim($_POST["username"]);
					}
				}
			}
	
			mysqli_stmt_close($stmt);
		}

		if(empty(trim($_POST["email"]))){
			$email_err = "Hey fella you gotta enter your Goshen email!";
		} elseif (preg_match('/^\w+@goshen\.edu$/i', trim($_POST["email"])) > 0){
			$email = trim($_POST["email"]);
		} else {
			$email_err = "Please use your proper Goshen email my friend!";	
		}

		if(empty(trim($_POST["password"]))){
			$password_err = "I mean you gotta enter a password my man.";
		}elseif(strlen(trim($_POST["password"])) < 6){
			$password_err = "Hey guy, slap more than 5 characters on that thing.";
		} else{
			$password = trim($_POST["password"]);
		}
	
		if(empty(trim($_POST["vpassword"]))){
			$confirm_password_err = 'Bruh you gotta confirm that password.';
		} else{
			$confirm_password = trim($_POST["vpassword"]);
			if($password != $confirm_password){
				$confirm_password_err = "Woah there buddy these password don't match.";
			}
		}

		$description = trim($_POST["description"]);
	
		if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)){
	
			$sql = "INSERT INTO users (username, email, password, description) VALUES (?, ?, ?, ?)";
			if($stmt = mysqli_prepare($link, $sql)){
				mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_email,  $param_password, $param_description);
	
				$param_username = $username;
				$param_email = $email;
				$param_password = password_hash($password, PASSWORD_DEFAULT);
				$param_description = $description;
	
				if(mysqli_stmt_execute($stmt)){
					header('Location: /?user=true');
				}
			}
			mysqli_stmt_close($stmt);
		} else {
			$show = 'block';
		}
		mysqli_close($link);
	}
}

if ($_SESSION['try_count'] > 5){
	$username_err = 'What, do you like suck at creating users or something?';
}
?>


<!DOCTYPE html>
<link rel="stylesheet" type="text/css" href="./styles/login_register.css" />

<html>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="./scripts/login_register.js"></script>

<head>
    <title>Goshen Gotcha | Login/Register</title>
</head>
<div style='display:<?php echo $show; ?>;' class='warning'>There was a problem with registering. Please try again.</div>
<div id="desc_box" class="desc_box no_submit">
		<label style="color:white;font-size:30px;right:0;top:8px;margin-bottom:10px;"for="description">Agent, what's your background story?</label>
		<textarea form='reg' name="description"value="<?php echo $description; ?>" cols="20" rows="4" placeholder="Fun background story..."><?php echo $description;?></textarea>
                <input style="margin-top:40px;"form="reg"type="submit" name="submit_btn" value="Register">
	</div>

    <div class="blocker">
        <div class="blocker_text_container">
            <h1 id='login' class='select'>Login</h1>
            <h1 id='register' class='unselect'>Register</h1>
        </div>
    </div>

    <div style="height:100%;overflow:hidden;"><div id='logreg' class="logreg transform">
        <div class="log">

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <label for="uname">Username</label>
                <input type="text" id="uname" name="lusername" placeholder="Username">
		<span><?php echo $lusername_err;?></span>

                <label for="password">Password</label>
                <input type="password" id="password" name="lpassword" placeholder="Password">
		<span><?php echo $lpassword_err;?></span>

                <input type="submit" name="submit_btn" value="Login">
            </form>

        </div>
        <div class='reg'>

	<form id="reg" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method='post'>
		<div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label style="right:39%;" for="email">Email</label>
		<input type="email" id="email" name="email" placeholder="Email"value="<?php echo $email; ?>">
		<span class="help-block"><?php echo $email_err;?></span>
		</div>

		<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label for="uname">Username</label>
		<input type="text" id="username" name="username" placeholder="Username"value="<?php echo $username; ?>">
		<span class="help-block"><?php echo $username_err;?></span>
		</div>

		<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ""; ?>">
                <label for="password">Password</label>
		<input type="password"id="password"name="password"placeholder="Password"value="<?php echo $password; ?>">
		<span class="help-block"><?php echo $password_err;?></span>
		</div>

		<div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ""; ?>">
                <label style='right:30%;white-space:nowrap;' for="vpassword">Verify Password</label>
		<input type="password" id="vpassword" name="vpassword"placeholder="Verify Password" value="<?php echo $confirm_password; ?>">
		<span class="help-block"><?php echo $confirm_password_err;?></span>
		</div>

		<div id='c_submit' >Register</div>
                <!--<input type="submit" name="submit_btn" value="Register">-->
            </form>

        </div>
    </div>
    <div style='margin-top:-487px;height:999px;' class="blocker"></div></div>
</html>
