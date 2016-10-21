<?php include ('crawling.php');?>

<?php

 date_default_timezone_set('Asia/Seoul');

function loadRanking($country, $provider){
	 include ('db_con.php');


	 //google
    if($provider == "google"){
      $id_free = 'id_ps_free';
      $id_paid = 'id_ps_paid';
      $id_feild = 'ps_id';
      $genre_field = "ps_genre";
    }
    //itunes
    else{
      $id_free = 'id_it_free';
      $id_paid = 'id_it_paid';
      $id_feild = 'it_id';
      $genre_field = "it_genre";
    }

    
        
    echo $country.' / '.$provider.'<br />';
    printf("<div id='genre'>
            <form id='gen_form' method='post' action='realtime_ranking.php'>
            <input type='hidden' name='Country' value='%s'>
            <input type='hidden' name='provider' value='%s'>
            <select id='genre' name=\"genre\" onchange=\"this.form.submit();\">
            <option value='NULL'>Category</option>
            <option value=\"All\">All</option>", $country, $provider);

    $gen_res = mysqli_query($connect,"SELECT DISTINCT $genre_field From app_info");

    for($i = 0; $row = mysqli_fetch_object($gen_res); $i++){
      $genre_list = $row->$genre_field;
      if($genre_list!=NULL){
        printf("<option value=\"%s\">%s</option>", $genre_list, $genre_list);
      }
    }

    printf("</select>
            </form>
            </div>");
         $date = date('Y-m-d');
        $beforeDay = date("Y-m-d", strtotime($day." -1 day"));   
         

        // including the session file
        require_once("session_start.php");


        if (isset($_POST['genre'])) { 
          $_SESSION['genre'] = $_POST['genre'];
        } 

        $genre = $_SESSION['genre'];
        echo $genre.'<br />';
        if($genre==NULL or $genre=="All"){
          $genre="All";
          $result = mysqli_query($connect, "SELECT rank, $id_free, $id_paid FROM $country WHERE day='$date'");
          $num = mysqli_num_rows($result);
        }
        else{
          $result1 = mysqli_query($connect, "SELECT rank, $id_free FROM $country 
            RIGHT OUTER JOIN app_info on $country.$id_free = app_info.$id_feild WHERE day='$date' and $genre_field='$genre'");
          $num1 = mysqli_num_rows($result1);
          $result2 = mysqli_query($connect, "SELECT rank, $id_paid FROM $country 
            RIGHT OUTER JOIN app_info on $country.$id_paid = app_info.$id_feild WHERE day='$date' and $genre_field='$genre'");
          $num2 = mysqli_num_rows($result2);
          if($num1<$num2){
            $num=$num2;
          }
          else{
            $num=$num1;
          }
        }

        printf("
            <table border=\"0\">
                <thead>
                    <td class=\"thead-line\">No</td>
                    <td class=\"thead-line\"></td>
                    <td class=\"thead-line\">무료 앱</td>
                    <td class=\"thead-line\"></td>
                    <td class=\"thead-line\">유료 앱</td>
                </thead>
        ");
        for($i = 0; $i<$num; $i++){
            if($provider == "google"){
              if($genre=="All"){
                $row = mysqli_fetch_object($result);
                $free = $row->id_ps_free;
                $paid = $row->id_ps_paid;
                $ranking1 = $row->rank;
                $ranking2 = $row->rank;
              }
              else{
                $row1 = mysqli_fetch_object($result1);
                $row2 = mysqli_fetch_object($result2);
                $free = $row1->id_ps_free;
                $paid = $row2->id_ps_paid;
                $ranking1 = $row1->rank;
                $ranking2 = $row2->rank;
              }
   
              $selected1 = mysqli_query($connect, "SELECT name, img, ps_genre FROM app_info WHERE $id_feild='$free';");
              $selected2 = mysqli_query($connect, "SELECT name, img, ps_genre FROM app_info WHERE $id_feild='$paid';");
              $selbefore1 = mysqli_query($connect, "SELECT rank FROM $country WHERE id_ps_free = '$free' and day = '$beforeDay'");
              $selbefore2 = mysqli_query($connect, "SELECT rank FROM $country WHERE id_ps_paid = '$paid' and day = '$beforeDay'");
            }
            else{
              if($genre=="All"){
                $row = mysqli_fetch_object($result);
                $free = $row->id_it_free;
                $paid = $row->id_it_paid;
                $ranking1 = $row->rank;
                $ranking2 = $row->rank;
              }
              else{
                $row1 = mysqli_fetch_object($result1);
                $row2 = mysqli_fetch_object($result2);
                $free = $row1->id_it_free;
                $paid = $row2->id_it_paid;
                $ranking1 = $row1->rank;
                $ranking2 = $row2->rank;
              }

              $selected1 = mysqli_query($connect, "SELECT name, img, it_link, it_genre FROM app_info WHERE $id_feild=$free");
              $selected2 = mysqli_query($connect, "SELECT name, img, it_link, it_genre FROM app_info WHERE $id_feild=$paid");
              $selbefore1 = mysqli_query($connect, "SELECT rank FROM $country WHERE id_it_free = '$free' and day = '$beforeDay'");
              $selbefore2 = mysqli_query($connect, "SELECT rank FROM $country WHERE id_it_paid = '$paid' and day = '$beforeDay'");              
            }

            $sel_obj1 = mysqli_fetch_object($selected1);
            $sel_obj2 = mysqli_fetch_object($selected2);


            $sel_obj_name1 = $sel_obj1->name;
            $sel_obj_img1 = $sel_obj1->img;            
            $sel_obj_name2 = $sel_obj2->name;
            $sel_obj_img2 = $sel_obj2->img;
            $sel_obj_genre1 = $sel_obj1->ps_genre;
            $sel_obj_genre2 = $sel_obj2->ps_genre;
            $sel_obj_genre1 = preg_replace("/&amp;/","&", $sel_obj_genre1);
            //$sel_obj_genre2 = preg_replace("&","/&amp;/", $sel_obj_genre2);
            if($provider=="apple"){
              $sel_obj_genre1 = $sel_obj1->it_genre;
              $sel_obj_genre2 = $sel_obj2->it_genre;
              $sel_obj_link1 = $sel_obj1->it_link;
              $sel_obj_link2 = $sel_obj2->it_link;
              $sel_obj_link1 = explode("/app",$sel_obj_link1);
              $sel_obj_link1 = $sel_obj_link1[1];
              $sel_obj_link2 = explode("/app",$sel_obj_link2);
              $sel_obj_link2 = $sel_obj_link2[1];
            }

            $download_free_ps = 'df'.$i.'_ps';
			$download_free_it = 'df'.$i.'_it';
            $chart_free = 'cf'.$i;
            $download_paid_ps = 'dp'.$i.'_ps';
			$download_paid_it = 'dp'.$i.'_it';
            $chart_paid = 'cp'.$i;

            $sel_rank1 = mysqli_fetch_row($selbefore1);
            $sel_rank2 = mysqli_fetch_row($selbefore2);

            $before_rank1 = $sel_rank1[0];
            $before_rank2 = $sel_rank2[0];
            $geupsangseung=false;

            if(!$ranking1){
              $changerank1="same";
              $changeval1=null;
            }
            else{
              if($before_rank1!=true){
                  $changerank1 ="new";
                  $changeval1=null;
                  if($ranking1<90){
                    $up=101 - $ranking1;
                    $geupsangseung=true;
                  }
              }
              elseif($ranking1>$before_rank1){
                  $down = $before_rank1-$ranking1;
                  $changerank1 = "down";
                  $changeval1 = $down;
              }
              elseif($ranking1<$before_rank1){
                  $up = $before_rank1-$ranking1;
                  $changerank1 = "up";
                  $changeval1 = $up;
                  if($up>9){
                    $geupsangseung=true;
                  }
              }
              elseif($ranking1==$before_rank1){
                  $changerank1 = "same";
                  $changeval1 = null;
              }
              if($geupsangseung){
                    $coming_rank[$k][0]=$up;
                    $coming_rank[$k][1]=$sel_obj_name1;
                    $coming_rank[$k][2]=$sel_obj_img1;
                    $coming_rank[$k][3]=$sel_obj_genre1;
                    $k++;
                    $geupsangseung=false;
              }
            }
            if(!$ranking2){
              $changerank2="same";
              $changeval2=null;
            }
            else{
              if($before_rank2!=true){
                  $changerank2 ="new";
                  $changeval2=null;
                  if($ranking2<90){
                    $up=101 - $ranking2;
                    $geupsangseung=true;
                  }
              }
              elseif($ranking2>$before_rank2){
                  $down = $before_rank2-$ranking2;
                  $changerank2 = "down";
                  $changeval2 = $down;
              }
              elseif($ranking2<$before_rank2){
                  $up = $before_rank2-$ranking2;
                  $changerank2 = "up";
                  $changeval2 = $up;
                  if($up>9){
                    $geupsangseung=true;
                  }
              }
              elseif($ranking2==$before_rank2){
                  $changerank2 = "same";
                  $changeval2 = null;
              }
              if($geupsangseung){
                    $coming_rank[$k][0]=$up;
                    $coming_rank[$k][1]=$sel_obj_name2;
                    $coming_rank[$k][2]=$sel_obj_img2;
                    $coming_rank[$k][3]=$sel_obj_genre2;
                    $k++;
                    $geupsangseung=false;
              }
            }


    
            printf("<tr><td>%s</td>", $i+1);
            printf("<td><img src=\"%s\" width=\"50\"></td>
              <td>
                <div class=\"cls_app_name\">
                  <div class=\"app_name\">%s
                  <span class='genre'>- %s</span></div>
                  <div class=\"app_href\">", $sel_obj_img1, $sel_obj_name1, $sel_obj_genre1);

            if($provider=="google"){
                $it_query = mysqli_query($connect, "SELECT it_id, it_link From app_info WHERE ps_id='$free'");
                $it_fetch = mysqli_fetch_object($it_query);
                $it_id = $it_fetch->it_id;
                $it_link = $it_fetch->it_link;
                $it_link = explode("/app",$it_link);
                $it_link = $it_link[1];
                if($it_id!=NULL){
                     printf("<a href=\"https://itunes.apple.com/app%s\" target='_blank'><img onmouseover=\"app_img_on('%s', 2)\" onmouseout=\"app_img_off('%s', 2)\" id=\"%s\" class=\"app_download\" src=\"icons/off-app-store.png\" width=\"20\" height=\"20\"></a>",
                   $it_link, $download_free_it, $download_free_it, $download_free_it);
                }
                printf("<a href=\"https://play.google.com/store/apps/details?id=%s\" target='_blank'><img onmouseover=\"app_img_on('%s', 1)\" onmouseout=\"app_img_off('%s', 1)\" id=\"%s\" class=\"app_download\" src=\"icons/off-google-play.png\" width=\"20\" height=\"20\"></a>",
                 $free, $download_free_ps, $download_free_ps, $download_free_ps);
            }

            else{
                printf("<a href=\"https://itunes.apple.com/app%s\" target='_blank'><img onmouseover=\"app_img_on('%s', 2)\" onmouseout=\"app_img_off('%s', 2)\" id=\"%s\" class=\"app_download\" src=\"icons/off-app-store.png\" width=\"20\" height=\"20\"></a>",
                 $sel_obj_link1, $download_free_it, $download_free_it, $download_free_it);
                $ps_query = mysqli_query($connect, "SELECT ps_id From app_info WHERE it_id='$free'");
                $ps_fetch = mysqli_fetch_object($ps_query);
                $ps_id = $ps_fetch->ps_id;
                if($ps_id!=NULL){
                      printf("<a href=\"https://play.google.com/store/apps/details?id=%s\" target='_blank'><img onmouseover=\"app_img_on('%s', 1)\" onmouseout=\"app_img_off('%s', 1)\" id=\"%s\" class=\"app_download\" src=\"icons/off-google-play.png\" width=\"20\" height=\"20\"></a>",
                 $ps_id, $download_free_ps, $download_free_ps, $download_free_ps);
                }
            }

            printf("<a data-toggle=\"modal\" href=\"show_chart.php?country=$country&sec=free&id=$free&from=$provider\" data-target=\"#chartModal\"><img onmouseover=\"app_img_on('%s', 0)\" onmouseout=\"app_img_off('%s', 0)\" id=\"%s\" class=\"app_download\" src=\"icons/off-chart.png\" width=\"20\" height=\"20\"></a>
                    <span class = '$changerank1'>
                        <img class = '$changerank1' src='icons/%s.png'>%s
                    </span>
                  </div>
                </div>
              </td>", $chart_free, $chart_free, $chart_free, $changerank1, $changeval1);  //free app chart 

             printf("<td><img src=\"%s\" width=\"50\"></td>
              <td>
                <div class=\"cls_app_name\">
                  <div class=\"app_name\">%s
                  <span class='genre'>- %s</span></div>
                  <div class=\"app_href\">", $sel_obj_img2, $sel_obj_name2, $sel_obj_genre2);

            if($provider=="google"){
                $it_query = mysqli_query($connect, "SELECT it_id, it_link From app_info WHERE ps_id='$paid'");
                $it_fetch = mysqli_fetch_object($it_query);
                $it_id = $it_fetch->it_id;
                $it_link = $it_fetch->it_link;
                $it_link = explode("/app",$it_link);
                $it_link = $it_link[1];
                if($it_id!=NULL){
                     printf("<a href=\"https://itunes.apple.com/app%s\" target='_blank'><img onmouseover=\"app_img_on('%s', 2)\" onmouseout=\"app_img_off('%s', 2)\" id=\"%s\" class=\"app_download\" src=\"icons/off-app-store.png\" width=\"20\" height=\"20\"></a>",
                   $it_link, $download_paid, $download_paid, $download_paid);
                }
                printf("<a href=\"https://play.google.com/store/apps/details?id=%s\" target='_blank'><img onmouseover=\"app_img_on('%s', 1)\" onmouseout=\"app_img_off('%s', 1)\" id=\"%s\" class=\"app_download\" src=\"icons/off-google-play.png\" width=\"20\" height=\"20\"></a>",
                 $paid, $download_paid, $download_paid, $download_paid);
            }

            else{
                printf("<a href=\"https://itunes.apple.com/app%s\" target='_blank'><img onmouseover=\"app_img_on('%s', 2)\" onmouseout=\"app_img_off('%s', 2)\" id=\"%s\" class=\"app_download\" src=\"icons/off-app-store.png\" width=\"20\" height=\"20\"></a>",
                 $sel_obj_link2, $download_paid, $download_paid, $download_paid);
                $ps_query = mysqli_query($connect, "SELECT ps_id From app_info WHERE it_id='$free'");
                $ps_fetch = mysqli_fetch_object($ps_query);
                $ps_id = $ps_fetch->ps_id;
                if($ps_id!=NULL){
                      printf("<a href=\"https://play.google.com/store/apps/details?id=%s\" target='_blank'><img onmouseover=\"app_img_on('%s', 1)\" onmouseout=\"app_img_off('%s', 1)\" id=\"%s\" class=\"app_download\" src=\"icons/off-google-play.png\" width=\"20\" height=\"20\"></a>",
                 $ps_id, $download_paid, $download_paid, $download_paid);
                }
            }
            
            printf("<a href = \"show_chart.php?country=$country&sec=paid&id=$paid&from=$provider\" target='_blank'><img onmouseover=\"app_img_on('%s', 0)\" onmouseout=\"app_img_off('%s', 0)\" id=\"%s\" class=\"app_download\" src=\"icons/off-chart.png\" width=\"20\" height=\"20\"></a>
                     <span class = '$changerank2'>
                        <img class = '$changerank2' src='icons/%s.png'>%s
                     </span>
                 </div>
                </div>
              </td>", $chart_paid, $chart_paid, $chart_paid, $changerank2, $changeval2); 
        }//paid app chart

        if($coming_rank){
          printf("<div class='trend'>
                  <table border=\"0\">
                  <thead>
                      <td class=\"thead-line\">No</td>
                      <td class=\"thead-line\"></td>
                      <td class=\"thead-line\">급상승</td>
                  </thead>"
            );
          rsort($coming_rank);
          for($n=0; $n<count($coming_rank); $n++){
             $genre = $coming_rank[$n][3];
             $img = $coming_rank[$n][2];
             $name = $coming_rank[$n][1];
             $rank = $coming_rank[$n][0];
             printf("<tr><td>%s</td>", $n+1);
             printf("<td><img src=\"%s\" width=\"50\"></td>
                     <td><div class=\"app_name\">%s
                     <span class='genre'>- %s</span></div>
                     <span class = 'up'>
                          <img class = 'up' src='icons/up.png'>%s
                      </span></td></tr>",$img, $name, $genre, $rank);
          }
          printf("</table></div>");
        }
  }


$i = $_POST['Country']; 
$j = $_POST['provider'];
$k = 0;
$coming_obj[10];
if($i==NULL){
  $i = "ko";
  $j = "google";
}
loadRanking($i, $j);
  
?>
