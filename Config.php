<?php
class Config{
	
	static $DB_SERVER = '127.0.0.1';
	static $DB_USER = 'root';
	static $DB_PASS= '';
	static $DB_NAME= 'scorebot2';
	static $PW_SALT = 'supercalifragalisticexpialidocious';
	static $SESSION_TIMEOUT = 3600000; //One Hour
	static $IS_TEAM_EXERCISE = TRUE;
	static $ROOT = "/var/www/";	
	static $IMAGES = '/var/www/images/';
	static $SCRIPTS = "/var/www/scripts";
}


?>