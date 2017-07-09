<?php
	session_start();
	include 'dbh.php';
	?>


<?php
    $FTpara = $_POST["FT"];
    $FPpara = $_POST["FP"];
    $LOpara = $_POST["LO"];
    $STpara = $_POST["ST"];
    $BLpara = $_POST["BL"];
    $LGpara = $_POST["LG"];

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
		$sql = "SELECT DATEDIFF (STR_TO_DATE('$date','%d-%M-%Y'),CURDATE())";
		$result =  mysql_query($sql) or die(mysql_error());
		return $result;
	}
	function scale($a,$b){
        return $a-$b;
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

	//4/24/2017
	$favteam1s = array();
	$favplayer1s = array();
	$location1s = array();
	$day1s = array();
	$block1s = array();
	$steal1s = array();
	while ($row1 = mysql_fetch_assoc($result)) {
		$favteam1 = favteam($usrid,$row1['home'],$row1['road']); 
		$favteam1s[$row1['gid']] = $favteam1;
		$favplayer1 = favplayer($usrid,$row1['home'],$row1['road']);
		$favplayer1s[$row1['gid']] = $favplayer1;
		$location1 = location($usrid,$row1['home'],$row1['road']);
		$location1s[$row1['gid']] = $location1;
		$day1 = mysql_result(days($row1['date']), 0);
		$day1s[$row1['gid']] = $day1;
		$block1 = teamstats($row1['home'])['BLK']+teamstats($row1['road'])['BLK'];
		$block1s[$row1['gid']] = $block1;
		$steal1 = teamstats($row1['home'])['STL']+teamstats($row1['road'])['STL'];
		$steal1s[$row1['gid']] =$steal1;
	}
	//foreach ($favteam1s as $gid => $favteam1) {
	//	echo $gid." => ".$favteam1."<br>";
	//}	
		$maxteam = max($favteam1s);    $minteam = min($favteam1s);
		$maxplayer = max($favplayer1s);  $minplayer = min($favplayer1s);
	    $maxlocation = max($location1s);   $minlocation = min($location1s);
	    $maxday = max($day1s);   $minday = min($day1s);
	    $maxblock = max($block1s);  $minblock = min($block1s);
	    $maxsteal = max($steal1s);  $minsteal = min($steal1s);  
	                        

	$result =  mysql_query($sql3) or die(mysql_error());
	while ($row = mysql_fetch_assoc($result)) {
        $score = 0;
        $score += $FTpara * favteam($usrid,$row['home'],$row['road'])/scale($maxteam,$minteam);     //the # of favorite teams included in a certain game
        $score += $FPpara * favplayer($usrid,$row['home'],$row['road'])/scale($maxplayer,$minplayer);    //the # of favorite players included in a certain game
        $score += $LOpara * location($usrid,$row['home'],$row['road'])/scale($maxlocation,$minlocation);   //the # of team in user's hometown included in a certain game
        $score += $STpara * (teamstats($row['home'])['STL']+teamstats($row['road'])['STL'])/scale($maxsteal,$minsteal);  //add up the steal effect
        $score += $BLpara * (teamstats($row['home'])['BLK']+teamstats($row['road'])['BLK'])/scale($maxblock,$minblock);  //add up the block effect
        $score -= $LGpara * mysql_result(days($row['date']), 0)/scale($maxday,$minday);  //later games are less likely to be recommended
		//echo mysql_result(days($row['date']), 0)."<br><br>";
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
$_SESSION['FT'] = $_POST["FT"];
$_SESSION['FT'] = $_POST["FT"];
$_SESSION['FT'] = $_POST["FT"];
$_SESSION['FT'] = $_POST["FT"];
$_SESSION['FT'] = $_POST["FT"];
	?>