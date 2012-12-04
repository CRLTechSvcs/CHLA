<?php
	$term = $_GET['term'];
	include ("config.php");
		
	$where = " WHERE journal_title LIKE '%" . $term . "%'";
	$select = "SELECT DISTINCT journal_title AS value, journal_id AS id FROM chla_journals" . $where . " ORDER BY journal_title";
	
	$queryResult = @mysqli_query($conn, $select);
	if (!$queryResult) {
		die( mysqli_error($conn) );
	}
	else {
		if (mysqli_num_rows($queryResult) == 0)
		{
			$temp = array();
			$temp['value'] = "";
			$temp['id'] = (int)"0";
			$row_set[] = $temp;
		} else {
			while($row = mysqli_fetch_array($queryResult, MYSQL_ASSOC)) {
				$row['value']=$row['value'];
				$row['id']=(int)$row['id'];
				$row_set[] = $row;//build an array
			}
		}
		echo json_encode($row_set);
	}
	
	mysqli_close($conn);
?>