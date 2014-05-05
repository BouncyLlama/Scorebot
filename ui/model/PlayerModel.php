<?php
require_once 'authentication/AuthenticationManager.php';
require_once 'logging/LogManager.php';
date_default_timezone_set('America/New_York');

class PlayerModel {
		
		public static function getLatestServiceCheck($id){
		QueryManager::escape($id);
			$querystr = "
			SELECT sc.available,sc.intact,s.id, sc.timestamp 
			FROM servicechecks AS sc 
				JOIN services AS s ON sc.service_id = s.id 
					JOIN assets as a ON s.asset_id = a.id 
						WHERE s.id = '$id' 
						ORDER BY timestamp DESC
			
			";
			LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
			$result = QueryManager::query($querystr);
			if(!$result){
				return FALSE;
			}
			return mysql_fetch_assoc($result);
		}
	public static function setServicePassword($id, $password) {
		QueryManager::escape($id);
		QueryManager::escape($password);
		$querystr = "
		UPDATE services SET password='$password' WHERE id='$id'
		";
		QueryManager::query($querystr);
		LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
	}

	public static function getService($id) {
		QueryManager::escape($id);
		$querystr = "
			SELECT s.*,a.ip,a.name AS asset_name, t.name AS team
			FROM services AS s 
				JOIN assets AS a 
				ON s.asset_id = a.id
				 JOIN teams AS t
				  ON a.team=t.id
					WHERE s.id=$id
			";
		LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
		return Querymanager::query($querystr);
	}

	public static function getAssets($team) {
		QueryManager::escape($team);
		$querystr = "
		SELECT s.*,a.ip,a.name AS asset_name,t.name AS team
		FROM services AS s 
			JOIN assets AS a 
			ON s.asset_id = a.id
			  JOIN teams AS t
			  ON t.id=a.team
				WHERE team='$team' ORDER BY asset_name,s.id
		
		";
		$result = QueryManager::query($querystr);
		LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
		return $result;
	}

	/**
	 * Get all flags
	 */
	public static function getFlags() {
		if (!AuthenticationManager::checkSession()) {
			return NULL;
		}
		$querystr = "
		SELECT * FROM flags
		";
		LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
		$result = QueryManager::query($querystr);
		return $result;
	}

	/**
	 * Get flags your team has not solved
	 */
	public static function getUnsolvedFlags() {
		$username = mysql_real_escape_string($_SESSION['username']);
		$team = mysql_real_escape_string($_SESSION['team']);
		$querystr = "";
		if (!Config::$IS_TEAM_EXERCISE) {

			/**UNTESTED**/
			$querystr = "
			SELECT * FROM flags AS f 
				LEFT JOIN flagsubmissions AS fs 
				ON f.id != fs.flag_id AND fs.correct=1 
					WHERE fs.username=$username
					AND fs.correct IS NULL
			";
		} else {

			$querystr = "
			
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

	/**
	 * Submit flag
	 * Currently the Player class passes in the correct value as well as the submission
	 * Maybe change that later
	 */
	public static function submitFlag($id, $submission, $value) {
		if (!AuthenticationManager::checkSession()) {
			return NULL;
		}
		$id = mysql_real_escape_string($id);
		$value = mysql_real_escape_string($value);
		$submission = mysql_real_escape_string($submission);
		$username = mysql_real_escape_string($_SESSION['username']);
		$time = time();
		$querystr = "
		INSERT INTO flagsubmissions (flag_id,submission,value,username,correct,timestamp) VALUES('$id','$submission','$value','$username', IF('$submission'='$value',1,0),'$time')
		";
		LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
        LogManager::log(LogAction::FLAGSUBMIT,$username,$submission,$querystr);
		QueryManager::query($querystr);
		if (strcmp($value, $submission) == 0) {
			return TRUE;
		} else {
			return FALSE;
		}

	}

	/**
	 * Get flag by id
	 */

	public static function getFlag($id) {
		if (!AuthenticationManager::checkSession()) {
			return NULL;
		}
		$id = mysql_real_escape_string($id);
		$querystr = "
		SELECT * FROM flags WHERE id='$id'
		";
		LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
		$result = QueryManager::query($querystr);
		return $result;
	}

	/**
	 * Get scores for flag submisisons only
	 */
	public static function getFlagScores() {

		$querystr = "";
		if (Config::$IS_TEAM_EXERCISE) {

			$querystr = "
			 SELECT f.points,fs.username,t.name AS team,fs.timestamp FROM flagsubmissions AS fs
			 	JOIN flags AS f 
			 	ON f.id=fs.flag_id and fs.correct=1
			 		JOIN users as u
			 		ON u.username = fs.username
			 		  JOIN teams as t
			 		  ON u.team = t.id
			 			ORDER BY fs.timestamp
			 ";

		} else {
			/** TODO individual exercise **/
		}
		LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
		$result = QueryManager::query($querystr);
		$arr = array();
		$sums = array();
		while ($row = mysql_fetch_assoc($result)) {
			if (!array_key_exists($row['team'], $sums)) {
				//array_push($sums,array($row['team']=>$row['points']));
				$sums[$row['team']] = $row['points'];
			} else {
				$sums[$row['team']] += $row['points'];

			}
			array_push($arr, array('time' => $row['timestamp'], 'user' => $row['team'], 'points' => $sums[$row['team']]));
			foreach ($sums as $key => $value) {

				if (strcmp($key, $row['team']) != 0) {
					array_push($arr, array('time' => $row['timestamp'], 'user' => $key, 'points' => $value));
				}
			}
		}

		return $arr;
	}

	/**
	 * Get files for downloadable challenges
	 */
	public static function getFiles() {
		$querystr = "
		SELECT * FROM files
		";
		LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
		return QueryManager::query($querystr);
	}

}
?>