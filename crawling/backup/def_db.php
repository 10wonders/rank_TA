<?php


  /** 
    $country 
    
    0 : 한국 
    1 : 미국
    2 : 일본   
  
  **/

 function insert_into_db($country){
      $host = 'localhost';
      $user = 'root';
      $password = 'tjdfkrdnjs~1';
      $dbname = 'chart';
      $port = '3306'; 
      $it_table;
      $ps_table;

      switch ($country) {
             case 0:
               $ps_table = "ps";
               $it_table = "it";
               $it_url = "http://www.apple.com/kr/itunes/charts/free-apps/";
        
               break;
             case 1:
               $ps_table = "us_ps";
               $it_table = "us_it";
               $it_url = "http://www.apple.com/itunes/charts/free-apps/";
               break;
             case 2:
               $ps_table = "jp_ps";
               $it_table = "jp_it";
               $it_url = "http://www.apple.com/jp/itunes/charts/free-apps";
               break;
      }

      $connect = mysqli_connect($host, $user, $password, $dbname);
      if(!$connect) 
            die('Connect Error: '.mysqli_connect_error());

      //play-store Crawling Code

      $ps_url="https://play.google.com/store/apps/collection/topselling_free";
      $ps_ch = curl_init($ps_url);
      curl_setopt($ps_ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ps_ch, CURLOPT_RANGE, '0-100');
      curl_setopt($ps_ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ps_ch, CURLOPT_SSL_VERIFYPEER, 0);
      $ps_content = curl_exec($ps_ch);  
      $ps_img_content= $ps_content;
      curl_close($ps_ch);

       //itunes Crawling Code
      $it_ch = curl_init();
      curl_setopt($it_ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36");
      curl_setopt($it_ch, CURLOPT_TIMEOUT, 30);
      curl_setopt($it_ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($it_ch, CURLOPT_URL, $it_url);
      //curl_setopt($it_ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($it_ch, CURLOPT_REFERER, $ref_url);
      curl_setopt($it_ch, CURLOPT_HEADER, TRUE);
      curl_setopt($it_ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
      curl_setopt($it_ch, CURLOPT_FOLLOWLOCATION, TRUE);
      curl_setopt($it_ch, CURLOPT_POST, TRUE);
      curl_setopt($it_ch, CURLOPT_POSTFIELDS, $data);
      $it_content = curl_exec ($it_ch);
      $it_img_content = $it_content;
      curl_close($it_ch);
     

      $cnt = preg_match_all("/<img\ssrc=\"(.*?)\".*?>/", $it_img_content, $it_img_match);
      preg_match_all("/<img.*?src=\"(.*?)\".*?>/", $ps_content, $ps_img_match);

      preg_match_all("/<h3>+<a.*?>(.*?)<\/a>/",$it_content, $it_match);
      preg_match_all("/<a\sclass=\"title\".*?title=\"(.*?)\".*?>/", $ps_content, $ps_match);

      for($i = 0; $i<$cnt; $i++){
        $ps_name = $ps_match[1][$i];
        $it_name = $it_match[1][$i];
        $ps_image = $ps_img_match[1][$i];
        $it_image = $it_img_match[1][$i];
        $rank = $i+1;
        mysqli_query($connect, "INSERT INTO $ps_table (app_rank, app_name, app_img_href) VALUES($rank, '$ps_name','$ps_image');");
        mysqli_query($connect, "INSERT INTO $it_table (app_rank, app_name, app_img_href) VALUES($rank, '$it_name','$it_image');");
    	}
               
        mysqli_close($connect);
        return $cnt;
      }

      function select_from_db($cnt, $country){
        
        $host = 'localhost';
        $user = 'root';
        $password = 'tjdfkrdnjs~1';
        $dbname = 'chart';
        $port = '3306';
        $table ='ps';

        $connect = mysqli_connect($host, $user, $password, $dbname);
        if(!$connect) 
              die('Connect Error: '.mysqli_connect_error());


        $it_table;
        $ps_table;

      switch ($country) {
             case 0:
               $ps_table = "ps";
               $it_table = "it";
               break;
             case 1:
               $ps_table = "us_ps";
               $it_table = "us_it";
               break;
             case 2:
               $ps_table = "jp_ps";
               $it_table = "jp_it";
               break;
      }

        $it_result = mysqli_query($connect, "SELECT * FROM $it_table;");
    		$ps_result = mysqli_query($connect, "SELECT * FROM $ps_table;");

		    if($it_result && $ps_result){
      	
	      		// 레코드 출력
	      		$i = 0;  		
	      		while ($i<$cnt) {
	            	$it_row = mysqli_fetch_object($it_result);
	            	$ps_row = mysqli_fetch_object($ps_result);

		         
		            $_it_name = $it_row->app_name; 
		            $_ps_name = $ps_row->app_name;
		            $_it_image = $it_row->app_img_href;
		            $_ps_image = $ps_row->app_img_href;

		            printf("
		               <tr>
		                  <th class=\"rank\">%d</th>
		                  <td class=\"ps_app\"><img src=\"%s\" width=\"50\"/>%s</td>
		                  <td class=\"it_app\"><img  src=\"%s\" width=\"50\"/>%s</td>
		               </tr>", $i+1,$_ps_image,$_ps_name, $_it_image, $_it_name
		               );
		            $i++;
	  			}

			    // 메모리 정리
			    mysqli_free_result($it_result);
			    mysqli_free_result($ps_result);
    		}
    		else 
      			echo '<div>query error : select!!</div>';
          
        mysqli_close($connect);
      }
?>