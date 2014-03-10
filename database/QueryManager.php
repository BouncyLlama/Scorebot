<?php
require_once 'Config.php';
mysql_connect(Config::$DB_SERVER, Config::$DB_USER, Config::$DB_PASS) or die(mysql_error());
mysql_select_db(Config::$DB_NAME) or die(mysql_error());

class QueryManager{
	/**
	 * Run a query
	 */
	public static function query($querystr){
		$result = mysql_query($querystr);
		return $result;
		 
	}
	/**
	 * Shortcut to escape a field
	 * Passes in by reference; need to make sure this works
	 */
	public static function escape(&$field){
		$field = mysql_real_escape_string($field);
	}
	/**
	 * Should probably just get rid of this; been doing it by hand anyway
	 */
	public static function getFieldFromRow($field,$idx,$result){
		$index = 0;
		$arr = array();
		while($row = mysql_fetch_assoc($result)){
				if($index==$idx){
					$keys = array_keys($row);
					$values = array_values($row);
					$found = array_search($field, $keys);
					if($found != FALSE)
					{
						return $values[$found];
					}
				}

				$index++;
		}
		return NULL;
		
	}
}

?>