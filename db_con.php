<?php 
       $host = 'localhost';
        $user = 'root';
        $password = '961111';
        $dbname = 'app';

        $connect = mysqli_connect($host, $user, $password, $dbname);
        $GLOBALS['connect'];

   		if(!$connect){
            die('Connect Error: '.mysqli_connect_error());
        	return ;
        }

?>
