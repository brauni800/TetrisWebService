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
    
    public function __CONSTRUCT() {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }

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
                    
                    $this->response->setResponse(true, "Successful registry");
                } else {
                    $this->response->setResponse(false, "This user is already in a room");
                }
            } else {
                $this->response->setResponse(false, "Error in the parameters");
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
        
                            $this->response->setResponse(true, 'Successful entry');
                        } else {
                            $this->response->setResponse(false, 'The room is full');
                        }
                    } else {
                        $this->response->setResponse(false, 'The room does not exist');
                    }
                } else {
                    $this->response->setResponse(false, 'The user is already in a room');
                }
            } else {
                $this->response->setResponse(false, 'if error ' . $id_user . ' .');
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
                            $this->response->setResponse(true, 'Successful delete');
                        } else {
                            $this->response->setResponse(true, "The user is not in the room");
                        }
                    } else {
                        $this->response->setResponse(false, 'The user with id ' . $id_user . ' is not in the room');
                    }
                } else {
                    $this->response->setResponse(false, 'The room does not exist');
                }
            } else {
                $this->response->setResponse(false, 'if error ' . $id_user . ' .');
            }
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }
}
