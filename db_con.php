<?php 
       $host = 'localhost';
        $user = 'root';
        $password = 'root';
        $dbname = 'app';

        $connect = mysqli_connect($host, $user, $password, $dbname);
        $GLOBALS['connect'];

   		if(!$connect){
            die('Connect Error: '.mysqli_connect_error());
        	return ;
        }

?>