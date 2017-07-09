<?php
	session_start();
	include 'dbh.php';
	?>


<?php
	function favteam($usrid,$home,$road){
		$sql1 = "SELECT * FROM `Favorite_team` WHERE Users_ID=$usrid AND Teams_Name='$road';";
		$sql2 = "SELECT *  FROM `Favorite_team` WHERE Users_ID=$usrid AND Teams_Name='$home';";
		$result1 =  mysql_query($sql1) or die(mysql_error());
		$result2 =  mysql_query($sql2) or die(mysql_error());
		return mysql_num_rows($result1)+mysql_num_rows($result2);
	}
	function favplayer($usrid,$home,$road){
		$sql1 = "SELECT *  FROM Favorite_player  WHERE Users_ID=$usrid AND Players_Name IN (SELECT Name FROM player, Teams_Statistics WHERE short_name=Teamname AND Team_name = '$road') ";
		$sql2 = "SELECT *  FROM Favorite_player  WHERE Users_ID=$usrid AND Players_Name IN (SELECT Name FROM player, Teams_Statistics WHERE short_name=Teamname AND Team_name = '$home') ";
		$result1 =  mysql_query($sql1) or die(mysql_error());
		$result2 =  mysql_query($sql2) or die(mysql_error());
		return mysql_num_rows($result1)+mysql_num_rows($result2);
	}
	function location($usrid,$home,$road){
		$sql = "(SELECT * FROM Teams_Statistics s, user WHERE s.Location = user.Location AND id = $usrid AND Team_name = '$road') UNION (SELECT * FROM Teams_Statistics s, user WHERE s.Location = user.Location AND id = $usrid AND Team_name = '$home')";
		$result =  mysql_query($sql) or die(mysql_error());
		return mysql_num_rows($result);
	}	
	function teamstats($team){
		$sql = "SELECT * FROM Teams_Statistics WHERE Team_name = '$team'";
		$result =  mysql_query($sql) or die(mysql_error());
		return mysql_fetch_assoc($result);
	}
	
	function days($date){
		$sql = "SELECT DATEDIFF (STR_TO_DATE('$date','%d-%M-%Y'),'2017-4-1')";
		$result =  mysql_query($sql) or die(mysql_error());
		return $result;
	}
	mysql_connect("localhost","staysimple_dalao1","Dalaodalao1");
	mysql_select_db("staysimple_sometimesnaive_my_DB");
	$usrid = $_SESSION['id'];
	//$usrid = 104;
	if($usrid!=''){
		//echo $usrid . "<br><br>";
	} 
	else{
		echo "Error! No username!<br><br>";
	}


	$sql1 = "SELECT * FROM `user` WHERE id = $usrid;";
	$result =  mysql_query($sql1) or die(mysql_error());	
	//test sql1


	$sql2 = "SELECT * FROM `game` ";
	$result =  mysql_query($sql2) or die(mysql_error());
	//test sql2

	$scores = array();
	$sql3 = "SELECT * FROM `game4`;";
	$result =  mysql_query($sql3) or die(mysql_error());
	//test sql3
	while ($row = mysql_fetch_assoc($result)) {
		$score = 0;
		$score += 6 * favteam($usrid,$row['home'],$row['road']);     //the # of favorite teams included in a certain game
		$score += 3 * favplayer($usrid,$row['home'],$row['road']);    //the # of favorite players included in a certain game
		$score += 0.75 * location($usrid,$row['home'],$row['road']);   //the # of team in user's hometown included in a certain game
		$score += 0.03 * (teamstats($row['home'])['3P_percent']+teamstats($row['road'])['3P_percent']);  //add up the 3 point effect
		$score += 0.045 * (teamstats($row['home'])['STL']+teamstats($row['road'])['STL']);  //add up the steal effect
		$score += 0.035 * (teamstats($row['home'])['AST']+teamstats($row['road'])['AST']);  //add up the assist effect
		$score += 0.06 * (teamstats($row['home'])['BLK']+teamstats($row['road'])['BLK']);  //add up the block effect
		$score -= 0.05 * mysql_result(days($row['date']), 0);  //later games are less likely to be recommended  
		//echo mysql_result(days($row['dat e']), 0)."<br><br>";
		$scores[$row['gid']] = $score;
	}
		//test array
	//foreach ($scores as $gid => $score) {
	//	echo $gid." => ".$score."<br>";
	//}
		arsort($scores);
	//foreach ($scores as $gid => $score) {
	//	echo $gid." => ".$score."<br>";
	//}
	$rec1 = array_slice($scores,0,1,ture);
	$rec2 = array_slice($scores,1,1,ture);
	$rec3 = array_slice($scores,2,1,ture);
	$rec4 = array_slice($scores,3,1,ture);
	$rec5 = array_slice($scores,4,1,ture);
	//print_r($rec1);
	$r1=key($rec1);
	$r2=key($rec2);
	$r3=key($rec3);
	$r4=key($rec4);
	$r5=key($rec5);
	$sql4 = "SELECT * FROM `game4` WHERE gid = '$r1' OR gid = '$r2'OR gid = '$r3'OR gid = '$r4'OR gid = '$r5';";
	$result =  mysql_query($sql4) or die(mysql_error());	
	while ($row = mysql_fetch_assoc($result)) {
		foreach ($row as $key => $value) {
		echo $key." => ".$value."<br>";
	}
	}	

	?>