<?php
	include ('db_con.php');
	$table =  str_replace( "'","", $_GET['country'] ); 
	$id =  str_replace( "'","", $_GET['id'] );	//	iTunes id로 실행할때 정상적으로 실행 불가능한 문제도 수정해야할거 같아요.
	$section = $_GET['sec'];					//  들어올때 iTunes id인지 판단하는 분기를 만들거나 해야할듯.
	if($id)
	$ps_result = mysqli_query($connect,"SELECT rank, day From $table WHERE id_ps_".$section."='$id' order by day");
	$it_query = mysqli_query($connect, "SELECT it_id From app_info WHERE ps_id='$id'");
	if($it_query){
		$it_fetch = mysqli_fetch_row($it_query);
		$it_id = $it_fetch[0]; 		//it_id가 안 받아지는거 같습니다. 쿼리 문제가 있는건지 잘 모르겠는데 수정 부탁드립니다.
		echo $it_id;
		$it_result = mysqli_query($connect, "SELECT rank, day From $table WHERE id_it_".$section."='$it_id' order by day");
		
		while ($it_row = mysqli_fetch_array($it_result)){
			$it_data[] = $it_row['rank'];
		}
	}
	else{
		echo "not exist";
		$it_data[]=[];
	}
	while ($row = mysqli_fetch_array($ps_result)) {
	   $ps_data[] = $row['rank'];
	   $day[] = '"'.$row['day'].'"';
	}
	global $day, $it_data, $ps_data;
?>
 <div style="position:absolute; top:60px; left:10px; width:500px; height:300px;">
<canvas id="myChart"></canvas>
<script src="Chart.js"></script>
</div>
<script>
 
//function showChart(value){

	var ctx = document.getElementById("myChart");
	var ctx = document.getElementById("myChart").getContext("2d");
	ctx.canvas.width = 100;
	ctx.canvas.height = 100;
	//var ctx = $("#myChart");
	var myChart = new Chart(ctx, {
	    type: 'line',
	    data: {
	        labels: [<?php echo join($day, ',') ?>],
	        datasets: [{
	            label: 'playstore',
	            data: [<?php echo join($ps_data, ',') ?>],
	            fill: false,
	            lineTension: 0.1,
	            backgroundColor: "rgba(75,192,192,0.4)",
	            borderColor: "rgba(75,192,192,1)",
	            borderCapStyle: 'butt',
	            borderDash: [],
	            borderDashOffset: 0.0,
	            borderJoinStyle: 'miter',
	            pointBorderColor: "rgba(75,192,192,1)",
	            pointBackgroundColor: "#fff",
	            pointBorderWidth: 1,
	            pointHoverRadius: 5,
	            pointHoverBackgroundColor: "rgba(75,192,192,1)",
	            pointHoverBorderColor: "rgba(220,220,220,1)",
	            pointHoverBorderWidth: 2,
	            pointRadius: 3,
	            pointHitRadius: 10,
	            showLine: true,
	        },
	        {
	            label: 'itunes',
	            data: [<?php echo join($it_data, ',') ?>],
	            fill: false,
	            lineTension: 0.1,
	            backgroundColor: "rgba(190,100,100,0.4)",
	            borderColor: "rgba(192,75,100,1)",
	            borderCapStyle: 'butt',
	            borderDash: [],
	            borderDashOffset: 0.0,
	            borderJoinStyle: 'miter',
	            pointBorderColor: "rgba(255,192,192,1)",
	            pointBackgroundColor: "#fff",
	            pointBorderWidth: 1,
	            pointHoverRadius: 5,
	            pointHoverBackgroundColor: "rgba(255,192,192,1)",
	            pointHoverBorderColor: "rgba(220,220,220,1)",
	            pointHoverBorderWidth: 2,
	            pointRadius: 3,
	            pointHitRadius: 10,
	            showLine: true,
	        }]
	    },
	    options: {

	        scales: {
	            yAxes: [{
	                ticks: {
	                	min:1,
	                    reverse: true
	                }
	            }]
	        }
	    }
	});
//}
</script>
