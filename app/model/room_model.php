<?php
namespace App\Room;

use App\Lib\Database;
use App\Lib\Response;
use PDO;

class RoomModel {
    private $db;
    private $tableRoom = 'room';
    private $tableRoomUser = 'roomusers';
    private $response;
    private $codeErrors;
    
    public function __CONSTRUCT() {
        $this->db = Database::StartUp();
        $this->response = new Response();
        $this->codeErrors = json_decode(file_get_contents('http://localhost/TetrisWebService/app/codes.json'), true)['codes']['room'];
    }
    /**
     * params {
     *      id_user: int
     *      name: str
     *      description: str
     *      max_players: int
     *      difficulty: int
     * }
     */
    public function newRoom($data) {
        try {
            if (isset($data["id_user"], $data["name"], $data["description"], $data["max_players"], $data["difficulty"])
            && $data["id_user"] != '' && $data["name"] != '') {

                $sql = "SELECT * FROM $this->tableRoomUser WHERE id_user = ?";
                $sth = $this->db->prepare($sql);
                $sth->execute(
                    array($data["id_user"])
                );
                $result = $sth->fetch(PDO::FETCH_OBJ);

                if (!$result) {
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
                            $id_room,
                            $data["id_user"]
                        )
                    );
                    
                    $this->response->setResponse(true, $this->codeErrors['new']['NR001']);
                } else {
                    $this->response->setResponse(false, $this->codeErrors['new']['NR002']);
                }
            } else {
                $this->response->setResponse(false, $this->codeErrors['new']['NR003']);
            }
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function getAllRoom() {
        try {
            $sql = "SELECT * FROM $this->tableRoom";
            $sth = $this->db->prepare($sql);
            if ($sth->execute()) {
                $this->response->setResponse(true, $sth->fetchAll());
            }
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function enterRoom($id_user, $id_room) {
        try {
            if (isset($id_user) && $id_user != '') {
                $sql = "SELECT * FROM $this->tableRoomUser WHERE id_user = ?";
                $sth = $this->db->prepare($sql);
                $sth->execute(
                    array(
                        $id_user
                    )
                );
                $result = $sth->fetch(PDO::FETCH_OBJ);

                if (!$result) {
                    $sql = "SELECT max_players, current_players FROM $this->tableRoom WHERE id_room = ?";
                    $sth = $this->db->prepare($sql);
                    $sth->execute(
                        array($id_room)
                    );
                    $result = $sth->fetch(PDO::FETCH_OBJ);

                    if ($result) {
                        if ($result->max_players != $result->current_players) {
                            $result->current_players++;
                    
                            $sql = "UPDATE $this->tableRoom SET current_players = ? WHERE id_room = ?";
                            $sth = $this->db->prepare($sql);
                            $sth->execute(
                                array(
                                    $result->current_players,
                                    $id_room
                                )
                            );
        
                            $sql = "INSERT INTO $this->tableRoomUser (id_room, id_user) VALUES (?, ?)";
                            $sth = $this->db->prepare($sql);
                            $sth->execute(
                                array(
                                    $id_room,
                                    $id_user
                                )
                            );
        
                            $this->response->setResponse(true, $this->codeErrors['enter']['ER001']);
                        } else {
                            $this->response->setResponse(false, $this->codeErrors['enter']['ER002']);
                        }
                    } else {
                        $this->response->setResponse(false, $this->codeErrors['enter']['ER003']);
                    }
                } else {
                    $this->response->setResponse(false, $this->codeErrors['enter']['ER004']);
                }
            }
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function leaveRoom($id_user, $id_room) {
        try {
            if (isset($id_user) && $id_user != '') {
                $sql = "SELECT current_players FROM $this->tableRoom WHERE id_room = ?";
                $sth = $this->db->prepare($sql);
                $sth->execute(
                    array($id_room)
                );
                $result = $sth->fetch(PDO::FETCH_OBJ);
                if ($result) {
                    $sql = "SELECT * FROM $this->tableRoomUser WHERE id_user = ?";
                    $sth = $this->db->prepare($sql);
                    $sth->execute(
                        array($id_user)
                    );
                    $searchedUser = $sth->fetch(PDO::FETCH_OBJ);
                    if ($searchedUser) {
                        if ($searchedUser->id_user == $id_user && $searchedUser->id_room == $id_room) {
                            if ($result->current_players == 1) {
                                $sql = "DELETE FROM $this->tableRoom WHERE id_room = ?";
                                $sth = $this->db->prepare($sql);
                                $sth->execute(
                                    array($id_room)
                                );
            
                                $sql = "DELETE FROM $this->tableRoomUser WHERE id_user = ?";
                                $sth = $this->db->prepare($sql);
                                $sth->execute(
                                    array($id_user)
                                );
                            } else {
                                $result->current_players--;
                            
                                $sql = "UPDATE $this->tableRoom SET current_players = ? WHERE id_room = ?";
                                $sth = $this->db->prepare($sql);
                                $sth->execute(
                                    array(
                                        $result->current_players,
                                        $id_room
                                    )
                                );
                
                                $sql = "DELETE FROM $this->tableRoomUser WHERE id_user = ?";
                                $sth = $this->db->prepare($sql);
                                $sth->execute(
                                    array($id_user)
                                );
                            }
                            $this->response->setResponse(true, $this->codeErrors['leave']['LR001']);
                        } else {
                            $this->response->setResponse(true, $this->codeErrors['leave']['LR002']);
                        }
                    } else {
                        $this->response->setResponse(false, $this->codeErrors['leave']['LR002']);
                    }
                } else {
                    $this->response->setResponse(false, $this->codeErrors['leave']['LR003']);
                }
            }
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }
}
