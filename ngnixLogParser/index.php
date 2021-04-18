<?php
$logs = file_get_contents('access.log');

$ipAccessReg = '/[\d]+[\.][\d]+[\.][\d]+[\.][\d]+.[\-].[\-]./';
$ipAccessRegSec = '/[\d]+[\.][\d]+[\.][\d]+[\.][\d]+/';
preg_match_all($ipAccessReg, $logs, $ipAccessData);
$ipAccessData = implode(' ', $ipAccessData[0]);
preg_match_all($ipAccessRegSec, $ipAccessData, $ipAccessDataSec);

$dateAccessReg = '/[\[].*[\]]/';
preg_match_all($dateAccessReg, $logs, $dateAccessData);

$requestAccessReg = '/["].*["]/';
preg_match_all($requestAccessReg, $logs, $requestAccessData);

$requestTypeAccessReg = '/(GET)|(POST)|(PUT)|(DELETE)/';
preg_match_all($requestTypeAccessReg, $logs, $requestTypeAccessData);

$requestUrlAccessReg = '/[h][t][t][p][s]?.*[H][T][T][P][S]?[\/][\d]+[\.]?[\d]?/';
preg_match_all($requestUrlAccessReg, $logs, $requestUrlAccessData);

$requestBrowserReg = '/[\"][(Mozilla)|(Opera)|(Chrome)|(Safari)].+[\"]/';
preg_match_all($requestBrowserReg, $logs, $requestBrowserData);

$report_item_arr = [];
for($i=0;$i<count($ipAccessDataSec[0]);$i++)
{
	$report_item_arr[$i] = [
		'ip' => $ipAccessDataSec[0][$i],
		'date' => $dateAccessData[0][$i],
		'requestType' => $requestTypeAccessData[0][$i],
		'requestUrl' => $requestUrlAccessData[0][$i],
		'requestBrowser' => $requestBrowserData[0][$i]
	];
}

function converterToCSV($data, $file_name = 'access_report.csv')
{
	$str = '';
	foreach($data as $item)
	{
		$item_str = implode(',', $item);
		$str.= $item_str;
		$str.= PHP_EOL;
	}
	
	saveToFile($str, $file_name);
}

function saveToFile($data, $file_name)
{
	file_put_contents($file_name, $data);
	echo 'Файл '.$file_name.' успешно сохранен.';
}

converterToCSV($report_item_arr);
?>