<?php

namespace SRC\Connection;

class Connection
{
	private static $instance;

	private function __construct(){}

	public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
	}
	
    public function connect()
    {	
    	$dir = __DIR__.'/../../config/config.ini';
    	$conf = parse_ini_file($dir, true);
		$conn = NewADOConnection($conf['DATABASE']['driver']);
	  
  		$conn->Connect(
  			$conf['DATABASE']['host'], 
  			$conf['DATABASE']['user'], 
  			$conf['DATABASE']['pass'],
  			$conf['DATABASE']['dbname']
		);

		if($conn->_errorMsg !== false) {
			header('HTTP/1.1 500 Internal Server Error');
			echo json_encode(array(
				'response' => array(
					'error' => true, 
					'msg' => $conn->_errorMsg
			)));
			exit;
		} 
		return $conn;
	}
}