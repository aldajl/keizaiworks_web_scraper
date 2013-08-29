<?php 
include 'keizaifunk.php';
/* Author: Joshua Alday
 * Description: This page is still under construction. Will update this later
 */
$con=getDBCon("1491219_cstats");
$fromCur = $_POST["cstatsFrom"];
$toCur = $_POST["cstatsTo"];
$fromCStat = getCStat($con, $fromCur);
$toCStat = getCStat($con, $toCur);

$conStocks = getDBCon("1491219_stocks");
$nasDaqData = getNasDaqData($conStocks);
$nasDaqDati = array_fill(0, 7, '');
for($x=6; $x>=0; $x--){
	$nasDaqDati[$x]=$nasDaqData->fetch_array(MYSQLI_ASSOC);
}
?>
<html>
<body>
<p>Currently under construction</p>
<p> From: <?php echo $fromCur; ?> - To: <?php echo $toCur; ?></p>
<p> Conversion: <?php conversion($fromCStat, $toCStat);?></p>
<?php foreach($nasDaqDati as $nasDaqi){
	echo "<p>".$nasDaqi['stockName']." ".$nasDaqi['stockStats']." ".$nasDaqi['statDiff']."</p>";
}
?>
</body>
</html>