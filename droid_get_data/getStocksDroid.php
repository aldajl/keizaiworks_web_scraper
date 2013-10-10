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

function getData($conStocks, $query){
	$sendData = mysqli_query($conStocks, $query) or die('Error, query failed '.mysqli_error($conStocks));
	return $sendData;
}

$nasDaqQ = "(SELECT stockName, stockFrom, stockParent, stockStats, statDiff, timestamp FROM nasdaqStats ORDER by PID DESC LIMIT 9)";
$topixQ = "(SELECT stockName, stockFrom, stockParent, stockClose AS stockStats, stockChange as statDiff, timestamp FROM tokyoStats ORDER by PID DESC LIMIT 1)";
$query = $nasDaqQ." UNION ".$topixQ;

$conStocks = getDBCon("1491219_stocks");
$stockData = getData($conStocks, $query);
$output = array();
while($data = mysqli_fetch_assoc($stockData)){
	$output[] = $data;
}
print json_encode($output);
?>