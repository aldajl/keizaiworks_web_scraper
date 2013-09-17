<?php
function getDBCon($DB){
	$con=mysqli_connect("pdb7.awardspace.net",$DB,"As84267139",$DB);
	if (mysqli_connect_errno($con))
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		return false;
	}
	else{
		return $con;
	}
}

function getCStat($con, $cur){
	$query = "SELECT cstats FROM ".$cur."cstats ORDER BY PID DESC LIMIT 1";
	$result = mysqli_query($con, $query) or die('Error, create query failed');
	$recentEntry = mysqli_fetch_array($result);
	return $recentEntry['cstats'];
}

function conversion($fromCStat, $toCStat){
	$from = 1;
	$to = ($toCStat/$fromCStat);
	if(empty($fromCStat)) {
		$fromCStat = 1;
	}
	if(empty($toCStat)) {
		$toCStat = 1;
	}
	echo "$from --- $to";
}

function getNasDaqData($conStocks){
	$query = "SELECT stockName, stockStats, statDiff, statDiffPer, volume, beforeVal, afterVal, timestamp FROM nasdaqStats ORDER by PID DESC LIMIT 10";
	$sendData = mysqli_query($conStocks, $query) or die('Error, nasdaq query failed');
	return $sendData;
}
?>