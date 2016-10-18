<!DOCTYPE>

<?php
    //include ('def_db.php');
    //include ('nav.php');
    //include ('realtime_ranking.php');
?>
<html>
<head>
<meta charset="utf-8">
   <title>Top Ranking Chart</title>
<style type="text/css">
   h1{text-align: center;}
   #chart {
    margin :40px auto;
    text-align: center;;
  }
   table{margin :20px auto; float:right;}
   #table_header{ 
    border-top: 3px solid #555555;
    border-bottom: 2px solid #aaaaaa;
  
  }
  td{border: 0px;}
  tr:nth-child(2n){
    background-color: #eeeeee;
  }
  tr{
    height : 65px;
  }
  thead{
    text-align: center;
  }
  .thead-line{
    border-top :3px solid #666666;
    border-bottom: 2px solid #aaaaaa;
    padding: 20px 0;
  }
  .trend{
    float: left;
  }
  .cls_app_name{
    width: 350px;
    
  }
  .app_href{
    padding-left: 30px;
    
    width: 250px;
    text-align: left;
  
  }
  .app_name{
    padding-bottom: 10px;
    padding-left: 30px;
    font-size: 13px;
    width: 250px;
    text-align: left;
    overflow: hidden;
  
  }
  .app_download{
    margin-left: 10px;
  }
  img.up{
    float : right;
    width : 15px;
    height : 15px;
  }
  .up{
    float : right; 
    color : red;
  }
  img.down{
    float : right;
    width : 15px;
    height : 15px;
  }
  .down{
    float : right; 
    color : blue;
  }
  img.same{
    float : right;
    width : 15px;
    height : 15px;
  }
  .same{
    float : right; 
    color : black;
  }
  img.new{
    float : right;
    width : 30px;
    height : 30px;
  }
  .new{
    float : right; 
    color : blue;
  }
  #ranking{ 
    margin:0 auto;  
    width:940px; 
  
  }
  #country{  }
  #chart{ margin-top: 50px; }
  #country_list1{  
    margin: 10px 0;
    float: left;
  }
  #country_list2{  
    margin: 10px 0;
    margin-left: 38px;
    float: left;
  }
  
  .country_flag{ 
    margin-left: 6px;
    padding: 10px 0px 5px 0px;
    width: 60px; float: left;
    border-top: 1px solid #666666;
    border-left: 1px solid #666666;
    border-right: 1px solid #666666;
    font-size: 9px;
  }
  .country_flag:hover{
      outline: 2px solid #258DC8;
  }
  .flags{text-align: center;}
  .flag_img{ width : 32px; height : 32px;}
  .country_name{
    text-align: center;
    color: #000000;
  }
  .end_footer{
    clear:both;
  }
</style>
<script type="text/javascript" src="jquery-3.1.0.min.js" charset="utf-8">
</script>
<script type="text/javascript">
  
function func(country){
  var obj = document.getElementById(country).checked = true;
  document.getElementById('myform').submit();
}
function app_img_on(id, which){

  if(which){
    var obj = document.getElementById(id).setAttribute("src", "on-google-play.png");
  }
  else
    document.getElementById(id).setAttribute("src", "on-chart.png");

}
function app_img_off(id, which){

  if(which)
    document.getElementById(id).setAttribute("src", "off-google-play.png");
  else
    document.getElementById(id).setAttribute("src", "off-chart.png");

}

window.onload = function(){

}

</script>
<?php
// Start the session
session_start();
?>
</head>
<body>

