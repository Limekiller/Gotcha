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
<link rel="stylesheet" type="text/css" href="./styles/rules.css" />
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
	<title>Goshen Gotcha | Spies</title>
</head>
<body>
<div class="wrapper">
<div class="header">RULES</div>
<div class="para">
<p>
The goal of Gotcha is to remain in the game until all other players have gotten out or you have been gotten out. To do so, you will be assigned a target. If you succeed in getting your target out, you both must submit a report (choose "Submit a Report" from the main menu), and you will then receive your next target.
</p>
<p>
SIGN UP BEFORE APRIL 4 AT NOON
</p>
<h2>To Start</h2>
<p>All players will give themselves a good nickname, but make it unique. Your nickname shouldn't give away your identity. Nicknames must be school appropriate! Also come up with a fun, background story for yourself. Remember that anyone can read your background story, so make sure it doesn't give who you are away. Secrecy is a useful tool in this game!</p>
<h2>The Rules</h2>
<ul>
    <li>An attack must be done so that no other people playing see you.</li>
<li>If you are attempting to attack someone, then the person you are trying to attack has the right to get you out in that moment too. Plain, simple self-defense. </li>
<li>You can't get people out just anywhere. Places people can't be gotten are:</li> 
<ol>
	<li>In the target's room when the door is fully closed and the target is sleeping. If the door is open enough so that one can walk in, you can make the attack (even if they are sleeping). If you are invited into the room by ANYONE in the room, then you can walk in and make the attack. For instance, you can walk into the room, shut the door, and make your attack. You CANNOT open a closed door (locked or unlocked) unless you are invited in...this is breaking the rules. Any other time that the victim is awake and you are legally in the person's room, however, you can make the attack. The "ROOM rules" above do not apply in the case of self-defense.</li>
	<li>While at work</li>
	<li>In a classroom or a professor's office. You can not block people from getting to their class -- standing right outside the door, for example.</li>
	<li>In the bathroom</li>
	<li>At any official school event. (Convo, chapel, concerts, plays, guest speakers; use your best judgment and don't be disrespectful)</li>
	<li>Inside the Good Library</li>
	<li>You cannot attack the driver of a moving vehicle, and you cannot kill the driver of a motor vehicle anytime the engine is on, even if said vehicle is stationary. Other passengers are fair game.</li>
	<li>While someone is at the gym, or during a game of something like Ultimate.</li>
	<li>Anywhere where weaponry might damage the surroundings -- use good judgment</li>
</ol>
<li>Once an attack is made, ALL parties involved need to submit reports. If the reports corroborate, the attacker will be assigned a new target.</li>
</ul>
<h2>Once You Are Attacked</h2>
<p>Please have respect for the other Players and the integrity of the game by not talking about the game. Don't tell anyone who got you or who your target was. This provides an unfair advantage to certain players. This game is made fun by the secrecy and suspense. Please do not ruin the game for others! </p>
<h2>How to Get People Out</h2>
<p>You can throw a rolled up sock at the target. It must be a solid hit to count; hitting just a person's hair does not count as a solid hit. 

(When trying to avoid sock, please be careful. But also keep in mind that if you are clearly attacked, you can use self-defense. Sometimes running is the best strategy.)</p>
<h4> If you have any sorts of questions about the rules, please contact the director. You should know proper conduct. Play fair, no cheating. If you botch a kill, don't lie about it. (If there is any disagreement, and there better not be, The director is the arbiter and their decision is final). Don't inconvenience the target or others with your antics. Don't hurt yourself. This is a fun sneaky game.</h4>
</div>
</div>
<div class="footer">
	<p style="display:inline;float:left;margin:13px;"><a href="/">Home</a>Created by Bryce Yoder, 2018</p>
	<a href='./logout.php' class='logout' style='float:right;display:<?php if($login == true){echo 'inline';}else{echo 'none';}?>'>Logout</a>
</div>
</div>
</body>
</html>
