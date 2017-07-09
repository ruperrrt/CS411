<?php 
mysql_connect("localhost","sometimesnaive_dalao1","Dalaodalao1");
mysql_select_db('sometimesnaive_my_DB');


$search = $_POST['TITLE'];
$selectoption = $_POST['rankStandard'];

if($search != ''){
	if ($selectoption == 'player') {
		if (preg_match("/^[a-zA-Z]+/", $search)) {
			$sql = "SELECT * FROM player WHERE name LIKE '%$search%' ";
			$result =  mysql_query($sql) or die(mysql_error());
			if (mysql_num_rows($result)==0) {
				echo "<p>No result is found in $selectoption category, please check your input.</p>";
			}
			echo "<table>";
			$first_row = true;
			while ($row = mysql_fetch_assoc($result)) {
					if ($first_row) {
        				$first_row = false;
        				echo '<tr>';
        				foreach($row as $key => $field) {
           					echo '<th>' . htmlspecialchars($key) . '</th>';
        				}
        				echo '</tr>';
        			}
        			echo '<tr>';
					foreach ($row as $key => $field) {	
						echo '<td>' . htmlentities($field).'</td>';	
					}
								
			 	echo "</tr>";
			}
				echo '</table>';
		}
			else{
				echo "Please input English letters!";
			}
	}
	elseif ($selectoption == 'team') {
		if (preg_match("/^[a-zA-Z0-9]+/", $search)) {
			$sql = "SELECT * FROM Teams_Statistics WHERE Team_name LIKE '%$search%' ";
			$result =  mysql_query($sql) or die(mysql_error());
			if (mysql_num_rows($result)==0) {
				echo "<p>No result is found in $selectoption category, please check your input.</p>";
			}
			echo "<table>";
			$first_row = true;
			while ($row = mysql_fetch_assoc($result)) {
					if ($first_row) {
        				$first_row = false;
        				echo '<tr>';
        				foreach($row as $key => $field) {
           					echo '<th>' . htmlspecialchars($key) . '</th>';
        				}
        				echo '</tr>';
        			}
        			echo '<tr>';
					foreach ($row as $key => $field) {	
						echo '<td>' . htmlentities($field).'</td>';	
					}
								
			 	echo "</tr>";
			}
				echo '</table>';
		}
			else{
				echo "Please input English letters or numbers!";
			}		
	}
}
else{
	echo "<p>Please enter a search query</p>";
}