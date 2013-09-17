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

function getQueryCol($data){
	$queryCol = array_keys($data);
	$result = "";
	foreach($queryCol as $col){
		$result .= $col.", ";
	}
	return $result;
}

function getQueryValues($data){
	$result = "";
	foreach($data as $queryVal){
		$result .= "'$queryVal', ";
	}
	return $result;
}

function storeStock($conStock, $data, $tableName, $stockFrom, $stockParent){
	$tDate = date(DATE_ATOM);
	$query = "INSERT INTO ".$tableName." (".getQueryCol($data)."stockFrom, stockParent, timestamp) VALUES (".getQueryValues($data)."'$stockFrom','$stockParent','$tDate')";

	mysqli_query($conStock, $query) or die('Error, insert query failed for '.mysqli_error($conStock));
}

function __nasdaqReturnData($html, $begRef, $dataRef, $dataRefalt){
	$keys = array('stockName', 'stockStats', 'statDiff', 'statDiffPer', 'volume', 'beforeVal', 'afterVal');
	$sendData = array_fill(0, 9, array_fill_keys($keys, ''));

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

function stockReturnData($stockName, $html, $begRef, $dataRefL, $dataRefR, $keys, $stockNum, $dataNum){
	$sendData = array_fill(0, $stockNum, array_fill_keys($keys, ''));
	$startPos = strpos($html, $begRef) + 1;
	
	foreach($sendData as &$sendDati){
		$sendDati["stockName"] = $stockName;
		for($i=0;$i<$dataNum;$i++){
			$begPos = strpos($html, $dataRefL, $startPos);
			$endPos = strpos($html, $dataRefR, $begPos);
			$data = substr($html, $begPos+strlen($dataRefL), $endPos - $begPos - strlen($dataRefL));
			$sendDati[$keys[$i+1]] = $data;
			$startPos = $endPos;
		}
	}
	
	return $sendData;
}

function getStockInfo($conStock, $target, $userAgent){
	$target_urls = array("http://www.nasdaq.com/", "http://www.tse.or.jp/english/market/EREALIDX/def01.html");
	$html = curlWeb($target, $userAgent);
	$data;
        $tableName;
	$stockFrom;
	if (!$html) {
		echo "<br />cURL error number:" .curl_errno($ch);
		echo "<br />cURL error:" . curl_error($ch);
		exit;
	}
	switch($target){
		case $target_urls[0]:
			$data = __nasdaqReturnData($html, "nasdaqHomeIndexChart.storeIndexInfo", "(", ")");
			$tableName = "nasdaqStats";
			$stockFrom = "national";
			$stockParent = "nasdaq";
			break;
		case $target_urls[1]:
			$keys = array('stockName', 'stockOpen', 'stockHigh', 'stockLow', 'stockClose', 'stockChange');
			$data = stockReturnData("topix", $html, "<td>2013", "<td>", "</td>", $keys, 1, 5);
			$tableName = "tokyoStats";
			$stockFrom = "japan";
			$stockParent = "topix";
			break;
	}
        
	if (count($data) == count($data, COUNT_RECURSIVE)){
		storeStock($conStock, $data, $tableName, $stockFrom, $stockParent);
	}
	else{
		foreach($data as $dati){
			storeStock($conStock, $dati, $tableName, $stockFrom, $stockParent);
		}
	}
}

?>