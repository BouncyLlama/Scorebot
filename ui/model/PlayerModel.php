<?php
require_once 'authentication/AuthenticationManager.php';
require_once 'logging/LogManager.php';
date_default_timezone_set('America/New_York');
class PlayerModel {
	
	public static function getFlags(){
		if(!AuthenticationManager::checkSession()){
			return NULL;
		}
		$querystr="
		SELECT * FROM flags
		";
		LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
		$result = QueryManager::query($querystr);
		return $result;
	}
	public static function getUnsolvedFlags(){
		$username=mysql_real_escape_string($_SESSION['username']);
		$team = mysql_real_escape_string($_SESSION['team']);
		$querystr ="";
		if(!Config::$IS_TEAM_EXERCISE)
		{
			$querystr="
			SELECT * FROM flags AS f 
				LEFT JOIN flagsubmissions AS fs 
				ON f.id != fs.flag_id AND fs.correct=1 
					WHERE fs.username=$username
					AND fs.correct IS NULL
			";
		}

		else {
			
		
			$querystr="
			
			SELECT f.* FROM users AS u 
	JOIN flagsubmissions AS fs 
	ON u.username= fs.username AND u.team = '$team' AND fs.correct=1
		RIGHT JOIN flags AS f ON fs.flag_id = f.id
		WHERE correct is NULL
			
			";
		}
		LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
		return QueryManager::query($querystr);
	}
	public static function submitFlag($id, $submission,$value){
				if(!AuthenticationManager::checkSession()){
			return NULL;
		}
		$id=mysql_real_escape_string($id);
		$value=mysql_real_escape_string($value);
		$submission=mysql_real_escape_string($submission);
		$username=mysql_real_escape_string($_SESSION['username']);
		$time = time();
		$querystr = "
		INSERT INTO flagsubmissions (flag_id,submission,value,username,correct,timestamp) VALUES('$id','$submission','$value','$username', IF('$submission'='$value',1,0),'$time')
		";
		LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
		QueryManager::query($querystr);
		if(strcmp($value,$submission)==0){
			return TRUE;
		}
		else{
			return FALSE;
		}
		
	}
	public static function getFlag($id){
				if(!AuthenticationManager::checkSession()){
			return NULL;
		}
		$id=mysql_real_escape_string($id);
		$querystr="
		SELECT * FROM flags WHERE id='$id'
		";
		LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
		$result =  QueryManager::query($querystr);
		return $result;
	}
	
	public static function getFlagScores(){
		
		 $querystr="";
		if(Config::$IS_TEAM_EXERCISE){
			 
			 $querystr="
			 SELECT f.points,fs.username,u.team,fs.timestamp FROM flagsubmissions AS fs
			 	JOIN flags AS f 
			 	ON f.id=fs.flag_id and fs.correct=1
			 		JOIN users as u
			 		ON u.username = fs.username
			 			ORDER BY fs.timestamp
			 ";
			
		}
		else
		{
	/** TODO individual exercise **/
		}
		LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
		$result=QueryManager::query($querystr);
		$arr = array();
		$sums = array();
		while($row=mysql_fetch_assoc($result)){
			if(!array_key_exists($row['team'], $sums)){
				//array_push($sums,array($row['team']=>$row['points']));
				$sums[$row['team']] = $row['points'];	
			}
			else{
				$sums[$row['team']] += $row['points'];
					
			}
			array_push($arr,array('time'=>$row['timestamp'],'user'=>$row['team'],'points'=>$sums[$row['team']]));
			foreach($sums as $key => $value){
					
				if(strcmp($key,$row['team'])!=0){
						array_push($arr,array('time'=>$row['timestamp'],'user'=>$key,'points'=>$value));
				}
			}
		}
		
		return $arr;
	}
	
	public static function getFiles(){
		$querystr="
		SELECT * FROM files
		";
		LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
		return QueryManager::query($querystr);
	}
}




?>