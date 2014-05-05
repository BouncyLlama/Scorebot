<?php

/**
 * Created by IntelliJ IDEA.
 * User: ethan
 * Date: 5/5/14
 * Time: 11:22 AM
 */
require_once 'authentication/AuthenticationManager.php';
require_once 'logging/LogManager.php';
date_default_timezone_set('America/New_York');
class AdminModel{
    /**
     * Get all flags
     */
    public static function getFlags() {
        if (!AuthenticationManager::checkSession()) {
            return NULL;
        }
        $querystr = "
		SELECT f.*,t.name as team,t.id as teamid
          FROM flags AS f
            join teams AS t
            ON f.team=t.id
		";
        LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
        $result = QueryManager::query($querystr);
        return $result;
    }

    public static function updateFlag($id,$name,$description,$points,$value,$team){
        QueryManager::escape($id);
        QueryManager::escape($name);
        QueryManager::escape($description);
        QueryManager::escape($points);
        QueryManager::escape($value);
        QueryManager::escape($team);
        $querystr="
        UPDATE flags set name='$name',description='$description',points='$points',value='$value',team='$team'
        where id='$id'
        ";
        LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
        $result = QueryManager::query($querystr);

    }
public static function getTeams(){
    $querystr="
    SELECT * from teams
    ";

    $result = QueryManager::query($querystr);
    return $result;
}
public static function deleteFlag($id){
    QueryManager::escape($id);
    $querystr="
    DELETE FROM flags where id='$id';
    ";
    QueryManager::query($querystr);
}
public static function createFlag($name,$description,$points,$value,$team){

    QueryManager::escape($name);
    QueryManager::escape($description);
    QueryManager::escape($points);
    QueryManager::escape($value);
    QueryManager::escape($team);
    $querystr="
        INSERT INTO flags (name,description,points,value,team) values ('$name','$description','$points','$value','$team')

        ";
    LogManager::log(LogAction::QUERY, mysql_real_escape_string($_SESSION['username']), null, $querystr);
    $result = QueryManager::query($querystr);

}
}

?>