<?php
namespace App\Lib;

class Response {
	public $response;
	public $message;
	public $value;
	
	public function __CONSTRUCT() {
		$this->response = false;
		$this->message = json_decode(file_get_contents('http://localhost/TetrisWebService/app/codes.json'), true)['codes']['default'];
		$this->value = null;
	}

	public function SetResponse($res, $m, $value = null)	{
		$this->response = $res;
		$this->value = $value;
		if ($m != '') {
			$this->message = $m;
		}
	}
}