<h1> PLAY-STORE / ITUNES 차트</h1>
</div>

    <div id="chart">
      <div>현재시각 : <span id="clock"></span>
      <div class="btn-group" role="group" aria-label="...">
        <form id="myform" method='post' action='realtime_ranking.php'>
        <label for='r1'><input type="radio" id="r1" name="provider" value="google" checked>GOOGLE</label>
        <label for='r2'><input type="radio" id="r2" name="provider" value="apple">APPLE</label><br><br> 
        
        <input type="radio"  id="ko"  name="Country" value="ko" hidden="">
        <input type="radio"  id="us"  name="Country" value="us" hidden="">
        <input type="radio"  id="jp"  name="Country" value="jp" hidden="">
        <input type="radio"  id="cn"  name="Country" value="cn" hidden="">
        <input type="radio"  id="ru"  name="Country" value="ru" hidden="">
        <input type="radio"  id="fr"  name="Country" value="fr" hidden="">
        <input type="radio"  id="de"  name="Country" value="de" hidden="">
        <input type="radio"  id="fi"  name="Country" value="fi" hidden="">
        <input type="radio"  id="es"  name="Country" value="es" hidden="">
        <input type="radio"  id="it"  name="Country" value="it" hidden="">
        <input type="radio"  id="au"  name="Country" value="au" hidden="">
        <input type="radio"  id="ca"  name="Country" value="ca" hidden="">
        <input type="radio"  id="br"  name="Country" value="br" hidden="">
        <input type="radio"  id="tw"  name="Country" value="tw" hidden="">
        <input type="radio"  id="hk"  name="Country" value="hk" hidden="">
        <input type="radio"  id="ru"  name="Country" value="ru" hidden="">
        <input type="radio"  id="dk"  name="Country" value="dk" hidden="">
        <input type="radio"  id="no"  name="Country" value="no" hidden="">
        <input type="radio"  id="nl"  name="Country" value="nl" hidden="">
        <input type="radio"  id="nz"  name="Country" value="nz" hidden="">
        <input type="radio"  id="uk"  name="Country" value="uk" hidden="">
        <input type="radio"  id="tr"  name="Country" value="tr" hidden="">
        <input type="radio"  id="th"  name="Country" value="th" hidden="">    
      </form> 
      </div>   
    </div> 
    <div> 

     <div id="ranking">
      <div id="country">
        <div id="country_list1">
          <a href="#" onclick="func('ko')" >
            <div class="country_flag">
              <div class="flags">
                <img src="flags/south-korea.png" class="flag_img">
              </div>
              <div class="country_name">
                KOREA
              </div>
            </div>
          </a>
          <a href="#" onclick="func('us')" >
            <div class="country_flag">
              <div class="flags">
                <img src="flags/usa.png" class="flag_img">
              </div>
              <div class="country_name">
                USA
              </div>
            </div>
          </a>
          <a href="#" onclick="func('jp')">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/japan.png" class="flag_img">
              </div>
              <div class="country_name">
                JAPAN
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/china.png" class="flag_img">
              </div>
              <div class="country_name">
                CHINA
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/russia.png" class="flag_img">
              </div>
              <div class="country_name">
                RUSSIA
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/france.png" class="flag_img">
              </div>
              <div class="country_name">
                FRANCE
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/germany.png" class="flag_img">
              </div>
              <div class="country_name">
                GERMANY
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/finland.png" class="flag_img">
              </div>
              <div class="country_name">
                FINLAND
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/spain.png" class="flag_img">
              </div>
              <div class="country_name">
                SPAIN
              </div>
            </div>
          </a>
         <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/italy.png" class="flag_img">
              </div>
              <div class="country_name">
                ITALY
              </div>
            </div>
          </a>
         <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/australia.png" class="flag_img">
              </div>
              <div class="country_name">
                AUSTRALIA
              </div>
            </div>
          </a>
         <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/canada.png" class="flag_img">
              </div>
              <div class="country_name">
                CANADA
              </div>
            </div>
          </a>                                                              
        </div>
        <div class="end_footer"></div>
     <div id="country_list2">
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/brazil.png" class="flag_img">
              </div>
              <div class="country_name">
                BRAZIL
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/taiwan.png" class="flag_img">
              </div>
              <div class="country_name">
                TAIWAN
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/hong-kong.png" class="flag_img">
              </div>
              <div class="country_name">
                HONGKONG
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/romania.png" class="flag_img">
              </div>
              <div class="country_name">
                ROMANIA
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/denmark.png" class="flag_img">
              </div>
              <div class="country_name">
                DENMARK
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/norway.png" class="flag_img">
              </div>
              <div class="country_name">
                NORWAY
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/netherlands.png" class="flag_img">
              </div>
              <div class="country_name">
                NETHERLAND
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/new-zealand.png" class="flag_img">
              </div>
              <div class="country_name">
               NEWZEALAND
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/united-kingdom.png" class="flag_img">
              </div>
              <div class="country_name">
                UK
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/turkey.png" class="flag_img">
              </div>
              <div class="country_name">
                TURKEY
              </div>
            </div>
          </a>
          <a href="#">
            <div class="country_flag">
              <div class="flags">
                <img src="flags/thailand.png" class="flag_img">
              </div>
              <div class="country_name">
                THAILAND
              </div>
            </div>
          </a>                                            
        </div>
        <div class="end_footer"></div>
         
      </div>
      <div class="end_footer"></div>
     </div>    

</body>
</html>
