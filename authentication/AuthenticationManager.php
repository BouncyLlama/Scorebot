<?php
require_once 'authentication/model/AuthenticationModel.php';
require_once 'logging/LogManager.php';
class AuthenticationManager {
	/**
	 * Log in
	 * Creates a session, if anything fails returns false
	 */
	public static function logIn($username, $password) {

		$result = AuthenticationModel::getUser($username);
		$row = mysql_fetch_assoc($result);
		$passwordHash = $row['password'];
		$userHash = hash("sha512", $password . Config::$PW_SALT);
		if (strcmp($userHash, $passwordHash) == 0) {
			if (AuthenticationModel::createSession($_SERVER['REMOTE_ADDR'], $username, session_id())) {
				$_SESSION['username'] = $username;
				$_SESSION['team'] = $row['team'];
				LogManager::log(LogAction::LOGIN, $username, null, null);
				LogManager::log(LogAction::SESSIONCREATE, $username, null, null);
				return TRUE;
			}
			return FALSE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Log out
	 */
	public static function logOut($username) {
		LogManager::log(LogAction::LOGOUT, $username, null, null);
		AuthenticationManager::destroySession(session_id(), $_SERVER['REMOTE_ADDR'], $username);

	}

	/**
	 * Destroy a login session
	 */
	public static function destroySession($cookie, $ip, $username) {
		LogManager::log(LogAction::SESSIONDESTROY, $username, null, null);
		AuthenticationModel::destroySession($cookie, $ip, $username);
	}

	/**
	 * Register a user
	 */
	public static function register($username, $password, $team) {
		return AuthenticationModel::registerUser($username, hash('sha512', $password . Config::$PW_SALT), $team);

	}

	/**
	 * Verify a login session is valid
	 */
	public static function checkSession() {

		$result = AuthenticationModel::getSession(session_id(), $_SERVER['REMOTE_ADDR']);
		$row = mysql_fetch_assoc($result);
		if (mysql_numrows($result) < 1 || !isset($_SESSION['username'])) {
			return FALSE;
		}
		$timestamp = $row['init_time'];

		$date = new DateTime();
		$now = $date -> getTimestamp();

		if ($now - $timestamp > Config::$SESSION_TIMEOUT) {
			AuthenticationModel::destroySession(session_id(), $_SERVER['REMOTE_ADDR'],$_SESSION['username']);
			return FALSE;

		}
		$sessUser = $row['username'];
		if (strcmp($sessUser, $_SESSION['username']) == 0) {
			AuthenticationModel::updateSession(session_id(), $_SERVER['REMOTE_ADDR']);
			return TRUE;
		} else {echo $sessUser . " : " . $_SESSION['username'];

			return FALSE;

		}

	}

}
?>