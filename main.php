<?php
// Author: Linmic, email: linmicya@gmail.com

$host = ""; // your db host (ip/dn)
$user = ""; // your db's privileged user account
$password = ""; // and it's password
$db_name = ""; // db name
$tbl_name = ""; // table name of the selected db

$link = mysql_connect ($host, $user, $password) or die('Could not connect: ' . mysql_error());
mysql_select_db($db_name) or die('Could not select database');

$select = "SELECT * FROM `".$tbl_name."`";

mysql_query('SET NAMES utf8;');
$export = mysql_query($select); 
$fields = mysql_num_rows($export); // thanks to Eric

for ($i = 0; $i < $fields; $i++) {
	$col_title .= '<td>'.mysql_field_name($export, $i).'</td>';
}

$col_title = '<tr>'.$col_title.'</tr>';

while($row = mysql_fetch_row($export)) {
	$line = '';
	foreach($row as $value) {
		if ((!isset($value)) OR ($value == "")) {
			$value = "\t"; 
		} else {
			$value = str_replace('"', '', $value);
			$value = '<td>' . $value . '</td>' . "\t";
		}
		$line .= $value;
	}
	$data .= trim("<tr>".$line."</tr>")."\n";
}

$data = str_replace("\r","",$data);

header("Content-Type: application/vnd.ms-excel;");
header("Content-Disposition: attachment; filename=export.xls");
header("Pragma: no-cache");
header("Expires: 0");

$xls_header = '<html xmlns:o="urn:schemas-microsoft-com:office:office"
	xmlns:x="urn:schemas-microsoft-com:office:excel"
	xmlns="http://www.w3.org/TR/REC-html40">
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
	<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
	</head>
	<body>
	<table border="1" align="center">';

$xls_footer = '</table>
	</body>
	</html>';

print $xls_header.$col_title.$data.$xls_footer;
exit;

?>
