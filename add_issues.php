	<?php
	$journalId = $_REQUEST['journalId'];
	$yearArray = $_REQUEST['year'];
	$extraChronArray = $_REQUEST['extraChron'];
	$extraChronOrderArray = $_REQUEST['extraChronOrder'];
	$firstLevelArray = $_REQUEST['firstLevel'];
	$firstLevelNumArray = $_REQUEST['firstLevelNum'];
	$secondLevelArray = $_REQUEST['secondLevel'];
	$secondLevelNumArray = $_REQUEST['secondLevelNum'];
	$thirdLevelArray = $_REQUEST['thirdLevel'];
	$thirdLevelNumArray = $_REQUEST['thirdLevelNum'];
	$fullIssueDescriptionArray = $_REQUEST['fullIssueDescription'];
	$numPagesArray = $_REQUEST['numPages'];
	$uploadedIssueCount = 0;
	$currentYear = null;
	include ("config.php");
	
	for ($i = 0; $i < count($yearArray); $i++) {
		$tempYear = $yearArray[$i];
		$tempExtraChron = $extraChronArray[$i];
		if ($tempYear == $currentYear) {
			$tempExtraChronOrder++;
		} else {
			$tempExtraChronOrder = 0;
		}
		$tempFullIssueDescription = $fullIssueDescriptionArray[$i];
		$tempNumPages = $numPagesArray[$i];
		$tempFirstLevel = $firstLevelArray[$i];
		$tempFirstLevelNum = $firstLevelNumArray[$i];
		$tempSecondLevel = $secondLevelArray[$i];
		$tempSecondLevelNum = $secondLevelNumArray[$i];
		$tempThirdLevel = $thirdLevelArray[$i];
		$tempThirdLevelNum = $thirdLevelNumArray[$i];
		
		$insert = 'INSERT INTO chla_issues (journal_id, year, chron_level_1, extra_chron_order, unit_level_1, enum_level_1, unit_level_2, enum_level_2, unit_level_3, enum_level_3,full_issue_description, number_of_pages) VALUES ("' . $journalId . '", "' . $tempYear . '", "' . $tempExtraChron . '", "' . $tempExtraChronOrder . '", "' . $tempFirstLevel . '", "' . $tempFirstLevelNum . '", "' . $tempSecondLevel . '", "' . $tempSecondLevelNum . '", "' . $tempThirdLevel . '", "' . $tempThirdLevelNum . '", "' . $tempFullIssueDescription . '", "' . $tempNumPages . '")';
		$insertResult = @mysqli_query($conn, $insert);
		if (!$insertResult) {
			die(mysqli_error($conn));
		} else {
			if (mysqli_affected_rows($conn) == 1) {
				$uploadedIssueCount++;
			}
		}
		$currentYear = $tempYear;
	}
	
	echo $uploadedIssueCount;
	mysqli_close($conn);
	
	
	/*
	$firstLevelNumberArray = $_REQUEST['firstLevelNum'];
	$secondLevelArray = $_REQUEST['secondLevel'];
	$secondLevelNumberArray = $_REQUEST['secondLevelNum'];
	$tempYear = $tempFirstLevel = $tempFirstLevelNum = $tempSecondLevel = $tempSecondLevelNum = "";
	$hyphenPosition = null;
	$slashPosition = null;
	
	for ($i = 0; $i < count($yearArray); $i++) {
		$padLength = 0;
		$tempYear = $yearArray[$i];
		$tempFirstLevel = $firstLevelArray[$i];
		$tempFirstLevelNum = $firstLevelNumberArray[$i];
		$hyphenPosition = strpos($tempFirstLevelNum, "-");
		$slashPosition = strpos($tempFirstLevelNum, "/");
		if (($slashPosition === false) && ($hyphenPosition === false)) {
			if (strlen($tempFirstLevelNum) == 1) {
				$padLength = 3;
			} else if (strlen($tempFirstLevelNum) == 2) {
				$padLength = 2;
			} else if (strlen($tempFirstLevelNum) == 3) {
				$padLength = 1;
			}
			//echo "padLength = " . $padLength . "\n";
		}
		else {
			if ($slashPosition > 0) {
				$splitString = explode('/', $tempFirstLevelNum);
			} else if ($hyphenPosition > 0) {
				$splitString = explode('-', $tempFirstLevelNum);
			}
			//echo "splitString = " . $splitString . "\n";
			$stringToPad = $splitString[0];
			//echo "stringToPad = " . $stringToPad . "\n";
			if (strlen($stringToPad) == 1) {
				$padLength = 3;
			} else if (strlen($stringToPad) == 2) {
				$padLength = 2;
			} else if (strlen($stringToPad) == 3) {
				$padLength = 1;
			}
			//echo $stringToPad . " " . strlen($stringToPad) . " " . $padLength . " " . "\n";
		}
		$tempFirstLevelNum = str_pad($tempFirstLevelNum, strlen($tempFirstLevelNum) + $padLength, " ", STR_PAD_LEFT);
		$hyphenPosition = null;
		$slashPosition = null;
		*/
		/*
		$tempSecondLevel = $secondLevelArray[$i];
		$tempSecondLevel = str_replace("&#039;", "'", $tempSecondLevel);
		$tempSecondLevelNum = $secondLevelNumberArray[$i];
		$tempSecondLevelNum = str_replace("&#039;", "'", $tempSecondLevelNum);
		$hyphenPosition = strpos($tempSecondLevelNum, "-");
		$slashPosition = strpos($tempSecondLevelNum, "/");
		if (($slashPosition === false) && ($hyphenPosition === false)) {
			if (strlen($tempSecondLevelNum) == 1) {
				$padLength = 3;
			} else if (strlen($tempSecondLevelNum) == 2) {
				$padLength = 2;
			} else if (strlen($tempSecondLevelNum) == 3) {
				$padLength = 1;
			}
		} else {
			if ($slashPosition > 0)
				$splitString = explode('/', $tempSecondLevelNum);
			else if ($hyphenPosition > 0) {
				$splitString = explode('-', $tempSecondLevelNum);
			}
			$stringToPad = $splitString[0];			
			if (strlen($stringToPad) == 1) {
				$padLength = 3;
			} else if (strlen($stringToPad) == 2) {
				$padLength = 2;
			} else if (strlen($stringToPad) == 3) {
				$padLength = 1;
			}
			//echo $stringToPad . " " . strlen($stringToPad) . " " . $padLength . " " . "\n";
		}
		$tempSecondLevelNum = str_pad($tempSecondLevelNum, strlen($tempSecondLevelNum) + $padLength, " ", STR_PAD_LEFT);
		$insert = 'INSERT INTO jstor_issues (journal_id, year, first_level, first_level_number, second_level, second_level_number) VALUES ("' . $journalId . '", "' . $tempYear . '", "' . $tempFirstLevel . '", "' . $tempFirstLevelNum . '", "' . $tempSecondLevel . '", "' . $tempSecondLevelNum . '")';		
		$queryResult1 = @mysqli_query($conn, $insert);
		if (!$queryResult1) {
			die(mysqli_error($conn));
		} else {
			if (mysqli_affected_rows() == 1) {
				$uploadedIssueCount++;
			}
		}
	}
	if ($uploadedIssueCount == 1) {
		echo "1 issue was uploaded.";
	} else if ($uploadedIssueCount > 1) {
		echo $uploadedIssueCount . " issues were uploaded.";
	} else if ($uploadedIssueCount == 0) {
		echo "No issues were uploaded.";
	}
	mysqli_close($conn);
	*/
?>