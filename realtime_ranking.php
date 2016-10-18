<?php include ('crawling.php');?>

<?php

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
        $beforeDay = date("Y-m-d", strtotime($day." -7 day"));   
         

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
                    <td class=\"thead-line\">App Free</td>
                    <td class=\"thead-line\"></td>
                    <td class=\"thead-line\">App Paid</td>
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
   
              $selected1 = mysqli_query($connect, "SELECT name, img FROM app_info WHERE $id_feild='$free';");
              $selected2 = mysqli_query($connect, "SELECT name, img FROM app_info WHERE $id_feild='$paid';");
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

              $selected1 = mysqli_query($connect, "SELECT name, img FROM app_info WHERE $id_feild=$free");
              $selected2 = mysqli_query($connect, "SELECT name, img FROM app_info WHERE $id_feild=$paid");
              $selbefore1 = mysqli_query($connect, "SELECT rank FROM $country WHERE id_it_free = '$free' and day = '$beforeDay'");
              $selbefore2 = mysqli_query($connect, "SELECT rank FROM $country WHERE id_it_paid = '$paid' and day = '$beforeDay'");              
            }

            $sel_obj1 = mysqli_fetch_object($selected1);
            $sel_obj2 = mysqli_fetch_object($selected2);


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
                  //$coming_obj[$k]=$sel_obj1;
                  $coming_rank[$k][0]=$up;
                  $coming_rank[$k][1]=$sel_obj1->name;
                  $coming_rank[$k][2]=$sel_obj1->img;
                  $k++;
                }
            }
            elseif($ranking1==$before_rank1){
                $changerank1 = "same";
                $changeval1 = null;
            }

            if($before_rank2!=true){
                $changerank2 ="new";
                $changeval2=null;
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
                  //$coming_obj[$k]=$sel_obj2;
                  $coming_rank[$k][0]=$up;
                  $coming_rank[$k][1]=$sel_obj1->name;
                  $coming_rank[$k][2]=$sel_obj1->img;
                  $k++;
                }
            }
            elseif($ranking2==$before_rank2){
                $changerank2 = "same";
                $changeval2 = null;
            }


    
            printf("<tr><td>%s</td>", $i+1);
            printf("<td><img src=\"%s\" width=\"50\"></td>
              <td>
                <div class=\"cls_app_name\">
                  <div class=\"app_name\">%s</div>
                  <div class=\"app_href\">
                    <a href=\"https://play.google.com/store/apps/details?id=%s\">
                    <img onmouseover=\"app_img_on('%s', 1)\" onmouseout=\"app_img_off('%s', 1)\" id=\"%s\" class=\"app_download\" src=\"off-google-play.png\" width=\"20\" height=\"20\"></a>
                    <a href = \"show_chart.php?country=$country&sec=free&id=$free&from=$provider\" target='_blank'>
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
                     <a href = \"show_chart.php?country=$country&sec=paid&id=$paid&from=$provider\" target='_blank'>
                        <img onmouseover=\"app_img_on('%s', 0)\" onmouseout=\"app_img_off('%s', 0)\" id=\"%s\" class=\"app_download\" src=\"off-chart.png\" width=\"20\" height=\"20\">
                     </a>
                     <span class = '$changerank2'>
                        <img class = '$changerank2' src='%s.png'>%s
                     </span>
                 </div>
                </div>
              </td>", $sel_obj_img2, $sel_obj_name2, $paid, $download_paid,$download_paid,$download_paid, $chart_paid, $chart_paid, $chart_paid, $changerank2, $changeval2);           
        }
        if($coming_rank){
          printf("<div class='trend'>
                  <table border=\"0\">
                  <thead>
                      <td class=\"thead-line\">No</td>
                      <td class=\"thead-line\"></td>
                      <td class=\"thead-line\">Hot trend</td>
                  </thead>"
            );
          rsort($coming_rank);
          for($n=0; $n<count($coming_rank); $n++){
             $img = $coming_rank[$n][2];
             $name = $coming_rank[$n][1];
             $rank = $coming_rank[$n][0];
             printf("<tr><td>%s</td>", $n+1);
             printf("<td><img src=\"%s\" width=\"50\"></td>
                     <td><div class=\"app_name\">%s</div>
                     <span class = 'up'>
                          <img class = 'up' src='up.png'>%s
                      </span></td></tr>",$img, $name, $rank);
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
