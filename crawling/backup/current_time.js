 
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
 var timeval = 1;

  function realtimeClock() {/*
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
     */
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
