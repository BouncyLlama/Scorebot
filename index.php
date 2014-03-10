<?php
require_once 'authentication/AuthenticationManager.php';
	session_start(); 
	if(AuthenticationManager::checkSession() == TRUE){
		header( 'Location: ui/PlayerView.php' ) ;
	}
	else{
		if(isset($_POST['login'])){
			
			if(AuthenticationManager::logIn($_POST['username'], $_POST['password'])){
					header( 'Location: ui/PlayerView.php' ) ;
			}
			else{
		
			}
		}
		else if(isset($_POST['register'])){
			if(AuthenticationManager::register($_POST['username'], $_POST['password'], $_POST['team']))
			{
				if(AuthenticationManager::logIn($_POST['username'], $_POST['password'])){
				
			}
			else{
			header( 'Location: http://google.com' ) ;
			}
			}
			else {
				
			}
		}
		
		
		?>
		
		
		
		<html>
<head>
<meta charset="utf-8">
<title>CodeNinja's Scorebot</title>
<link href="scripts/style.css" rel="stylesheet" type="text/css">
<link rel="icon" 
      type="image/png" 
      href="tux-matrix.png">
<script src="scripts/tabs.js"></script>
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
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
    selected: function(event,ui){document.getElementById('selflag').innerText=ui.selected.value;
  },
    unselected: function(event, ui){
        //ui.unselected.id
    }
});
  });

  
  </script>
</head>
<body>
<div id="pic" style="text-align:center">
<img align="middle" src="images/nix.png"/>
</div>
<div id="wrapper">
  <div id="tabContainer">
    <div class="tabs">
      <ul>

        <li id="tabHeader_3">Sign In</li>
         <li id="tabHeader_2">Register</li>
      </ul>
    </div>
    <div class="tabscontent">
     
      <div class="tabpage" id="tabpage_3">
        <h2>Sign In</h2>
        <p>
		<form id="loginForm" action="index.php" method="POST">
			<input type='hidden' name='login'/>
			<label>Email</label>
          <input id="username" name="username"></input>
          <label>Password</label>
          <input id="password" name="password"></input>
          <input type="submit" name="Log In" value="Log In"/>
     
			
		</form>
   </p>
      </div>

           <div class="tabpage" id="tabpage_2">
        <h2>Register</h2>
        <p>
         <form id="registerForm" action="index.php" method="POST">
         	<input type="hidden" name="register"/>
        <fieldset>
        <label class="labels">Email: </label>
          <input id="username" name="username"></input><br/>
          <label class="labels">Password: </label>
          <input id="password" name="password"></input><br/>

      
          <label class="labels"> Team/School Name: </label>
          <input id="team" name= "team"></input><br/>
          </fieldset>
          
          <input type='submit' value="Register" />
          </form>

        </p>
      </div>

    </div>
  </div>

</body>
</html>
		
		
		
		
		
		
		
		
		
		
		
		
	
	<?php
	}
	 
?>