<?php
/*
$connect = mysql_connect("localhost", "root", "");
mysql_select_db("roommates_import", $connect);

$handle = fopen("russia.csv", "r");

// Read first (headers) record only)
$data = fgetcsv($handle, 12000, ";");
$sql= 'CREATE TABLE russ_new_products(';

for($i=0;$i<count($data); $i++) {
	$column = strtolower(str_replace(" ", "_", $data[$i]));
	if($column != 'description') {
		$sql .= $column.' VARCHAR(255), ';
	} else {
		$sql .= $column.' TEXT, ';
	}
}

//The line below gets rid of the comma
$sql = substr($sql,0,strlen($sql)-2);
$sql .= ')';
echo $sql;
fclose($handle);*/
?>

<html>
<head>
	<meta charset="utf-8"/>
</head>
<body>
<?php

$connect = mysql_connect("localhost", "root", "root");
mysql_select_db("test", $connect);

$handle = fopen("TDR0064193.csv", "r");

// Read first (headers) record only)
$data = fgetcsv($handle, 12000, ",");

//print_r($data);

$sql= 'CREATE TABLE shipping_table_rates(';
//$column_title = (explode( ';',$data[0]));
//print_r($column_title);


foreach($data as $key => $column){
	$column = strtolower(str_replace(" ", "_", $column));
	if($column != 'description') {
		$sql .= $column.' VARCHAR(255), ';
	} else {
		$sql .= $column.' TEXT, ';
	}
	
}
//The line below gets rid of the comma
$sql = substr($sql,0,strlen($sql)-2);
$sql .= ')';
echo $sql;
fclose($handle);
?>
</body>
