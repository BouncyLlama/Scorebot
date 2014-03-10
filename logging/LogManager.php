<?php
require_once 'logging/model/LogModel.php';

/**
 * Different event types that can be logged
 * Needs expanding upon
 */
abstract class LogAction {
	const LOGIN = 'User Login';
	const LOGOUT = 'User Logout';
	const SESSIONCREATE = 'Session Created';
	const SESSIONDESTROY = 'Session Destroyed';
	const SESSIONUPDATE = 'Session Updated';
	const FLAGSUBMIT = 'Flag Submission';
	const PAGEVIEW = 'Page Viewed';
	const QUERY = 'Query Run';
}

class LogManager {

	public static function log($action, $username, $description, $querystr) {

		LogModel::log($action, $username, time(), $description, $querystr);
	}

}
?>