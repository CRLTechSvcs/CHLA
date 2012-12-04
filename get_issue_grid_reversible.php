<?php
	$CRLjournalId = null;
	$CHLAjournalId = null;
	if (isset($_REQUEST['CRLjournalId'])) {
		$CRLjournalId = $_REQUEST['CRLjournalId'];
		$select = 'SELECT * FROM chla_journals WHERE journal_id = "' . $CRLjournalId . '"';
	}
	if (isset($_REQUEST['CHLAjournalId'])) {
		$CHLAjournalId = $_REQUEST['CHLAjournalId'];
		$select = 'SELECT * FROM chla_journals WHERE chla_journal_id = "' . $CHLAjournalId . '"';
	}
	
	$reverse = $_REQUEST['reverse'];
	include ("config.php");
	$queryResult = @mysqli_query($conn, $select);
	if (!$queryResult) {
		die(mysqli_error($conn));
	} else {
		$row = mysqli_fetch_array($queryResult, MYSQL_ASSOC);
		$CRLjournalId = $row['journal_id'];
		$CHLAjournalId = $row['chla_journal_id'];
		$journalTitle = $row['journal_title'];
		$author = $row['author'];
		$placeOfPublication = $row['place_of_publication'];
		$publishedBy = $row['published_by'];
	}
	
	$select1 = 'SELECT issue_id, year, chron_level_1, extra_chron_order, unit_level_1, enum_level_1, unit_level_2, enum_level_2, unit_level_3, enum_level_3, chla_holds FROM chla_issues WHERE journal_id = "' . $CRLjournalId . '"';
	if($reverse == "false") {
		$select1 .= ' ORDER BY year, unit_level_1, enum_level_1, extra_chron_order, unit_level_2, enum_level_2, unit_level_3, enum_level_3 ASC';
	} else {
		$select1 .= ' ORDER BY year DESC, extra_chron_order, unit_level_1, enum_level_1, unit_level_2, enum_level_2, unit_level_3, enum_level_3';
	}
	$queryResult1 = @mysqli_query($conn, $select1);
	$journalArray = array();
	
	if (!$queryResult1) {
		die(mysqli_error($conn));
	} else {
		$tempYear = "";
		$tempFirstLevelString = "";
		$i = 0;
		$j = 0;
		$maxColumns = 0;
		$tempColumns = 0;
		$issueArray = array();
		$yearVolume = array();
		while($row1 = mysqli_fetch_array($queryResult1, MYSQL_ASSOC)) {
			$issueId = $row1['issue_id'];
			$year = $row1['year'];
			$chronLevel1 = $row1['chron_level_1'];
			$firstLevel = $row1['unit_level_1'];
			$firstLevelNum = $row1['enum_level_1'];
			$secondLevel = $row1['unit_level_2'];
			$secondLevelNum = $row1['enum_level_2'];
			$chlaHolds = $row1['chla_holds'];
			$firstLevelString = $firstLevel . " " . $firstLevelNum;
			$secondLevelString = $secondLevel. " " . $secondLevelNum;
			if (($tempYear <> $year) || ($tempFirstLevelString <> $firstLevelString)) {
				if (count($yearVolume) <> 0) {
					array_push($issueArray, $yearVolume);
					$yearVolume = array();
				}
				$yearVolume[0] = $year;
				$yearVolume[1] = $firstLevelString;
				$yearVolume[2] = $issueId;
				$yearVolume[3] = $chronLevel1;
				$yearVolume[4] = $secondLevelString;
				$yearVolume[5] = $chlaHolds;
				if ($tempYear <> $year)
					$tempYear = $year;
				if ($tempFirstLevelString <> $firstLevelString)
					$tempFirstLevelString = $firstLevelString;
			} else {
				array_push($yearVolume, $issueId);
				array_push($yearVolume, $chronLevel1);
				array_push($yearVolume, $secondLevelString);
				array_push($yearVolume, $chlaHolds);
			}
			$tempColumns = (count($yearVolume)-2)/2;
			if ($tempColumns > $maxColumns)
				$maxColumns = $tempColumns;
		}
		array_push($issueArray, $yearVolume);
	}
	
	//Get issue counts
	$selectAllCount = 'SELECT count(issue_id) FROM chla_issues WHERE journal_id = "' . $CRLjournalId . '"';
	$queryResultAllCount = @mysqli_query($conn, $selectAllCount);
	if (!$queryResultAllCount) {
		die(mysqli_error($conn));
	} else {
		$rowAllCount = mysqli_fetch_array($queryResultAllCount, MYSQL_ASSOC);
		$allIssuesCount = $rowAllCount['count(issue_id)'];
	}
	$selectHoldCount = 'SELECT count(issue_id) FROM chla_issues WHERE journal_id = "' . $CRLjournalId . '" AND chla_holds = "yes"';
	$queryResultHoldCount = @mysqli_query($conn, $selectHoldCount);
	if (!$queryResultHoldCount) {
		die(mysqli_error($conn));
	} else {
		$rowHoldCount = mysqli_fetch_array($queryResultHoldCount, MYSQL_ASSOC);
		$holdIssuesCount = $rowHoldCount['count(issue_id)'];
	}
	
	$selectWantCount = 'SELECT count(issue_id) FROM chla_issues WHERE journal_id = "' . $CRLjournalId . '" AND chla_holds = "no"';
	$queryResultWantCount = @mysqli_query($conn, $selectWantCount);
	if (!$queryResultWantCount) {
		die(mysqli_error($conn));
	} else {
		$rowWantCount = mysqli_fetch_array($queryResultWantCount, MYSQL_ASSOC);
		$wantIssuesCount = $rowWantCount['count(issue_id)'];
	}
	
	$selectTBDCount = 'SELECT count(issue_id) FROM chla_issues WHERE journal_id = "' . $CRLjournalId . '" AND chla_holds = "unknown"';
	$queryResultTBDCount = @mysqli_query($conn, $selectTBDCount);
	if (!$queryResultTBDCount) {
		die(mysqli_error($conn));
	} else {
		$rowTBDCount = mysqli_fetch_array($queryResultTBDCount, MYSQL_ASSOC);
		$TBDIssuesCount = $rowTBDCount['count(issue_id)'];
	}
	
	$json = array("crlJournalId" => $CRLjournalId, "chlaJournalId" => $CHLAjournalId, "journalTitle" => $journalTitle, "author" => $author, "placeOfPublication" => $placeOfPublication, "publishedBy" => $publishedBy, "maxColumns" => $maxColumns, "issues" => $issueArray, "allIssuesCount" => $allIssuesCount, "holdIssuesCount" => $holdIssuesCount, "wantIssuesCount" => $wantIssuesCount, "TBDIssuesCount" => $TBDIssuesCount);
	$encoded = json_encode($json);
	die($encoded);
	
	mysqli_close($conn);
?>