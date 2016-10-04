<?php
include ('db_con.php');

	$result = mysqli_query($connect,"SELECT rank From ko WHERE id_ps_free='com.kakao.story'");

while ($row = mysqli_fetch_array($result)) {
   $data[] = $row['rank'];
}
?>
<canvas id="myChart" width="100" height="100"></canvas>
<script src="Chart.js"></script>
<script>

//function showChart(value){
    var ctx = document.getElementById("myChart");
	var myChart = new Chart(ctx, {
	    type: 'line',
	    data: {
	        labels: ["09/28", "10/04"],
	        datasets: [{
	            label: '# of Votes',
	            data: [<?php echo join($data, ',') ?>],
	            backgroundColor: [
	                'rgba(255, 99, 132, 0.2)',
	                'rgba(54, 162, 235, 0.2)',
	                'rgba(255, 206, 86, 0.2)',
	                'rgba(75, 192, 192, 0.2)',
	                'rgba(153, 102, 255, 0.2)',
	                'rgba(255, 159, 64, 0.2)'
	            ],
	            borderColor: [
	                'rgba(255,99,132,1)',
	                'rgba(54, 162, 235, 1)',
	                'rgba(255, 206, 86, 1)',
	                'rgba(75, 192, 192, 1)',
	                'rgba(153, 102, 255, 1)',
	                'rgba(255, 159, 64, 1)'
	            ],
	            borderWidth: 1
	        }]
	    },
	    options: {
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero:true
	                }
	            }]
	        }
	    }
	});
//}
</script>
