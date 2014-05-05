<?php
/**
 * Created by IntelliJ IDEA.
 * User: ethan
 * Date: 5/5/14
 * Time: 11:22 AM
 */


require_once 'ui/Player.php';
require_once 'ui/Admin.php';

session_start();
		if(isset($_POST['logout'])){

            AuthenticationManager::logOut($_SESSION['username']);
            session_destroy();
            header( 'Location: ../index.php' ) ;
        }
		//probably should improve this
	if(! AuthenticationManager::checkSession()){
        die("You've been a naughty boy");
    }



	?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CodeNinja's Scorebot</title>
    <link href="../scripts/style.css" rel="stylesheet" type="text/css">
    <link rel="icon"
          type="image/png"
          href="../images/tux-matrix.png">

    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

    <script type="text/javascript" src="../scripts/jquery-2.1.0.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="../scripts/tabs.js"></script>
    <style>
        #feedback { font-size: 1.4em; }
        #selectable .ui-selecting { background: #FECA40; }
        #selectable .ui-selected { background: #F39814; color: white; }
        #selectable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
        #selectable li { margin: 3px; padding: 0.4em; font-size: 1.4em; min-height: 18px; }
    </style>
    <script>
        $(function() {
            $( "#selectable" ).selectable({
                selected: function(event,ui){document.getElementById('selflag').value=ui.selected.value;

                    ui.selected.id='selecteditem';
                },
                unselected: function(event, ui){
                    ui.unselected.id='unselecteditem'
                }
            });
        });
        window.onload=function() {

            // get tab container
            var container = document.getElementById("tabContainer");
            // set current tab
            var navitem = container.querySelector(".tabs ul li");
            //store which tab we are on
            var ident = navitem.id.split("_")[1];
            navitem.parentNode.setAttribute("data-current",ident);
            //set current tab with class of activetabheader
            navitem.setAttribute("class","tabActiveHeader");

            //hide two tab contents we don't need
            var pages = container.querySelectorAll(".tabpage");
            for (var i = 1; i < pages.length; i++) {
                pages[i].style.display="none";
            }

            //this adds click event to tabs
            var tabs = container.querySelectorAll(".tabs ul li");
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].onclick=displayPage;
            }





            <?php
                if(isset($_POST['getscores'])){
                    echo "document.getElementById('tabHeader_2').click();";
                }
                if(isset($_POST['servicepassword'])){
                    echo "document.getElementById('tabHeader_5').click();";
                }
                 if(isset($_POST['editflag'])){
                    echo "document.getElementById('tabHeader_1').click();";
                    Admin::updateFlag($_POST['id'],$_POST['name'],$_POST['description'],$_POST['points'],$_POST['value'],$_POST['team']);
                }
                if(isset($_POST['deleteflag'])){
                    Admin::deleteFlag($_POST['id']);
                }
                 if(isset($_POST['createflag'])){
                    Admin::createFlag($_POST['name'],$_POST['description'],$_POST['points'],$_POST['value'],$_POST['team']);
                }
            ?>
        };

    </script>
</head>
<body>
<script type="text/javascript" src="../scripts/Highcharts-3.0.5/js/highcharts.js"></script>
<script type= "text/javascript" src="../scripts/Highcharts-3.0.5/js/modules/exporting.js"></script>

<div id="pic" style="text-align:center">
    <img align="middle" src="../images/nix.png"/>
</div>
<div id="wrapper">
    <div id="tabContainer">
        <div class="tabs">
            <ul>
                <li id="tabHeader_1"></li>
                <li id="tabHeader_2">Flag Management</li>
                <li id="tabHeader_5"></li>
                <li id="tabHeader_4"></li>
                <li id="tabHeader_3">Sign Out</li>
            </ul>
        </div>
        <div class="tabscontent">
            <div class="tabpage" id="tabpage_1" >

            </div>
            <div class="tabpage" id="tabpage_2">
                <h2>Flags</h2>
                <p>
                <table class='tftable' border='1'>
                    <caption><h2>Flags</h2></caption>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Points</th>
                        <th>Team</th>
                        <th>Value</th>
                        <th>Action</th>

                    </tr>
                    <?php

                        $select = "<select name='team'>";
                        $result = Admin::getTeams();
                        while($row=mysql_fetch_assoc($result)){
                            $select = $select."<option value='{$row['id']}'>{$row['name']}</option> ";
                        }
                         $select=$select."</select>";
                         $result = Admin::getFlags();
                         while($row = mysql_fetch_assoc($result))
                         {


                           /// $selectnew=preg_replace("/>".$row['team'],"selected".$row['team'],$select);
                            $selectnew= preg_replace("/>{$row['team']}/", "selected>{$row['team']}", $select);
                            $tr = "

                             <tr>
                             <form action='AdminView.php' method='post'>
                             <input name='id' type='hidden' value='{$row['id']}'/>
                            <td><input name='name' type='text' value='{$row['name']}'/></td>
                            <td><input name='description' type='text' value='{$row['description']}'/></td>
                            <td><input name='points' type='text' value='{$row['points']}'/></td>
                            <td>$selectnew</td>
                            <td><input name='value' type='text' value='{$row['value']}'/></td>
                            <td><input name='editflag' type='submit' value='Edit'/><input name='deleteflag' type='submit' value='Delete'/></td>
                            </form>
                            </tr>


                            ";
                             echo $tr;

                        }


                    ?>


                </table>
                <table  class='tftable' border='1'>
                    <caption><h2>New Flag</h2></caption>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Points</th>
                        <th>Team</th>
                        <th>Value</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $tr = "<tr>

 <form action='AdminView.php' method='post'>


                            <td><input name='name' type='text' value='name'/></td>
                            <td><input name='description' type='text' value='description'/></td>
                            <td><input name='points' type='text' value='points'/></td>
                            <td>$select</td>
                            <td><input name='value' type='text' value='answer'/></td>
                            <td><input name='createflag' type='submit' value='Create'/></td>

                            </form>
                           </tr>


                            ";
                    echo $tr;
                    ?>
                </table>
                </p>

                <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto">


                    <?php
                    if(isset($_POST['getscores'])){
                        $scoresJSON = Player::getFlagScores();
                        echo "<script>getScores('$scoresJSON'); </script>";

                    }
                    ?>





                </div>
            </div>


            <div class="tabpage" id="tabpage_5"style="clear:left;">

            </div>



            <div class="tabpage" id="tabpage_3">
                <h2>Sign Out</h2>
                <p>

                <form action="PlayerView.php" method="POST">
                    <input type="submit" name= "logout" value="Sign Out" />

                </form>

                </p>
            </div>

            <div class="tabpage" id="tabpage_4">

            </div>
        </div>
    </div>

</body>
</html>
