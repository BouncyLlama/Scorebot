<?php
require_once 'ui/model/PlayerModel.php';
require_once 'logging/LogManager.php';
class Player{
	public static function getFlags(){
		return PlayerModel::getFlags()	;
	}
		public static function getFlag($id){
		
		$result = PlayerModel::getFlag($id);
		if(!$result){
			die(mysql_error());
		}
		$flag= mysql_fetch_assoc($result);
		return $flag;
	}
	public static function submitFlag($id,$submission){
		$flag = Player::getFlag($id);
		LogManager::log(LogAction::FLAGSUBMIT, $_SESSION['username'], "Submitted flag ID $id", null);
		return PlayerModel::submitFlag($id, $submission, $flag['value']);
	}
	public static function getUnsolvedFlags(){
		return PlayerModel::getUnsolvedFlags();
	}
	public static function getFlagScores(){
		return json_encode(PlayerModel::getFlagScores());
	}
	public static function getFiles(){
		return PlayerModel::getFiles();
	}
	
}

?>