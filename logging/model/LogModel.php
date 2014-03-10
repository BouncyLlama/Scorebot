<?php
class LogModel {

	/**
	 * Log an action
	 * Accepts null values for description and query
	 */
	public static function log($action, $username, $timestamp, $description, $query) {
		QueryManager::escape($action);
		QueryManager::escape($username);
		QueryManager::escape($timestamp);
		QueryManager::escape($description);
		QueryManager::escape($query);
		$querystr = "
		INSERT INTO log (action,username,timestamp,description,query) VALUES('$action','$username','$timestamp','$description','$query')
		";
		QueryManager::query($querystr);
	}

}
?>