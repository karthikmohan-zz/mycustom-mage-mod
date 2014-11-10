<?php

set_time_limit(10000);

$con = mysql_connect('localhost','root','root');
mysql_select_db("test", $con);

$fp = fopen("TDR0064193.csv", "r");

$i = 1;

while( !feof($fp) ) {
  if( !$line = fgetcsv($fp, 12000, ',')) {
     continue;
  }

  $columns = array();
  if(count($line) > 0) {
  	foreach($line as $column) {
		$columns[] = mysql_real_escape_string($column);
  	}
  }

	$importSQL = "INSERT INTO shipping_table_rates VALUES('".implode("', '", $columns)."'); \n";

	mysql_query($importSQL) or die(mysql_error());
	echo "inserted --- $i <br/>";
	$i++;

}

fclose($fp);
//mysql_close($con);
