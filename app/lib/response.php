<?php
namespace App\Lib;

class Response {
	public $response;
	public $message;
	
	public function __CONSTRUCT() {
		$this->response = false;
		$this->message = json_decode(file_get_contents('http://localhost/TetrisWebService/app/codes.json'), true)['codes']['default'];
	}

	public function SetResponse($res, $m)	{
		$this->response = $res;
		if ($m != '') {
			$this->message = $m;
		}
	}
}
