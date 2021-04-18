<?php
$logs = file_get_contents('access.log');

$reportExp = '/^[\d].*[\"]/m';
preg_match_all($reportExp, $logs, $reports);

$report_item_arr = [];
for($i=0;$i<count($reports[0]);$i++)
{
	$ipAccessReg = '/^[\d]+[\.][\d]+[\.][\d]+[\.][\d]+/';
	preg_match_all($ipAccessReg, $reports[0][$i], $ipAccessData);

	$dateAccessReg = '/[\[].*[\]]/';
	preg_match_all($dateAccessReg, $reports[0][$i], $dateAccessData);

	$requestAccessReg = '/["].*["]/';
	preg_match_all($requestAccessReg, $reports[0][$i], $requestAccessData);

	$requestTypeAccessReg = '/(GET)|(POST)|(PUT)|(DELETE)/';
	preg_match_all($requestTypeAccessReg, $reports[0][$i], $requestTypeAccessData);

	$requestUrlAccessReg = '/[h][t][t][p][s]?.*[H][T][T][P][S]?[\/][\d]+[\.]?[\d]?/';
	preg_match_all($requestUrlAccessReg, $reports[0][$i], $requestUrlAccessData);

	$requestBrowserReg = '/[\"][(Mozilla)|(Opera)|(Chrome)|(Safari)].+[\"]/';
	preg_match_all($requestBrowserReg, $reports[0][$i], $requestBrowserData);
		
	$report_item_arr[$i] = [
		'ip' => $ipAccessData[0][0],
		'date' => $dateAccessData[0][0],
		'requestType' => $requestTypeAccessData[0][0],
		'requestUrl' => $requestUrlAccessData[0][0],
		'requestBrowser' => $requestBrowserData[0][0]
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