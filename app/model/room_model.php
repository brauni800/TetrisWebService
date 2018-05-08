<?php
namespace App\Room;

use App\Lib\Database;
use App\Lib\Response;

class RoomModel {
    private $db;
    private $tableRoom = 'room';
    private $tableRoomUser = 'room-users';
    private $response;
    
    public function __CONSTRUCT() {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }

    public function newRoom($data) {
        try {
            if (isset($data["id_user"], $data["name"], $data["description"], $data["max_players"], $data["difficulty"])
            && $data["id_user"] != '' && $data["name"] != '') {

                $sql = "INSERT INTO $this->tableRoom (name, description, max_players, current_players, difficulty) VALUES (?, ?, ?, ?, ?)";
                $sth = $this->db->prepare($sql);
                $sth->execute(
                    array(
                        $data['name'],
                        $data["description"],
                        $data["max_players"],
                        1,
                        $data["difficulty"]
                    )
                );
                $id_room = $this->db->lastInsertId();
                $sql = "INSERT INTO $this->tableRoomUser (id_room, id_user) VALUES (?, ?)";
                $sth = $this->db->prepare($sql);
                $sth->execute(
                    array(
                        (int)$id_room,
                        (int)$data["id_user"]
                    )
                );
                
                $this->response->setResponse(true, "Successful registry");
            } else {
                $this->response->setResponse(false, "Error in the parameters");
            }
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function getAllRoom($data) {
        try {

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function enterRoom($data) {
        try {

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function leaveRoom($data) {
        try {

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }
}
