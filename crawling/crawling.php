<!DOCTYPE>

<?php
    include ('def_db.php');
?>
<html>
<head>
<meta charset="utf-8">
   <title>Top Ranking Chart</title>
<style type="text/css">
   h1{text-align: center;}
   div{text-align: center;}
   #chart {margin :40px auto;}
   table{font-size : 15px; margin :40px auto;}
   .tb1{display: none;}
   .tb2{display: none;}

</style>
<script type="text/javascript" src="jquery-3.1.0.min.js" charset="utf-8">
  $(function(){
    $('.btn0').bind('click', function(e){
      $('.tb0').show();
      $('.tb1').hide();
      $('.tb2').hide();
    });
    $('.btn1').bind('click', function(e){
      $('.tb0').hide();
      $('.tb1').show();
      $('.tb2').hide();
    });
    $('.btn2').bind('click', function(e){
      $('.tb0').hide();
      $('.tb1').hide();
      $('.tb2').show();
    });
  });
</script>
<script type="text/javascript" src="current_time.js" charset="utf-8"></script>

</head>

<body>

<h1> PLAY-STORE && ITUNES TOP RANKING CHART</h1>
<div><h2>현재시각 : <span id="clock"></span></h2>
</div>

<div id="chart">
<input type="button" class="btn0" value="KOREA">
<input type="button" class="btn1" value="USA">
<input type="button" class="btn2" value="JAPAN">

<?php

    for($i = 0; $i<3; $i++){
?>
   <table border="1" class=<?php echo"tb".$i?>>
      <tr>
         <th class="rank">RANK</th>
         <th class="ps_app">PLAY STORE</th>
         <th class="it_app">ITUNES</th>

      </tr>
   <?php

      /** 
        $country 
        
        0 : 한국 
        1 : 미국
        2 : 일본   
      
      **/

      //$cnt = insert_into_db(2);
      select_from_db(100, $i);
   ?>
   </table>
   <?php
    }
   ?>

</div>
</body>
</html>