<?php 

class PDO_Connection{

    public $_db;

    public function __construct(){    
        $db_host = 'localhost';
        $db_name = 'leaverequest';
        $db_user = 'root';
        $db_pw = '';

        try{
            
            // Initialise PDO :: charset to UTF8
            
            $this->_db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF8", $db_user, $db_pw);
      
            // Prior to PHP 5.3.6 the charset option was ignored (use next)
            //array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            return array("error" => $e->getMessage() );
        }
    }

}
