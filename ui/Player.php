<?php
require_once 'ui/model/PlayerModel.php';
require_once 'logging/LogManager.php';
class Player {
	public static function getLatestServiceCheck($id){
		$qresult = PlayerModel::getService($id);
		$result = mysql_fetch_assoc($qresult);
		if(!isset($result['team'])){
			return FALSE;
		}
		
		if (strcmp($result['team'], $_SESSION['team']) != 0) {
			return FALSE;
		}
		return PlayerModel::getLatestServiceCheck($id);
	}
	public static function setServicePassword($id, $password) {
		$result = PlayerModel::getService($id);
		$service = mysql_fetch_assoc($result);
		if (strcmp($service['team'], $_SESSION['team']) != 0) {
			return FALSE;
		}
		PlayerModel::setServicePassword($id, $password);
		return TRUE;
	}

	public static function getAssets() {
		return PlayerModel::getAssets($_SESSION['team']);

	}

	/**
	 * Get all flags
	 */
	public static function getFlags() {
		return PlayerModel::getFlags();
	}

	/**
	 * Get a particular flag by integer id
	 */
	public static function getFlag($id) {

		$result = PlayerModel::getFlag($id);
		if (!$result) {
			die(mysql_error());
		}
		$flag = mysql_fetch_assoc($result);
		return $flag;
	}

	/**
	 * Submit value for flag identified by integer id
	 */
	public static function submitFlag($id, $submission) {
		$flag = Player::getFlag($id);
		LogManager::log(LogAction::FLAGSUBMIT, $_SESSION['username'], "Submitted flag ID $id", null);
		return PlayerModel::submitFlag($id, $submission, $flag['value']);
	}

	/**
	 * Get all flags your team has not solved
	 */
	public static function getUnsolvedFlags() {
		return PlayerModel::getUnsolvedFlags();
	}

	/**
	 * Get scoring data (only flag submissions right now)
	 */
	public static function getFlagScores() {
		return json_encode(PlayerModel::getFlagScores());
	}

	/**
	 * Get files (for downloadable challenges)
	 */
	public static function getFiles() {
		return PlayerModel::getFiles();
	}

}
?>