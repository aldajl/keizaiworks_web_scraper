<?php

function curlWeb($target_url, $userAgent){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	curl_setopt($ch, CURLOPT_URL,$target_url);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	return curl_exec($ch);
}

function storeNasdaq($conStock, $nasDaqData){
	$tDate = date(DATE_ATOM);
	$query = "INSERT INTO nasdaqStats (stockName, from, parent, stockStats, statDiff, statDiffPer, volume, beforeVal, afterVal, timestamp) 
				VALUES ('".$nasDaqData['name']."',national,nasdaq,'".$nasDaqData['stockStats']."','".$nasDaqData['statDiff']."','".$nasDaqData['statDiffPer']."','".$nasDaqData['volume']."',
						'".$nasDaqData['beforeVal']."','".$nasDaqData['afterVal']."', '$tDate')";
	
	mysqli_query($conStock, $query) or die('Error, insert query failed for '.$nasDaqData['name']);
}

function __nasdaqReturnData($html, $begRef, $dataRef, $dataRefalt){
	$keys = array('name', 'stockStats', 'statDiff', 'statDiffPer', 'volume', 'beforeVal', 'afterVal');
	$sendData = array_fill(0, 7, array_fill_keys($keys, ''));
	
	$begPos = 0;
	foreach ($sendData as &$sendDati){
		$begPos = strpos($html, $begRef, $begPos);
		$dataPos = strpos($html, $dataRef, $begPos);
		$dataEndPos = strpos($html, dataRefalt, $dataPos);
		$data = substr($html, $dataPos+1, $dataEndPos - $dataPos - 1);
		$results = explode(',', str_replace('"', '', $data));
		
		$count = 0;
		foreach ($sendDati as &$dati){
			$dati = $results[$count];
			$count++;
		}
		$begPos += 1;
	}
	
	return $sendData;
}

function getNasdaqInfo($conStock, $html, $userAgent){
	$nasDaqData = __nasdaqReturnData($html, "nasdaqHomeIndexChart.storeIndexInfo", "(", ")");
	foreach($nasDaqData as $nasDaqDati){
		storeNasdaq($conStock, $nasDaqDati);
	}
}

$target_url = "http://www.nasdaq.com/";
$userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';
$conStock=mysqli_connect("pdb7.awardspace.net","1491219_stocks","As84267139","1491219_stocks");

if (mysqli_connect_errno($conStock))
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
else{
	$html = curlWeb($target_url, $userAgent);
	if (!$html) {
		echo "<br />cURL error number:" .curl_errno($ch);
		echo "<br />cURL error:" . curl_error($ch);
		exit;
	}
	
	getNasdaqInfo($conStock, $html, $userAgent);
	mysqli_close($conStock);
}