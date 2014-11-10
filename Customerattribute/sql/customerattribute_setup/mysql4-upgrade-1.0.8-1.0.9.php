<?php 
$tablequote = $this->getTable('sales/quote');
$installer->run(" ALTER TABLE $tablequote ADD `customer_customernumber` VARCHAR(25) NOT NULL ");
