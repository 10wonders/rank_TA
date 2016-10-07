<meta name = 'viewport' charset='utf-8'>
<table>
	<thead>
		<td>No</td>
        <td>  </td>
		<td>Image</td>
		<td>Name</td>

	</thead>

<?php

		include ('db_con.php');
        $table = 'us';
        $date = date('Y-m-d');
        //$date = strtotime($date);
        //date("Y-m-d",strtotime(str_replace('/','-',$date)));//오늘 날짜

        $result = mysqli_query($connect, "SELECT con.rank, con.id_ps_free, info.img, info.name FROM app_info as info, $table as con where con.id_ps_free = info.ps_id and con.day = '$date' order by con.rank");
        
        $row_num = mysqli_num_rows($result);



        echo $row_num;
        
        for($i = 0; $i<$row_num; $i++){
        	$row = mysqli_fetch_object($result);
            $rank = $row->rank;
            $id_ps_free = $row->id_ps_free;
            $img = $row->img;
        	$name = $row->name;
       		
            $res = mysqli_query($connect, "SELECT rank FROM $table WHERE id_ps_free = '$id_ps_free' and day = '2016-09-28'");
            $row_no = mysqli_num_rows($res);
            $row = mysqli_fetch_row($res);
            $before = $row[0];

            if($before!=true){
                $delta="new";
            }
            elseif($rank>$before){
                $up = $rank-$before;
                $delta = $up;
            }
            elseif($rank<$before){
                $down = $rank-$before;
                $delta = $down;
            }
            elseif($rank==$before){
                $delta = "-";
            }



       		//$ps_id = $row->ps_id;
       		//$it_id = $row->it_id;

        	printf("<tr><td>%s</td>", $rank);
            printf("<td>%s</td>", $delta);
        	printf("<td><img src=\"%s\" width=\"50\"></td><td>%s</td>", $img, $name);         	
            printf("<td>%s</td><td>%s</td>
                <td><form method='post' action='show_chart.php'>
                <button type='submit'>Chart</button></form></td></tr>",$ps_id, $it_id);
        }
		mysqli_close($connect);
    
?>
</table>
