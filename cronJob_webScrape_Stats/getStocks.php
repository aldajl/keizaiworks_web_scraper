<?php
$target_urls = array("http://www.nasdaq.com/", "http://www.tse.or.jp/english/market/EREALIDX/def01.html");
include 'getStocksFunctions.php';

$userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';
$conStock=mysqli_connect("pdb7.awardspace.net","1491219_stocks","As84267139","1491219_stocks");

if (mysqli_connect_errno($conStock))
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
else{
	foreach($target_urls as $target_url){
		getStockInfo($conStock, $target_url, $userAgent);
	}
	mysqli_close($conStock);
}