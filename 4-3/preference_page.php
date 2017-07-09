<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="https://fonts.googleapis.com/css?family=Raleway:400, 600" rel="stylesheet">
            <link href="style2.css" type="text/css" rel="stylesheet">
                </head>
    <body>
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

        <div class="jumbotron">
            <div class="container">
                <div class="main">
                    <h1>Choose Your Favorite</h1>
                    <!--<a class="btn-main" href="#">Get started</a>-->
                    <div class = "search">
                        <form action="preference.php" method="post">
                            
                            <!--<h3> Title:</h3>-->
                            <input type="text" name="fteam1" placeholder="Enter your favorite team 1:" class="searcher"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="text" name="fteam2" placeholder="Enter your favorite team 2:" class="searcher"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="text" name="fteam3" placeholder="Enter your favorite team 3:" class="searcher">
                                <br><br><br>
                            <input type="text" name="fplayer1" placeholder="Enter your favorite player 1:" class="searcher">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="text" name="fplayer2" placeholder="Enter your favorite player 2:" class="searcher">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="text" name="fplayer3" placeholder="Enter your favorite player 3:" class="searcher">
                                            <br>
                            
                                <!--<h3>Search by user:</h3>
                                 <input name="searchUser" type="radio" value="true" checked="checked" />Yes
                                 <input name="searchUser" type="radio" value="false" checked="checked"/>No<br>
                                 
                                 
                                 <h3>Search by genre:</h3>
                                 <input name="useGenre" type="radio" value="true"/>Yes
                                 <input name="useGenre" type="radio" value="false" checked="checked" />No<br>-->
                                
                                <br><br>
                                <input type="submit" value="Submit" class="btn-submit">
                                    
                                    
                                    
                                    <!--    <input name="InFavorites" type="radio" value="1" />Search Favorited Video<br>-->
                                    <br>
                                    </form>
                        
                    </div>
                </div>
            </div>
        </div>
        
        
        
        
        
        <div class="supporting">
            <div class="container">
                
                <div class="col">
                    <img src="https://img.clipartfest.com/fe6512cbe424f39449e93e935992ddb9_basketball-black-and-white-basketball-black-and-white-clipart_843-688.jpeg">
                        <h2>NBA Official Site</h2>
                        <p>Make your projects look great and interact beautifully.</p>
                        <a class="btn-default" href="http://www.nba.com" target = "_blank">Learn more</a>
                        </div>
                
                <div class="col">
                    <img src="https://image.freepik.com/free-icon/quarter-pie-piece-in-circular-graph_318-28979.jpg">
                        <h2>Statistic</h2>
                        <p>Use modern tools to turn your design into a web site</p>
                        <a class="btn-default" href="http://sports.yahoo.com/nba/stats/" target = "_blank">Learn more</a>
                        </div>
                
                <div class="col">
                    <img src="https://pbs.twimg.com/profile_images/462244617321062400/ZU9gWQ4j_400x400.png" >
                        <h2>Shaqtin' a Fool</h2>
                        <p>Use modern tools to turn your design into a web site</p>
                        <a class="btn-default" href="http://www.nba.com/fool" target = "_blank">Learn more</a>
                        </div>
                
            </div>
            <div class="clearfix"></div>
        </div>
        
        <div class="footer">
            <div class="container">
                <p>&copy; NBA Game On 2017</p>
            </div>
        </div>
        
    </body>
</html>
