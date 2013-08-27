<?php
function getDBCon(){
	$con=mysqli_connect("pdb7.awardspace.net","1491219_cstats","As84267139","1491219_cstats");
	if (mysqli_connect_errno($con))
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		return false;
	}
	else return $con;
}

function getCStat($con, $cur){
	$query = "SELECT cstats FROM ".$cur."cstats ORDER BY PID DESC";
	mysqli_query($con, $query) or die('Error, create query failed');
	$recentEntry = mysqli_fetch_array($result);
	return $recentEntry['cstats'];
}

function conversion($fromCStat, $toCStat){
	$from = 1;
	$to = (1/$toCStat)/$fromCStat;
	echo "$from --- $to";
}
?>