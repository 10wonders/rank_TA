<meta name = 'viewport' charset='utf-8'>


<?php

 

    function func(){

        printf("
            <table>
                <thead>
                    <td>No</td>
                    <td>Image</td>
                    <td>App Name</td>
                    <td>Google ID</td>
                    <td>Apple ID</td>
                </thead>
        ");
    	$host = 'localhost';
        $user = 'root';
        $password = 'tjdfkrdnjs~1';
        $dbname = 'app';

        $connect = mysqli_connect($host, $user, $password, $dbname);
        $GLOBALS['connect'];

   		if(!$connect){
            die('Connect Error: '.mysqli_connect_error());
        	return ;
        }

        $table = 'app_info';
        $result = mysqli_query($connect, "SELECT * FROM $table");
        
        $row_num = mysqli_num_rows($result);


   
        for($i = 0; $i<$row_num; $i++){
        	$row = mysqli_fetch_object($result);
        	$name = $row->name;
       		$img = $row->img;  
       		$ps_id = $row->ps_id;
       		$it_id = $row->it_id;

        	printf("<tr><td>%s</td>", $i+1);
        	printf("<td><img src=\"%s\" width=\"50\"></td><td>%s</td>", $img, $name);         	
            printf("<td>%s</td><td>%s</td></tr>",$ps_id, $it_id);
        }
		mysqli_close($connect);
    }        
?>
</table>