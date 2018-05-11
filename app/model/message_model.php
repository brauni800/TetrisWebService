<?php
namespace App\Message;

use App\Lib\Database;
use App\Lib\Response;
use PDO;

class MessageModel {
    private $db;
    private $tableMessage = 'message';
    private $response;
    private $codeErrors;

    public function __CONSTRUCT() {
        $this->db = Database::StartUp();
        $this->response = new Response();
        $this->codeErrors = json_decode(file_get_contents('http://localhost/TetrisWebService/app/codes.json'), true)['codes']['message'];
    }

    /**
     * parameters {
     *      id_user: int
     *      id_room: int
     *      time: Time
     *      content: str
     * }
     */
    public function sendMessage($data) {
        try {
            if (isset($data['id_user'], $data['id_friend'], $data['time'], $data['content'])
                && $data['id_user'] != '' && $data['id_friend'] != '') {
                
            }
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
        return $this->response;
    }
}
