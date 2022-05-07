<?php 
	class InvoiceException extends Exception{ }
	
		class Invoice{
			private $_id;
			private $_amount;
			private $_currency;
			private $_invoice_date;
			private $_paid;
			
			//constructor for invoice
			public function __construct($id, $amount, $currency, $invoice_date, $paid){
				$this->setID($id);
				$this->setAmount($amount);
				$this->setCurrency($currency);
				$this->setInvoiceDate($invoice_date);
				$this->setPaid($paid);
			}
			
			public function getID(){
				return $this->_id;
			}
			public function getAmount(){
				return $this->_amount;
			}
			public function getCurrency(){
				return $this->_currency;
			}
			public function getInvoiceDate(){
				return $this->_invoice_date;
			}
			public function getPaid(){
				return $this->_paid;
			}
			
			public function setID($id){
				$this->_id=$id;
			}
			
			//validate that amount of the invoice is numeric
			public function setAmount($amount){
			if(!is_numeric($amount)){
					throw new InvoiceException(" Amount must be a numeric");
			}	
				$this->_amount=$amount;
			}
			
			public function setCurrency($currency){
				$this->_currency=$currency;
			}
			
			public function setInvoiceDate($invoice_date){
				$this->_invoice_date= $invoice_date;
			}
			
			//validate the payment status 
			public function setPaid($paid){
				if((int)$paid !== 1 && (int)paid !== 0){
					throw new InvoiceException(" Paid Status must be  1 for paid or 0 to unpaid");
				}
				$this->_paid=(int)$paid;
			}
			
			public function returnInvoiceAsArray(){
				$invoice = array();
				$invoice['id'] = $this->getID();
				$invoice['amount'] = $this->getAmount();
				$invoice['currency'] = $this->getCurrency();
				$invoice['invoice_date'] = $this->getInvoiceDate();
				$invoice['paid'] = $this->getPaid();
				return $invoice;
			}
			
			
		}