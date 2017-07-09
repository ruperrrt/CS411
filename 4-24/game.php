<?php
    session_start();
    include 'dbh.php';
?>

<!DOCTYPE html>

<html>

    <head>

        <link href="https://fonts.googleapis.com/css?family=Raleway:400, 600" rel="stylesheet">

            <link href="form.css" type="text/css" rel="stylesheet">
                </head>

    <body>
        <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
        <script src="main.js"></script>

        <div class="header">

            <div class="container">

                <ul class="nav">


                    
                     
                    <?php
                        if (isset($_SESSION['id'])) {
                        ?>
                    <a class="afterlogin" href='./logout.php'><li>Log out</li></a>
                    <a href="./signup_page.php"><li>Sign up</li></a>
                    <a href="./index.php"><li>Search</li></a>
                    <li>Contact</li>
                    <a class="afterlogin" href='./update_page.php'><li>Profile</li></a>
                    <a class="afterlogin" href='./data_visualization.php'><li>DV</li></a>
                    <a class="afterlogin" href='./data_visualization_2.php'><li>DV2</li></a>
                    <?php
                        }
                        else {
                    ?>
                    <a href ="./login_page.php"><li>Log in</li></a>
                    <a href="./signup_page.php"><li>Sign up</li></a>
                    <a href="./index.php"><li>Search</li></a>
                    <li>Contact</li>
                    <?php
                        }
                    ?>


                </ul>

            </div>

        </div>



                    <img style="float: left;" src = "https://m.popkey.co/ac13dc/7654a.gif">
                    <h1 style="text-align: center; margin-top: 100px; font-size:300%; font-weight: 700;">These are the recent games you may like!</h1>
                    <!--<a class="btn-main" href="#">Get started</a>-->

                    <div style="width: 100%; overflow:auto;">
                            
                        <?php
                            $FTpara = $_POST["FT"];
                            $FPpara = $_POST["FP"];
                            $LOpara = $_POST["LO"];
                            $TPpara = $_POST["3P"];
                            $STpara = $_POST["ST"];
                            $ASpara = $_POST["AS"];
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
                                $score += 1.5 * $FTpara * favteam($usrid,$row['home'],$row['road']);     //the # of favorite teams included in a certain game
                                $score += 1 * $FPpara * favplayer($usrid,$row['home'],$row['road']);    //the # of favorite players included in a certain game
                                $score += 1.25 * $LOpara * location($usrid,$row['home'],$row['road']);   //the # of team in user's hometown included in a certain game
                                $score += 0.3 * $TPpara *(teamstats($row['home'])['3P_percent']+teamstats($row['road'])['3P_percent']);  //add up the 3 point effect
                                $score += 0.45 * $STpara * (teamstats($row['home'])['STL']+teamstats($row['road'])['STL']);  //add up the steal effect
                                $score += 0.35 * $ASpara * (teamstats($row['home'])['AST']+teamstats($row['road'])['AST']);  //add up the assist effect
                                $score += 0.6 * $BLpara * (teamstats($row['home'])['BLK']+teamstats($row['road'])['BLK']);  //add up the block effect
                                $score -= 0.5 * $LGpara * mysql_result(days($row['date']), 0);  //later games are less likely to be recommended
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
                            echo '<table class="hafuman">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>Date</th>';
                            echo '<th>Time</th>';
                            echo '<th>Road</th>';
                            echo '<th>Home</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            while ($row = mysql_fetch_assoc($result)) {
                                echo '<tr>';
                                $count = 1;
                                foreach ($row as $key => $value) {
                                    if($count<=4){
                                        echo "<td>".$value."</td>";
                                    }
                                    $count++;
                                }
                                echo '</tr>';
                            }
                            echo '</tbody>';
                            echo '</table>'
                            ?>


                    </div>






        

    </body>

</html>

