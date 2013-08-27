<?php 
include 'keizaifunk.php';
/* Author: Joshua Alday
 * Description: This page is still under construction. Will update this later
 */
$con = getDBCon();
$fromCur = $_POST["cstatsFrom"];
$toCur = $_POST["cstatsTo"];
$fromCStat = getCStat($con, $fromCur);
$toCStat = getCStat($con, $toCur);
?>
<html>
<body>
<p>Currently under construction</p>
<p> From: <?php echo $fromCur; ?> - To: <?php echo $toCur; ?></p>
<p> Conversion: <?php conversion($fromCStat, $toCStat);?></p>

</body>
</html>