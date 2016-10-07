
<?php
    function insertPsFreeDB(){
		include ('db_con.php');
    	echo "--플레이스토어 무료 앱 순위 업데이트-- <br />";


		for($i = 0; $i < 3; $i++){

			$is_free = true;

		    switch ($i) {
		    	case 0:
		    		$ps_url="https://play.google.com/store/apps/collection/topselling_free?start=0&num=100&gl=us&hl=en";
		    		break;
		    	case 1:
		    		$ps_url="https://play.google.com/store/apps/collection/topselling_free?start=0&num=100&gl=ko&hl=en";
		    		break;
		    	case 2:
		    		$ps_url="https://play.google.com/store/apps/collection/topselling_free?start=0&num=100&gl=jp&hl=en";
		    		break;

		    }
	    	//playstore
		    $ps_ch = curl_init($ps_url);
		    curl_setopt($ps_ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ps_ch, CURLOPT_RANGE, '0-100');
		    curl_setopt($ps_ch, CURLOPT_SSL_VERIFYHOST, 0);
		    curl_setopt($ps_ch, CURLOPT_SSL_VERIFYPEER, 0);
		    $ps_content = curl_exec($ps_ch);  
		    curl_close($ps_ch);

		    	// id추출
		    $cnt = preg_match_all("/<span\sclass=\"preview.*?data-docid=\"(.*?)\".*?>/", $ps_content, $id_matches);
	            // img 추출 
	        preg_match_all("/<img.*?src=\"(.*?)\".*?>/", $ps_content, $ps_img_match);
	            //이름 추출 
		    preg_match_all("/<a\sclass=\"title\".*?title=\"(.*?)\".*?>/", $ps_content, $ps_match);

		    $date = date('Y-m-d');
			$date = date("Y-m-d",strtotime(str_replace('/','-',$date)));

		    

			    for($j = 0; $j < $cnt; ++$j){
			    	$rank = $j + 1;
			    	$id = $id_matches[1][$j];
			    	$img = $ps_img_match[1][$j];
			    	$name = $ps_match[1][$j];
			    	$name = preg_replace("/\'/","", $name); //작은따옴표 제거
			    	// 앱 정보 저장
			    	$result= mysqli_query($connect, "INSERT INTO app_info (ps_id, name, img, isFree) VALUES('$id', '$name', '$img', '$is_free')");

			    	if($i == 0){
						//미국 플레이스토어 무료 앱 순위 DB 저장		    			
		    			$res= mysqli_query($connect, "INSERT INTO us (rank, id_ps_free, day) VALUES('$rank', '$id', '$date')");
	    			}
	    			else if($i == 1){
						//한국 플레이스토어 무료 앱 정보, 순위 DB 저장
	    				$res= mysqli_query($connect, "INSERT INTO ko (rank, id_ps_free, day) VALUES('$rank', '$id', '$date')");
	    			}
	    			else if($i == 2){
						//한국 플레이스토어 무료 앱 정보, 순위 DB 저장
	    				$res= mysqli_query($connect, "INSERT INTO jp (rank, id_ps_free, day) VALUES('$rank', '$id', '$date')");
	    			}
	    		}

		}

    	mysqli_close($connect);
    	insertItFreeDB();
      }
    	insertPsFreeDB();
      //insertItFreeDB();
      //insertPsPaidDB();
      //insertItPaidDB();

    function insertItFreeDB(){
		include ('db_con.php');
     
        echo "--ITUNES 무료 앱 순위 업데이트 완료-- <br />";


		for($i = 0; $i < 3; $i++){

			$is_free = true;

		    switch ($i) {
		    	case 0:
		    		$it_url="http://www.apple.com/itunes/charts/free-apps/";
		    		break;
		    	case 1:
		    		$it_url="http://www.apple.com/kr/itunes/charts/free-apps/";
		    		break;
		    	case 2:
		    		$it_url="http://www.apple.com/jp/itunes/charts/free-apps/";
		    		break;

		    }
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

	        // 링크추출 
			preg_match_all("/<h3><a[\s]+[^>]*?href[\s]?=[\s\"\']+"."(.*?)[\"\']+.*?>"."([^<]+|.*?)?<\/a>/", $it_content, $it_link_match); 
		    // img 추출 
		    preg_match_all("/<img\ssrc=\"(.*?)\".*?>/", $it_img_content, $it_img_match);
		    //이름 추출 
			preg_match_all("/<h3>+<a.*?>(.*?)<\/a>/",$it_content, $it_match);
			//장르 추출
			preg_match_all("/<h4>+<a.*?>(.*?)<\/a>/",$it_content, $it_genre_match);

			$date = date('Y-m-d');
			$date = date("Y-m-d",strtotime(str_replace('/','-',$date)));


			    for($j = 0; $j < 100; ++$j){
			    	$rank = $j+1;
			    	$itunes_link = $it_link_match[1][$j];
			    	preg_match("/id(\d+)/m", $itunes_link, $app_id_match);
          			$id = $app_id_match[1];
			    	$img = $it_img_match[1][$j];
			    	$name = $it_match[1][$j];
			    	$name = preg_replace("/\'/","", $name); //작은따옴표 제거
			    	$genre = $it_genre_match[1][$j];

					$sql = "SELECT * FROM app_info WHERE name='$name'";
		    		$result = mysqli_query($connect, $sql);
		    		$num =mysqli_num_rows($result);

		    		if($num){ //똑같은 앱정보 이미 있음
		    			$sql = "UPDATE app_info SET it_id='$id' WHERE name='$name'";
		    			$res = mysqli_query($connect, $sql);
		    		}
		    		else{
		    			$result= mysqli_query($connect, "INSERT INTO app_info (it_id, img, name, isFree) VALUES('$id', '$img', '$name', '$is_free');");
		    		}

	    			if($i == 0){
	    				//미국 아이튠즈 무료 앱 정보, 순위 DB 저장		    			
		    			$res= mysqli_query($connect, "UPDATE us SET id_it_free='$id' WHERE rank='$rank' and day='$date'");
	    			}

	    			else if($i == 1){
	    				//한국 아이튠즈 무료 앱 정보, 순위 DB 저장	
		    			$res= mysqli_query($connect, "UPDATE ko SET id_it_free='$id' WHERE rank='$rank' and day='$date'");
	    			}
	    			else if($i == 2){
						//일본 아이튠즈 무료 앱 정보, 순위 DB 저장	
	    				$res= mysqli_query($connect, "UPDATE jp SET id_it_free='$id' WHERE rank='$rank' and day='$date'");
	    			}

	    		}		    
		    
		}

    	mysqli_close($connect);
    	insertPsPaidDB();
      }

  function insertPsPaidDB(){

	  include ('db_con.php');
    	echo "--플레이스토어 유료 앱 순위 업데이트 완료-- <br />";


		for($i = 0; $i < 3; $i++){

			$is_free = false;

		    switch ($i) {
		    	case 0:
		    		$ps_url="https://play.google.com/store/apps/collection/topselling_paid?start=0&num=100&gl=us&hl=en";
		    		break;
		    	case 1:
		    		$ps_url="https://play.google.com/store/apps/collection/topselling_paid?start=0&num=100&gl=ko&hl=en";
		    		break;
		    	case 2:
		    		$ps_url="https://play.google.com/store/apps/collection/topselling_paid?start=0&num=100&gl=jp&hl=en";
		    		break;

		    }
	    	//playstore
		    $ps_ch = curl_init($ps_url);
		    curl_setopt($ps_ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ps_ch, CURLOPT_RANGE, '0-100');
		    curl_setopt($ps_ch, CURLOPT_SSL_VERIFYHOST, 0);
		    curl_setopt($ps_ch, CURLOPT_SSL_VERIFYPEER, 0);
		    $ps_content = curl_exec($ps_ch);  
		    curl_close($ps_ch);

		    	// id추출
		    $cnt = preg_match_all("/<span\sclass=\"preview.*?data-docid=\"(.*?)\".*?>/", $ps_content, $id_matches);
	            // img 추출 
	        preg_match_all("/<img.*?src=\"(.*?)\".*?>/", $ps_content, $ps_img_match);
	            //이름 추출 
		    preg_match_all("/<a\sclass=\"title\".*?title=\"(.*?)\".*?>/", $ps_content, $ps_match);

		    $date = date('Y-m-d');
			$date = date("Y-m-d",strtotime(str_replace('/','-',$date)));

		    

			    for($j = 0; $j < $cnt; ++$j){
			    	$rank = $j + 1;
			    	$id = $id_matches[1][$j];
			    	$img = $ps_img_match[1][$j];
			    	$name = $ps_match[1][$j];
			    	$name = preg_replace("/\'/","", $name); //작은따옴표 제거
			    	// 앱 정보 저장
			    	$result= mysqli_query($connect, "INSERT INTO app_info (ps_id, name, img, isFree) VALUES('$id', '$name', '$img', '$is_free')");

			    	if($i == 0){
						//미국 플레이스토어 유료 앱 순위 DB 저장		    			
		    			$res= mysqli_query($connect, "UPDATE us SET id_ps_paid='$id' WHERE rank='$rank' and day='$date'");
	    			}
	    			else if($i == 1){
						//한국 플레이스토어 유료 앱 정보, 순위 DB 저장
	    				$res= mysqli_query($connect, "UPDATE ko SET id_ps_paid='$id' WHERE rank='$rank' and day='$date'");
	    			}
	    			else if($i == 2){
						//한국 플레이스토어 유료 앱 정보, 순위 DB 저장
	    				$res= mysqli_query($connect, "UPDATE jp SET id_ps_paid='$id' WHERE rank='$rank' and day='$date'");
	    			}
	    		}

		}
		//insertItDB();

    	mysqli_close($connect);
    	insertItPaidDB();
      }
    	//insertPsFreeDB();
      
    function insertItPaidDB(){
		include ('db_con.php');
        echo "--ITUNES 유료 앱 순위 업데이트 완료-- <br />";


		for($i = 0; $i < 3; $i++){

			$is_free = false;

		    switch ($i) {
		    	case 0:
		    		$it_url="http://www.apple.com/itunes/charts/paid-apps/";
		    		break;
		    	case 1:
		    		$it_url="http://www.apple.com/kr/itunes/charts/paid-apps/";
		    		break;
		    	case 2:
		    		$it_url="http://www.apple.com/jp/itunes/charts/paid-apps/";
		    		break;

		    }
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

	        // 링크추출 
			preg_match_all("/<h3><a[\s]+[^>]*?href[\s]?=[\s\"\']+"."(.*?)[\"\']+.*?>"."([^<]+|.*?)?<\/a>/", $it_content, $it_link_match); 
		    // img 추출 
		    preg_match_all("/<img\ssrc=\"(.*?)\".*?>/", $it_img_content, $it_img_match);
		    //이름 추출 
			preg_match_all("/<h3>+<a.*?>(.*?)<\/a>/",$it_content, $it_match);
			//장르 추출
			preg_match_all("/<h4>+<a.*?>(.*?)<\/a>/",$it_content, $it_genre_match);

			$date = date('Y-m-d');
			$date = date("Y-m-d",strtotime(str_replace('/','-',$date)));


			    for($j = 0; $j < 100; ++$j){
			    	$rank = $j+1;
			    	$itunes_link = $it_link_match[1][$j];
			    	preg_match("/id(\d+)/m", $itunes_link, $app_id_match);
          			$id = $app_id_match[1];
			    	$img = $it_img_match[1][$j];
			    	$name = $it_match[1][$j];
			    	$name = preg_replace("/\'/","", $name); //작은따옴표 제거
			    	$genre = $it_genre_match[1][$j];

					$sql = "SELECT * FROM app_info WHERE name='$name'";
		    		$result = mysqli_query($connect, $sql);
		    		$num =mysqli_num_rows($result);

		    		if($num){ //똑같은 앱정보 이미 있음
		    			$sql = "UPDATE app_info SET it_id='$id' WHERE name='$name'";
		    			$res = mysqli_query($connect, $sql);
		    		}
		    		else{
		    			$result= mysqli_query($connect, "INSERT INTO app_info (it_id, img, name, isFree) VALUES('$id', '$img', '$name', '$is_free');");
		    		}

	    			if($i == 0){
	    				//미국 아이튠즈 유료 앱 정보, 순위 DB 저장		    			
		    			$res= mysqli_query($connect, "UPDATE us SET id_it_paid='$id' WHERE rank='$rank' and day='$date'");
	    			}

	    			elseif($i == 1){
	    				//한국 아이튠즈 유료 앱 정보, 순위 DB 저장	
		    			$res= mysqli_query($connect, "UPDATE ko SET id_it_paid='$id' WHERE rank='$rank' and day='$date'");
	    			}
	    			elseif($i == 2){
	    				//일본 아이튠즈 유료 앱 정보, 순위 DB 저장	
		    			$res= mysqli_query($connect, "UPDATE jp SET id_it_paid='$id' WHERE rank='$rank' and day='$date'");
	    			}

	    		}		    
		    
		}

    	mysqli_close($connect);
      }
	 
?>
