<?php
require_once 'database/QueryManager.php';
require_once 'logging/LogManager.php';
	class AuthenticationModel {
	public static function createSession($ip,$username,$cookie){
			$ip=mysql_real_escape_string($ip);
			$username=mysql_real_escape_string($username);
			$cookie=mysql_real_escape_string($cookie);
			$date = new DateTime();
			$now = $date->getTimestamp();
		$querystr = "
		INSERT INTO	sessions (remote_ip,init_time,username,cookie,last_active)
			VALUES('$ip',$now,'$username','$cookie',$now)
		";
		LogManager::log(LogAction::QUERY, $username, null, $querystr);
		if(QueryManager::query($querystr)){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	public static function registerUser($username,$password,$team){
		$username=mysql_real_escape_string($username);
		$password=mysql_real_escape_string($password);
		$team=mysql_real_escape_string($team);
		
		
		$querystr="
		INSERT INTO users (username,password,team) VALUES('$username','$password','$team')
		";
		LogManager::log(LogAction::QUERY, $username, null, $querystr);
		if(QueryManager::query($querystr)){
			return TRUE;
		}
		else {
			{return FALSE;}
		}
		
	}
	public static function destroySession($cookie, $ip,$username){
		$cookie=mysql_real_escape_string($cookie);
		$ip=mysql_real_escape_string($ip);
		$username=mysql_real_escape_string($username);
		$querystr = "
		DELETE FROM sessions
			WHERE username='$username'
		";
		LogManager::log(LogAction::QUERY, $username, null, $querystr);
		QueryManager::query($querystr);
	}
	public static function getSession($cookie,$ip){
		$cookie=mysql_real_escape_string($cookie);
		$ip=mysql_real_escape_string($ip);
		$querystr = "
		SELECT * FROM sessions WHERE cookie='$cookie' AND remote_ip='$ip'
		";
		LogManager::log(LogAction::QUERY, isset($_SESSION['username']) ? mysql_real_escape_string($_SESSION['username']) : 'unauthenticated', null, $querystr);
		$result = QueryManager::query($querystr);
		return $result;
	}
	public static function updateSession($cookie,$ip){
		$ip=mysql_real_escape_string($ip);			
		$cookie=mysql_real_escape_string($cookie);
		 
		$now = time();
		
		$querystr ="
		UPDATE sessions set last_active=$now WHERE cookie='$cookie' 
		";
		LogManager::log(LogAction::QUERY, isset($_SESSION['username']) ? mysql_real_escape_string($_SESSION['username']) : 'unauthenticated', null, $querystr);
		QueryManager::query($querystr);
	}
	public static function getUser($username){
		$username = mysql_real_escape_string($username);
		$querystr="
		SELECT * FROM users WHERE username='$username'
		";
		LogManager::log(LogAction::QUERY,$username, null, $querystr);
		$result = QueryManager::query($querystr);
		return $result;
		
	}
}


?>