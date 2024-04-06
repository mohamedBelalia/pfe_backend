<?php 

class DbConnection{
    private static $server = "localhost";
    private static $user = "root";
    private static $pwd = "";
    private static $db = "pfe_database";

    public static function dbCon(){
        $con = new mysqli(self::$server , self::$user , self::$pwd , self::$db);
        if($con->connect_error){
            return null ;
        }
        return $con ;
    }
}



?>