<?php
namespace App\Lib;

class Response
{
	public $response   = false;
	public $message    = 'Ocurrio un error inesperado.';
	
	public function SetResponse($response, $m = '')
	{
		$this->response = $response;
		$this->message = $m;

		if(!$response && $m = '') $this->response = 'Ocurrio un error inesperado';
	}
}