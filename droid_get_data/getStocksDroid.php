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

function getNasDaqData($conStocks){
	$query = "SELECT stockName, from, parent, stockStats, statDiff, statDiffPer, volume, beforeVal, afterVal, timestamp FROM nasdaqStats ORDER by PID DESC LIMIT 7";
	$sendData = mysqli_query($conStocks, $query) or die('Error, nasdaq query failed');
	return $sendData;
}

$conStocks = getDBCon("1491219_stocks");
$stockData = getNasDaqData($conStocks);
$output = array();
while($data = mysqli_fetch_assoc($stockData)){
	$output[] = $data;
}
print json_encode($output);
?>