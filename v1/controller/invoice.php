<?php
require_once('db.php');
require_once('../model/Invoice.php');
require_once('../model/Response.php');

try{
	$maindb = DB::connectDB();
}
catch (PDOException $ex){	
	$response = new Response();
	$response -> setHttpStatusCode(500);
	$response ->setSuccess(false);
	$response ->addMessage("DB Connect Error");
	$response -> send();
	exit();
}
//------------------------STORE URL REQUEST TO DB----------------------------------------------------
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$query = $maindb -> prepare('insert into logger (request) VALUES ("'.$actual_link .'")');
	$query->execute();


//--------------------------GET ONE OR MULTIPLE INVOICES----------------------------
if (array_key_exists("invoiceid",$_GET)){
	
	$invoiceid = $_GET['invoiceid'];
	//check if invoice id is blank
	if ($invoiceid == '  '){
		$response = new Response();
		$response -> setHttpStatusCode(400);
		$response ->setSuccess(false);
		$response ->addMessage("Invoice can't be blank");
		$response -> send();
		exit();
	}
	
	if ($_SERVER['REQUEST_METHOD'] === 'GET'){
		try{
			
			$query = $maindb -> prepare('select invoice_id, amount, currency, DATE_FORMAT(invoice_date, "%d/%m/%Y") as invoice_date, is_paid from invoices where invoice_id IN ('.$_GET['invoiceid'].')');

			$query->execute();
			$rowCount = $query->rowCount();
			if ($rowCount===0){
				$response = new Response();
				$response -> setHttpStatusCode(404);
				$response ->setSuccess(false);
				$response ->addMessage("Invoice doesn't found");
				$response -> send();
				exit();
			}
			
			while($row=$query->fetch(PDO::FETCH_ASSOC)){
				$invoice = new Invoice($row['invoice_id'],$row['amount'],$row['currency'],$row['invoice_date'],$row['is_paid']);
				$invoiceArray[]= $invoice->returnInvoiceAsArray();
			}
			
			$returnData= array();
			$returnData['rows_returned'] = $rowCount;
			$returnData['invoices']  = $invoiceArray;
			$response = new Response();
			$response -> setHttpStatusCode(200);
			$response ->setSuccess(true);
			//$response ->toCache(true); //why not to cache :)
 			$response ->setData($returnData);
			$response -> send();
			exit();
		}
		catch (PDOException $ex)	{}
		
		
	}
	
	
}
//--------------------------GET ALL INVOICES------------------------------ extra options
elseif(empty($_GET)){
	
		try{
			$query = $maindb -> prepare('select invoice_id, amount, currency, DATE_FORMAT(invoice_date, "%d/%m/%Y") as invoice_date, is_paid from invoices');

			$query->execute();
			$rowCount = $query->rowCount();
			while($row=$query->fetch(PDO::FETCH_ASSOC)){
				$invoice = new Invoice($row['invoice_id'],$row['amount'],$row['currency'],$row['invoice_date'],$row['is_paid']);
				$invoiceArray[]= $invoice->returnInvoiceAsArray();
			}
			$returnData= array();
			$returnData['rows_returned'] = $rowCount;
			$returnData['invoices']  = $invoiceArray;
			
			$response = new Response();
			$response -> setHttpStatusCode(200);
			$response ->setSuccess(true);
			//$response ->toCache(true); //why not to cache :)
 			$response ->setData($returnData);
			$response -> send();
			exit();
		}
		catch (PDOException $ex)	{}
		
		
//--------------------------FILTER INVOICES BY DATES-----------------------------
}elseif(array_key_exists("date_from",$_GET) && array_key_exists("date_to",$_GET) ){
	
	$datefrom = $_GET['date_from'];
	$dateto = $_GET['date_to'];
	
	try{
			$query = $maindb -> prepare('select invoice_id, amount, currency, invoice_date, is_paid from invoices where invoice_date > '. $datefrom. ' and invoice_date < '. $dateto);
			$query->execute();
			$rowCount = $query->rowCount();
			
			if ($rowCount===0){
				$response = new Response();
				$response -> setHttpStatusCode(402);
				$response ->setSuccess(false);
				$response ->addMessage("No results found");
				$response -> send();
				exit();
			}
			
			
			while($row=$query->fetch(PDO::FETCH_ASSOC)){
				$invoice = new Invoice($row['invoice_id'],$row['amount'],$row['currency'],$row['invoice_date'],$row['is_paid']);
				$invoiceArray[]= $invoice->returnInvoiceAsArray();
			}
			$returnData= array();
			$returnData['rows_returned'] = $rowCount;
			$returnData['invoices']  = $invoiceArray;
			
			$response = new Response();
			$response -> setHttpStatusCode(200);
			$response ->setSuccess(true);
			//$response ->toCache(true); //why not to cache :)
 			$response ->setData($returnData);
			$response -> send();
			exit();
		}
		catch (PDOException $ex)	{}
		
		
	
}else{
		$response = new Response();
		$response -> setHttpStatusCode(404);
		$response ->setSuccess(false);
		$response ->addMessage("Endpoint Not Found");
		$response -> send();
		exit();
}
	

