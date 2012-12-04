<?php
	$chlaJournalId = $_REQUEST['chlaJournalId'];
	$journalTitle = $_REQUEST['journalTitle'];
	$author = $_REQUEST['author'];
	$pubPlace = $_REQUEST['pubPlace'];
	$publishedBy = $_REQUEST['publishedBy'];
	date_default_timezone_set('America/Chicago');
	$currentDate = date('Y-m-d H:i:s');
	include ("config.php");
	$insert = 'INSERT INTO chla_journals (chla_journal_id, journal_title, author, place_of_publication, published_by, journal_timestamp) VALUES ("' . $chlaJournalId . '", "' . $journalTitle . '", "' . $author . '", "' . $pubPlace . '", "' . $publishedBy. '", "' . $currentDate . '")';
	$insertResult = @mysqli_query($conn, $insert);
	if (!$insertResult) {
			die(mysqli_error($conn));
	} else {
		$select = "SELECT * FROM chla_journals WHERE chla_journal_id = '" . $chlaJournalId . "'";
		$selectResult = @mysqli_query($conn, $select);
		if (!$selectResult) {
			die(mysqli_error($conn));
		} else {
			$temp = array();
			while($row = mysqli_fetch_array($selectResult, MYSQL_ASSOC)) {
				$innerArray = array();
				$tempJournalId = $row['journal_id'];
				$tempChlaJournalId = $row['chla_journal_id'];
				$tempJournalTitle = $row['journal_title'];
				$tempAuthor = $row['author'];
				$tempPubPlace = $row['place_of_publication'];
				$tempPublishedBy = $row['published_by'];
				$innerArray['journalId'] = $tempJournalId;
				$innerArray['chlaJournalId'] = $tempChlaJournalId;
				$innerArray['journalTitle'] = $tempJournalTitle;
				$innerArray['author'] = $tempAuthor;
				$innerArray['pubPlace'] = $tempPubPlace;
				$innerArray['publishedBy'] = $tempPublishedBy;
				array_push($temp, $innerArray);
			}
			$json = array('journalInfo' => $temp);
			$encoded = json_encode($json);
			die($encoded);
		}
	}
	mysqli_close($conn);
	
	/*
	
	
		
		
			
			while($row = mysqli_fetch_array($queryResult, MYSQL_ASSOC)) {
				$innerArray = array();
				$tempJournalId = $row['journal_id'];
				$tempJournalCode = $row['journal_code'];
				$tempJournalTitle = $row['journal_title'];				
				$tempIssn = $row['issn'];
				$tempPublicationStatus = $row['publication_status'];
				$tempCollectStatus = $row['collect_status'];
				$innerArray["journalId"] = $tempJournalId;
				$innerArray["journalCode"] = $tempJournalCode;
				$innerArray["journalTitle"] = $tempJournalTitle;  //Still whitespace
				$innerArray["publicationStatus"] = $tempPublicationStatus;
				$innerArray["issn"] = $tempIssn;
				$innerArray["collectStatus"] = $tempCollectStatus;
				array_push($temp, $innerArray);
			}
			$json = array("journals" => $temp);
			$encoded = json_encode($json);
			die($encoded);
	

	mysqli_close($conn);
	*/
?>