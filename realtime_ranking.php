<?php include ('crawling.php');?>

<?php
 function chkResource($country, $provider){

    if($country=="us"){
      if($provider == "google"){    
        $ps_url_free = "https://play.google.com/store/apps/collection/topselling_free?start=0&num=100&gl=us";
        $ps_url_paid = "https://play.google.com/store/apps/collection/topselling_paid?start=0&num=100&gl=us";     
      }
      else{
        $it_url_free = "http://www.apple.com/itunes/charts/free-apps/";
        $it_url_paid = "http://www.apple.com/itunes/charts/paid-apps/";
      }
    }
    else{
      if($provider == "google"){
        $ps_url_free = "https://play.google.com/store/apps/collection/topselling_free?start=0&num=100&gl=".$country;
        $ps_url_paid = "https://play.google.com/store/apps/collection/topselling_paid?start=0&num=100&gl=".$country;
      }
      else{
        $it_url_free = "http://www.apple.com/".$country."/itunes/charts/free-apps/";
        $it_url_paid = "http://www.apple.com/".$country."/itunes/charts/paid-apps/";
       
      }
    }
    if($provider == "google") loadRanking($ps_url_paid, $ps_url_free, $country, $provider );
    else loadRanking($it_url_paid, $it_url_free, $country, $provider);
 }

 function loadRanking($it_url, $ps_url, $country, $provider){

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

        //google 
        if($provider == "google"){
          $id_free = 'id_ps_free';
          $id_paid = 'id_ps_paid';
          $id_feild = 'ps_id';
        }
        //itunes
        else{
          $id_free = 'id_it_free';
          $id_paid = 'id_it_paid';
          $id_feild = 'it_id';

        }
    echo $country.' / '.$provider.'<br />';

        $date = date('Y-m-d');
        $beforeDay = date("Y-m-d", strtotime($day." -1 day"));

        $result = mysqli_query($connect, "SELECT rank, $id_free, $id_paid FROM $country WHERE day='$date'");

        printf("
            <table border=\"0\">
                <thead>
                    <td class=\"thead-line\">No</td>
                    <td class=\"thead-line\"></td>
                    <td class=\"thead-line\">App Free</td>
                    <td class=\"thead-line\"></td>
                    <td class=\"thead-line\">App Paid</td>
                </thead>
        ");

        for($i = 0; $row = mysqli_fetch_object($result); $i++){
          
            
            if($provider == "google"){
              $free = $row->id_ps_free;
              $paid = $row->id_ps_paid;              
              $selected1 = mysqli_query($connect, "SELECT name, img FROM app_info WHERE $id_feild='$free';");
              $selected2 = mysqli_query($connect, "SELECT name, img FROM app_info WHERE $id_feild='$paid';");
              $selbefore1 = mysqli_query($connect, "SELECT rank FROM $country WHERE id_ps_free = '$free' and day = '$beforeDay'");
              $selbefore2 = mysqli_query($connect, "SELECT rank FROM $country WHERE id_ps_paid = '$paid' and day = '$beforeDay'");
            }
            else{
              $free = $row->id_it_free;
              $paid = $row->id_it_paid;
              $selected1 = mysqli_query($connect, "SELECT name, img FROM app_info WHERE $id_feild=$free");
              $selected2 = mysqli_query($connect, "SELECT name, img FROM app_info WHERE $id_feild=$paid");
              $selbefore1 = mysqli_query($connect, "SELECT rank FROM $country WHERE id_it_free = '$free' and day = '$beforeDay'");
              $selbefore2 = mysqli_query($connect, "SELECT rank FROM $country WHERE id_it_paid = '$paid' and day = '$beforeDay'");              
            }

            $sel_obj1 = mysqli_fetch_object($selected1);
            $sel_obj2 = mysqli_fetch_object($selected2);

            $ranking = $row->rank;
            $sel_obj_name1 = $sel_obj1->name;
            $sel_obj_img1 = $sel_obj1->img;            
            $sel_obj_name2 = $sel_obj2->name;
            $sel_obj_img2 = $sel_obj2->img;

            $download_free = 'df'.$i;
            $chart_free = 'cf'.$i;
            $download_paid = 'dp'.$i;
            $chart_paid = 'cp'.$i;

            $sel_rank1 = mysqli_fetch_row($selbefore1);
            $sel_rank2 = mysqli_fetch_row($selbefore2);

            $before_rank1 = $sel_rank1[0];
            $before_rank2 = $sel_rank2[0];

            if($before_rank1!=true){
                $changerank1 ="new";
                $changeval1=null;
            }
            elseif($ranking>$before_rank1){
                $down = $before_rank1-$ranking;
                $changerank1 = "down";
                $changeval1 = $down;
            }
            elseif($ranking<$before_rank1){
                $up = $before_rank1-$ranking;
                $changerank1 = "up";
                $changeval1 = $up;
            }
            elseif($ranking==$before_rank1){
                $changerank1 = "same";
                $changeval1 = null;
            }

            if($before_rank2!=true){
                $changerank2 ="new";
                $changeval2=null;
            }
            elseif($ranking>$before_rank2){
                $down = $before_rank2-$ranking;
                $changerank2 = "down";
                $changeval2 = $down;
            }
            elseif($ranking<$before_rank2){
                $up = $before_rank2-$ranking;
                $changerank2 = "up";
                $changeval2 = $up;
            }
            elseif($ranking==$before_rank2){
                $changerank2 = "same";
                $changeval2 = null;
            }
            //$changeimg2 = $changerank2.".png";
            printf("<tr><td>%s</td>", $ranking);
            printf("<td><img src=\"%s\" width=\"50\"></td>
              <td>
                <div class=\"cls_app_name\">
                  <div class=\"app_name\">%s</div>
                  <div class=\"app_href\">
                    <a href=\"https://play.google.com/store/apps/details?id=%s\">
                    <img onmouseover=\"app_img_on('%s', 1)\" onmouseout=\"app_img_off('%s', 1)\" id=\"%s\" class=\"app_download\" src=\"off-google-play.png\" width=\"20\" height=\"20\"></a>
                    <a href = \"show_chart.php?country=$country$sec=free&id=$free\" target='_blank'>
                    <img onmouseover=\"app_img_on('%s', 0)\" onmouseout=\"app_img_off('%s', 0)\" id=\"%s\" class=\"app_download\" src=\"off-chart.png\" width=\"20\" height=\"20\"></a>
                    <span class = '$changerank1'>
                        <img class = '$changerank1' src='%s.png'>%s
                    </span>
                  </div>
                </div>
              </td>", $sel_obj_img1, $sel_obj_name1, $free, $download_free, $download_free, $download_free, $chart_free, $chart_free, $chart_free, $changerank1, $changeval1);           
            printf("<td><img src=\"%s\" width=\"50\"></td>
              <td>
                <div class=\"cls_app_name\">
                  <div class=\"app_name\">%s</div>
                  <div class=\"app_href\">
                     <a href=\"https://play.google.com/store/apps/details?id=%s\">
                        <img onmouseover=\"app_img_on('%s', 1)\" onmouseout=\"app_img_off('%s', 1)\" id=\"%s\" class=\"app_download\" src=\"off-google-play.png\" width=\"20\" height=\"20\"></a>
                     <a href = \"show_chart.php?country=$country&sec=paid&id=$paid\" target='_blank'>
                        <img onmouseover=\"app_img_on('%s', 0)\" onmouseout=\"app_img_off('%s', 0)\" id=\"%s\" class=\"app_download\" src=\"off-chart.png\" width=\"20\" height=\"20\">
                     </a>
                     <span class = '$changerank2'>
                        <img class = '$changerank2' src='%s.png'>%s
                     </span>
                 </div>
                </div>
              </td>", $sel_obj_img2, $sel_obj_name2, $paid, $download_paid,$download_paid,$download_paid, $chart_paid, $chart_paid, $chart_paid, $changerank2, $changeval2);           
        }
  }
      $i = $_REQUEST['Country'];  
      $j = $_REQUEST['chk_info'];

      if($i==NULL){
        $i = "kr";
        $j = "google";
      }
     chkResource($i, $j);
?>
