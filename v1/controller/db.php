<?php
	class DB{
		
		private static $mainDBConnection;
		
		public static function connectDB(){
			self::$mainDBConnection = new PDO('mysql:host=localhost;dbname=armenisn274598_;charset=utf8','main_invoices','Icme27%31');
			return self::$mainDBConnection;
		}
		
	}