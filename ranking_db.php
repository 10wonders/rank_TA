
<?php
ini_set('max_execution_time', 0); 


    function ps_DB($free_paid, $country, $is_free){
		include ('db_con.php');

    	echo "--플레이스토어 앱 순위 업데이트-- <br />";
		$ps_url="https://play.google.com/store/apps/collection/topselling_".$free_paid."?start=0&num=100&gl=".$country."&hl=en";
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
		$date = date("Y-m-d",strtotime(str_replace('/','-',$date))); // today

		//개선 필요할 것 같습니다. 너무 느려서 뻗을 수 있을듯..
	    for($j = 0; $j < $cnt; ++$j){
	    	$rank = $j + 1;
	    	$id = $id_matches[1][$j];
	    	$img = $ps_img_match[1][$j];
	    	$name = $ps_match[1][$j];
	    	$name = preg_replace("/\'/","", $name); //작은따옴표 제거
	    	/*
	    	  $ps_link = "https://play.google.com/store/apps/details?id=".$id."&hl=en&gl=".$contury."";
	    	  $ps_ch_link = curl_init($ps_link);
	          curl_setopt($ps_ch_link, CURLOPT_RETURNTRANSFER, 1);
	          curl_setopt($ps_ch_link, CURLOPT_RANGE, '0-100');
	          curl_setopt($ps_ch_link, CURLOPT_SSL_VERIFYHOST, 0);
	          curl_setopt($ps_ch_link, CURLOPT_SSL_VERIFYPEER, 0);
	          $ps_content_link = curl_exec($ps_ch_link);  
	          curl_close($ps_ch_link);
	          preg_match('/<span itemprop="genre".*?>(.*?)<\/span>/', $ps_content_link, $ps_genre_match);//장르(카테고리)
	          $ps_genre = $ps_genre_match[1];
	          echo $ps_genre;
			*/
	    	// 앱 정보 저장
	    	$sql = "SELECT * FROM app_info WHERE name='$name'";
    		$result = mysqli_query($connect, $sql);
    		$num =mysqli_num_rows($result);

    		if($num){ //똑같은 앱정보 이미 있음
    			$sql = "UPDATE app_info SET ps_id='$id' WHERE name='$name'";
    			$res = mysqli_query($connect, $sql);
    		}
    		else{
    			$result= mysqli_query($connect, "INSERT INTO app_info (ps_id, img, name, isFree) VALUES('$id', '$img', '$name', '$is_free')");
    		}

    		$id_ps = "id_ps_".$free_paid;
    		if($is_free){
    			$res= mysqli_query($connect, "INSERT INTO $country (rank, $id_ps, day) VALUES('$rank', '$id', '$date')");
    		}
    		else{
    			$res= mysqli_query($connect, "UPDATE $country SET $id_ps='$id' WHERE rank='$rank' and day='$date'");
    		}
			
		}
	    	mysqli_close($connect);
	    	//it_DB($free_paid, $country, $is_free);
	}


	function ps_genre(){
		include('db_con.php');

		$sql = "SELECT ps_id FROM app_info WHERE ps_genre IS NULL AND ps_id IS NOT NULL";
		$result = mysqli_query($connect, $sql);
		$num= mysqli_num_rows($result);
		echo $num;
		for($i=0;$i<$num;$i++){
			$id=mysqli_fetch_row($result);
			$ps_link = "https://play.google.com/store/apps/details?id=".$id[0]."&hl=en&gl=us";
			$ps_ch_link = curl_init($ps_link);
			curl_setopt($ps_ch_link, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ps_ch_link, CURLOPT_RANGE, '0-100');
			curl_setopt($ps_ch_link, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ps_ch_link, CURLOPT_SSL_VERIFYPEER, 0);
			$ps_content_link = curl_exec($ps_ch_link);
			curl_close($ps_ch_link);
			preg_match('/<span itemprop="genre".*?>(.*?)<\/span>/', $ps_content_link, $ps_genre_match);//장르(카테고리)
			$ps_genre = $ps_genre_match[1];
			$ps_genre = preg_replace("/&amp;/","&", $ps_genre);
			echo $ps_genre;
			mysqli_query($connect, "UPDATE app_info SET ps_genre='$ps_genre' WHERE ps_id='$id[0]'");
		}
		mysqli_close($connect);
	}



    function it_DB($free_paid, $country, $is_free){
		include ('db_con.php');
        echo "--ITUNES 앱 순위 업데이트 완료-- <br />".$free_paid.$country.$is_free;
        if($country=="us"){
              $it_url = "http://www.apple.com/itunes/charts/".$free_paid."-apps/";
   		}
    	else{
              $it_url = "http://www.apple.com/".$country."/itunes/charts/".$free_paid."-apps/";
    	}
    	  //itunes Crawling Code
        $it_ch = curl_init();
        curl_setopt($it_ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36");
        curl_setopt($it_ch, CURLOPT_TIMEOUT, 60);
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
		$date = date("Y-m-d",strtotime(str_replace('/','-',$date))); //today


	    for($j = 0; $j < 100; ++$j){
	    	$rank = $j+1;
	    	$itunes_link = $it_link_match[1][$j];
	    	preg_match("/id(\d+)/m", $itunes_link, $app_id_match);
  			$id = $app_id_match[1];
	    	$img = $it_img_match[1][$j];
	    	$name = $it_match[1][$j];
	    	$name = preg_replace("/\'/","", $name); //작은따옴표 제거
	    	$genre = $it_genre_match[1][$j];
			$genre = preg_replace("/&#38;/","&", $genre);
/*
	    	  $it_link = explode("APPS",$itunes_link);
	          $it_link = $it_link[0]."APPS&l=en&ign-mpt=uo%3D4";

	          $it_link_ch = curl_init();
	          curl_setopt($it_link_ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36");
	          //curl_setopt($it_ch, CURLOPT_TIMEOUT, 30);
	          curl_setopt($it_link_ch, CURLOPT_RETURNTRANSFER, TRUE);
	          curl_setopt($it_link_ch, CURLOPT_URL, $it_link);
	          //curl_setopt($it_ch, CURLOPT_RETURNTRANSFER, 1);
	          curl_setopt($it_link_ch, CURLOPT_REFERER, $ref_url);
	          curl_setopt($it_link_ch, CURLOPT_HEADER, TRUE);
	          curl_setopt($it_link_ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	          curl_setopt($it_link_ch, CURLOPT_FOLLOWLOCATION, TRUE);
	          curl_setopt($it_link_ch, CURLOPT_SSL_VERIFYPEER, false);
	          curl_setopt($it_link_ch, CURLOPT_POST, TRUE);
	          curl_setopt($it_link_ch, CURLOPT_POSTFIELDS, $data);
	          $it_link_content = curl_exec ($it_link_ch);
	          curl_close($it_link_ch);
	          preg_match('/<span itemprop="applicationCategory".*?>(.*?)<\/span>/', $it_link_content, $it_genre_match);// 장르
	          $it_genre_match = $it_genre[1];
*/

			$sql = "SELECT * FROM app_info WHERE name='$name'";
    		$result = mysqli_query($connect, $sql);
    		$num =mysqli_num_rows($result);

    		if($num){ //똑같은 앱정보 이미 있음
    			$sql = "UPDATE app_info SET it_id='$id', it_genre='$genre' WHERE name='$name'";
    			$res = mysqli_query($connect, $sql);
    		}
    		else{
    			$result= mysqli_query($connect, "INSERT INTO app_info (it_id, img, name, isFree, it_genre) VALUES('$id', '$img', '$name', '$is_free', '$genre');");
    		}
    		$id_it = "id_it_".$free_paid;
			//아이튠즈 무료 앱 정보, 순위 DB 저장		    			
    		$res= mysqli_query($connect, "UPDATE $country SET $id_it='$id' WHERE rank='$rank' and day='$date'");

		}		    
		 	
    	mysqli_close($connect);

    }



    for($i = 0; $i < 3; $i++){
    	for($j = 0; $j < 2; $j++){
    		if($j==0){
				$is_free = true;
				$free_paid = "free";
				echo $i;

			}
			elseif($j==1){
				$is_free = false;
				$free_paid = "paid";
			}

		    switch ($i) {
		    	case 0:
		    		$country = 'us';
		    		break;
		    	case 1:
		    		$country = 'ko';
		    		break;
		    	case 2:
		    		$country = 'jp';
		    		break;
		    }

		ps_DB($free_paid, $country, $is_free);
		it_DB($free_paid, $country, $is_free);
		}
	}
	ps_genre();
?>
