<?php

/**
 * Created by IntelliJ IDEA.
 * User: ethan
 * Date: 5/5/14
 * Time: 11:21 AM
 */
require_once 'ui/model/AdminModel.php';
class Admin{
    public static function getRoles()
    {
        return AdminModel::getRoles();
    }
    public static function getFlags(){

        return AdminModel::getFlags();
    }

    public static function getPlayers()
    {
        return AdminModel::getPlayers();
    }
    public static function updateFlag($id,$name,$description,$points,$value,$team){

        AdminModel::updateFlag($id,$name,$description,$points,$value,$team);
    }

    public static function updatePlayer($id, $username, $handle, $role, $team, $password)
    {
        AdminModel::updatePlayer($id, $username, $handle, $role, $team, $password);
    }

    public static function deletePlayer($id)
    {
        AdminModel::deletePlayer($id);
    }
    public static function getTeams(){
        return AdminModel::getTeams();
    }

    public static function deleteFlag($id){
    AdminModel::deleteFlag($id);
    }
    public static function createFlag($name,$description,$points,$value,$team){
        AdminModel::createFlag($name,$description,$points,$value,$team);
    }
}