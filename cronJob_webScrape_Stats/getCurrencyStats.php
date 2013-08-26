<?php
function checkTimestampID($con, $timestampID){
	$query = "SELECT timestampID FROM CStatsInfo ORDER BY PID DESC";
	$result = mysqli_query($con, $query);
	$recentEntry = mysqli_fetch_array($result);
	if($timestampID == $recentEntry['timestampID']){
		return true;
	}
	else return false;
}

function updateCStatsInfo($con, $timestampID){
	$tDate = date(DATE_ATOM);
	$query = "INSERT INTO CStatsInfo (timestampID, timestamp) VALUES ('$timestampID', '$tDate')";
	mysqli_query($con, $query) or die('Error, insert query failed');
}

function storeCStats($con, $timestampID, $ccrncy, $cStats) {
	$tDate = date(DATE_ATOM);
	$query = "INSERT INTO ".$ccrncy."cstats (timestampID, timestamp, cstats) VALUES ('$timestampID','$tDate','$cStats')";
	mysqli_query($con, $query) or die('Error, create query failed');
}

$target_url = "http://openexchangerates.org/api/latest.json?app_id=184dfd89fd9c493c9501a09088a26b1b";
$userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';
$stats_data = array(
    "AED",
    "AFN",
    "ALL",
    "AMD",
    "ANG",
    "AOA",
    "ARS",
    "AUD",
    "AWG",
    "AZN",
    "BAM",
    "BBD",
    "BDT",
    "BGN",
    "BHD",
    "BIF",
    "BMD",
    "BND",
    "BOB",
    "BRL",
    "BSD",
    "BTC",
    "BTN",
    "BWP",
    "BYR",
    "BZD",
    "CAD",
    "CDF",
    "CHF",
    "CLF",
    "CLP",
    "CNY",
    "COP",
    "CRC",
    "CUP",
    "CVE",
    "CZK",
    "DJF",
    "DKK",
    "DOP",
    "DZD",
    "EEK",
    "EGP",
    "ETB",
    "EUR",
    "FJD",
    "FKP",
    "GBP",
    "GEL",
    "GHS",
    "GIP",
    "GMD",
    "GNF",
    "GTQ",
    "GYD",
    "HKD",
    "HNL",
    "HRK",
    "HTG",
    "HUF",
    "IDR",
    "ILS",
    "INR",
    "IQD",
    "IRR",
    "ISK",
    "JEP",
    "JMD",
    "JOD",
    "JPY",
    "KES",
    "KGS",
    "KHR",
    "KMF",
    "KPW",
    "KRW",
    "KWD",
    "KYD",
    "KZT",
    "LAK",
    "LBP",
    "LKR",
    "LRD",
    "LSL",
    "LTL",
    "LVL",
    "LYD",
    "MAD",
    "MDL",
    "MGA",
    "MKD",
    "MMK",
    "MNT",
    "MOP",
    "MRO",
    "MTL",
    "MUR",
    "MVR",
    "MWK",
    "MXN",
    "MYR",
    "MZN",
    "NAD",
    "NGN",
    "NIO",
    "NOK",
    "NPR",
    "NZD",
    "OMR",
    "PAB",
    "PEN",
    "PGK",
    "PHP",
    "PKR",
    "PLN",
    "PYG",
    "QAR",
    "RON",
    "RSD",
    "RUB",
    "RWF",
    "SAR",
    "SBD",
    "SCR",
    "SDG",
    "SEK",
    "SGD",
    "SHP",
    "SLL",
    "SOS",
    "SRD",
    "STD",
    "SVC",
    "SYP",
    "SZL",
    "THB",
    "TJS",
    "TMT",
    "TND",
    "TOP",
    "TRY",
    "TTD",
    "TWD",
    "TZS",
    "UAH",
    "UGX",
    "USD",
    "UYU",
    "UZS",
    "VEF",
    "VND",
    "VUV",
    "WST",
    "XAF",
    "XAG",
    "XAU",
    "XCD",
    "XDR",
    "XOF",
    "XPF",
    "YER",
    "ZAR",
    "ZMK",
    "ZMW",
    "ZWL");

$con=mysqli_connect("pdb7.awardspace.net","1491219_cstats","As84267139","1491219_cstats");

if (mysqli_connect_errno($con))
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
else{
	// make the cURL request to $target_url
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	curl_setopt($ch, CURLOPT_URL,$target_url);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	
	$html= curl_exec($ch);
	if (!$html) {
		echo "<br />cURL error number:" .curl_errno($ch);
		echo "<br />cURL error:" . curl_error($ch);
		exit;
	}
	
	//this is currently incomplete, will be finished at a later date
	$begPos = strpos($html, "timestamp");
	$midPos = strpos($html, ' ', $begPos);
	$endPos = strpos($html, ',', $begPos);
	$timestampID = (int) substr($html, $midPos, ($endPos - $midPos));
	
	if(!checkTimestampID($con, $timestampID)){
		updateCStatsInfo($con, $timestampID);
		
		foreach ($stats_data as $data){
			$begPos = strpos($html, $data);
			$midPos = strpos($html, ' ', $begPos);
			if(strpos($html, ',', $begPos) == False){
				$endPos = strpos($html, '}', $begPos);
			}
			else $endPos = strpos($html, ',', $begPos);
			
			$cStats = (float) substr($html, $midPos, ($endPos - $midPos));
			storeCStats($con, $timestampID, $data, $cStats);
		}	
	}
	mysqli_close($con);
}