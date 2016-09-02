<!DOCTYPE>
<html>
<head>
<meta charset="utf-8">
   <title>Top Ranking Chart</title>
<style type="text/css">
   h1{text-align: center;}
   div{text-align: center;}
   #chart {margin :40px auto;}
   table{font-size : 15px; margin :40px auto;}
   .ps_app{vertical-align: middle;}
   .it_app{vertical-align: middle;}
   th{font-size: 18px;height: 50px;}

</style>
<script type="text/javascript">

  var timeval = 1;

  function click_button(text){
    alert(text);
  }

  function realtimeClock() {
     var d = new Date();
     if(d.getSeconds()%5 == 0){
        if(timeval){
           document.getElementById('ps0').innerHTML = '텐원더스';
           timeval = 0;
        }
        else{
           document.getElementById('ps0').innerHTML = '카카오톡';
           timeval = 1;
        }
     }
    document.getElementById('clock').innerHTML = getTimeStamp();
    setTimeout("realtimeClock()", 1000);
  }


  function getTimeStamp() { // 24시간제
    var d = new Date();

    var s =
      leadingZeros(d.getFullYear(), 4) + '-' +
      leadingZeros(d.getMonth() + 1, 2) + '-' +
      leadingZeros(d.getDate(), 2) + ' ' +

      leadingZeros(d.getHours(), 2) + ':' +
      leadingZeros(d.getMinutes(), 2) + ':' +
      leadingZeros(d.getSeconds(), 2);

    return s;
  }


  function leadingZeros(n, digits) {
    var zero = '';
    n = n.toString();

    if (n.length < digits) {
      for (i = 0; i < digits - n.length; i++)
        zero += '0';
    }
    return zero + n;
  }
  window.onload = realtimeClock;
</script>
</head>

<body>

<h1> PLAY-STORE && ITUNES TOP RANKING CHART</h1>

<div>현재시각 : <span id="clock"></span>
</div>

<div id="chart">
<input type="button" name="ko" value="KOREA" onclick="click_button('KOREA')">
<input type="button" name="en" value="USA"onclick="click_button('KOREA')">
<input type="button" name="jp" value="JAPAN"onclick="click_button('KOREA')">

   <table border="1">
      <tr>
         <th>RANK</th>
         <th>PLAY STORE</th>
         <th>ITUNES</th>

      </tr>
   <?php
   function getHTML(){

      //play-store Crawling Code
      $ps_url="https://play.google.com/store/apps/collection/topselling_free?hl=ko";
      $ps_ch = curl_init($ps_url);
      curl_setopt($ps_ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ps_ch, CURLOPT_RANGE, '0-100');
      curl_setopt($ps_ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ps_ch, CURLOPT_SSL_VERIFYPEER, 0);
      $ps_content = curl_exec($ps_ch);  
      $ps_img_content= $ps_content;
      curl_close($ps_ch);

      //itunes Crawling Code
      $it_url = "http://www.apple.com/kr/itunes/charts/free-apps/";
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
      


      /*------output-----*/

      // Crawling Image;
      preg_match_all("/<img.*?src=\"(.*?)\".*?>/", $ps_content, $ps_img_match);
      preg_match_all("/<img\ssrc=\"(.*?)\".*?>/", $it_img_content, $it_img_match);
      
      // Crawling Ranking
      preg_match_all("/<h3>+<a.*?>(.*?)<\/a>/",$it_content, $it_match);
      preg_match_all("/<a\sclass=\"title\".*?title=\"(.*?)\".*?>/", $ps_content, $ps_match);
      for($i = 0; $i<100; $i++){
         if(strstr($it_match[1][$i], "Prisma"))
               $it_match[1][$i] = "Prisma";

            printf("
               <tr>
                  <th>%d</th>
                  <td><img src=\"%s\" width=\"50\"/><span id=\"ps$i\" class=\"ps_app\">%s</span></td>
                  <td><img src=\"%s\" width=\"50\"/><span id=\"it$i\" class=\"\it_app\"'>%s</span></td>
               </tr>", $i+1,$ps_img_match[1][$i],$ps_match[1][$i], $it_img_match[1][$i], $it_match[1][$i]
               );
      }

   }
   getHTML();
   ?>
   </table>
</div>
</body>
</html>