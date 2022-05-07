<?php 

require_once('../model/Invoice.php');

try{
	$invoice = new Invoice("65sad43asd12",852.23,'EUR','2020-05-05',1);
	header('Content-type: application/json;charset=utf-8');
	echo json_encode($invoice->returnInvoiceAsArray());
	
}
catch(InvoiceException $error){
	echo "Error: ". $error->getMessage();;
}