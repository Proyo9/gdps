<?php
error_reporting(0);
include "../../incl/lib/connection.php";
require "../../incl/lib/generatePass.php";
require_once "../../incl/lib/exploitPatch.php";
require_once "../../incl/lib/mainLib.php";
$gs = new mainLib();
if(!empty($_POST["userName"]) AND !empty($_POST["password"])){
	$userName = ExploitPatch::remove($_POST["userName"]);
	$password = ExploitPatch::remove($_POST["password"]);
	$pass = GeneratePass::isValidUsrname($userName, $password);
	if ($pass == 1) {
		$query = $db->prepare("SELECT accountID FROM accounts WHERE userName=:userName");	
		$query->execute([':userName' => $userName]);
		$accountID = $query->fetchColumn();
		if($query->rowCount()==0){
			echo "Invalid account/password. <a href='suggestList.php'>Try again.</a>";
		}else if($gs->checkPermission($accountID, "toolSuggestlist")){
			$accountID = $query->fetchColumn();
			$query = $db->prepare("SELECT suggestBy,suggestLevelId,suggestDifficulty,suggestStars,suggestFeatured,suggestAuto,suggestDemon,timestamp FROM suggest ORDER BY timestamp DESC");
			$query->execute();
			$result = $query->fetchAll();
			echo '<div class="center"><h1>Sent Levels</h1><br><table border="0"><tr><th>Time</th><th>Moderator</th><th>Level</th><th>Difficulty</th><th>Stars</th><th>Feature</th></tr></div>';
			echo '<a href="https://sql2.7m.pl/sql.php?server=1&db=proyo9_pyps20srv94&table=suggest&pos=0">Manage</a>';
		foreach($result as &$sugg){
			echo "<tr><td>".date("d/m/Y G:i", $sugg["timestamp"])."</td><td>".$gs->getAccountName($sugg["suggestBy"])."(".$sugg["suggestBy"].")</td><td>".htmlspecialchars($sugg["suggestLevelId"],ENT_QUOTES)."</td><td>".htmlspecialchars($gs->getDifficulty($sugg["suggestDifficulty"],$sugg["suggestAuto"],$sugg["suggestDemon"]), ENT_QUOTES)."</td><td>".htmlspecialchars($sugg["suggestStars"],ENT_QUOTES)."</td><td>".htmlspecialchars($sugg["suggestFeatured"],ENT_QUOTES)."</td></tr>";
		}
			echo "</table>";
		}else{
			echo "<h1>You don't have permissions to view content on this page.<h1>\n";
		}
	}else{
		echo "<h1>Invalid account/password.<h1>";
	}
}else{
	echo '<h1>Sent Levels</h1><br><form action="suggestList.php" method="post"><input type="text" name="userName" placeholder="Username">
		<br><input type="password" name="password" placeholder="Password"><br><input type="submit" value="View Sent Levels"></form>';
}
?>
<style>
input {
	margin: 4px;
	width: 200px;
	height: 25px;
	border: solid;
	font-family: Arial;
	text-align: center;
    margin-left: 0 auto; 
    display: inline-block;
  }
div {
  height: 700px;
  width: auto;
  text-align: center;
}
form {
    margin: 0 auto; 
    width: auto;
    height: 700px;
}
h1 {
	color: black;
	font-size: 100px;
	font-family: Arial;
	margin: 0px;
}
table {
	height: 25px;
	font-family: Arial;
	text-align: center;
    display: inline-block;
    border-spacing: 0;
    width: auto;
    border: 0;
}
center {
    margin-left: auto;
    margin-right: auto;
}
tr:nth-child(even) {
  background-color: #D3D3D3;
}
th, td {
  text-align: center;
  padding: 16px;
}
a {
  position: relative;
  display: inline-block;
  padding: 10px 30px;
  text-decoration: none;
  color: white;
  background: #262c37;
  letter-spacing: 2px;
  font-size: 16px;
  transition: 0.5s;
  font-family: Arial;
}
a:hover {
  position: relative;
  display: inline-block;
  padding: 10px 30px;
  text-decoration: none;
  color: white;
  background: gray;
  letter-spacing: 2px;
  font-size: 16px;
  transition: 0.5s;
  font-family: Arial;
}
</style>
