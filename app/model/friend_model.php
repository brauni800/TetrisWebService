<?php
namespace App\Friend;

use App\Lib\Database;
use App\Lib\Response;
use PDO;

class FriendModel {
    private $db;
    private $tableFriend = 'friend';
    private $tableChat = 'chat';
    private $response;
    private $codeErrors;
    private $statusRequest;

    public function __CONSTRUCT() {
        $this->db = Database::StartUp();
        $this->response = new Response();
        $this->codeErrors = json_decode(file_get_contents('http://localhost/TetrisWebService/app/codes.json'), true)['codes']['friend'];
        $this->statusRequest = [
            'waiting' => 'WAITING',
            'accepted' => 'ACCEPTED',
            'sent' => 'SENT'
        ];
    }

    /**
     * parameters {
     *      id_user: int        el que solicita
     *      id_friend: int      el solicitado
     * }
     */
    public function sendRequest($data) {
        try {
            if (isset($data['id_user'], $data['id_friend']) && $data['id_user'] != '' && $data['id_friend'] != '') {
                $sql = "SELECT user.id_user, friend.id_user AS id_friend
                        FROM user, (SELECT user.id_user FROM user WHERE user.id_user = ?) friend
                        WHERE user.id_user = ?";
                $sth = $this->db->prepare($sql);
                $sth->execute(
                    array(
                        $data['id_friend'],
                        $data['id_user']
                    )
                );
                $result = $sth->fetch(PDO::FETCH_OBJ);
                if ($result) {
                    $sql = "SELECT * FROM $this->tableFriend WHERE id_user = ? AND id_friend = ?";
                    $sth = $this->db->prepare($sql);
                    $sth->execute(
                        array(
                            $data['id_user'],
                            $data['id_friend']
                        )
                    );
                    $result = $sth->fetch(PDO::FETCH_OBJ);
                    if (!$result) {
                        $sql = "INSERT INTO $this->tableFriend (id_user, request_user, id_friend, request_friend) VALUES (?, ?, ?, ?)";
                        $sth = $this->db->prepare($sql);
                        $sth->execute(
                            array(
                                $data['id_user'],
                                $this->statusRequest['sent'],
                                $data['id_friend'],
                                $this->statusRequest['waiting']
                            )
                        );
                        $sth->execute(
                            array(
                                $data['id_friend'],
                                $this->statusRequest['waiting'],
                                $data['id_user'],
                                $this->statusRequest['sent']
                            )
                        );
                        $this->response->setResponse(true, $this->codeErrors['sendRequest']['SR001']);
                    } else {
                        $this->response->setResponse(false, $this->codeErrors['sendRequest']['SR002']);
                    }
                } else {
                    $this->response->setResponse(false, $this->codeErrors['sendRequest']['SR003']);
                }
            }
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
        return $this->response;
    }

    /**
     * parameters {
     *      id_user: int        el que rechaza
     *      id_friend: int      el rechazado
     * }
     */
    public function rejectRequest($data) {
        try {
            if (isset($data['id_user'], $data['id_friend']) && $data['id_user'] != '' && $data['id_friend'] != '') {

                $sql = "DELETE FROM $this->tableFriend WHERE id_user = ? AND request_user = ? AND id_friend = ? AND request_friend = ?";
                $sth = $this->db->prepare($sql);
                $result = $sth->execute(
                    array(
                        $data['id_user'],
                        $this->statusRequest['waiting'],
                        $data['id_friend'],
                        $this->statusRequest['sent']
                    )
                );
                if ($result) {
                    $sth->execute(
                        array(
                            $data['id_friend'],
                            $this->statusRequest['sent'],
                            $data['id_user'],
                            $this->statusRequest['waiting']
                        )
                    );
                    $this->response->setResponse(true, $this->codeErrors['rejectRequest']['RR001']);
                } else {
                    $this->response->setResponse(false, $this->codeErrors['rejectRequest']['RR002']);
                }
            }
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
        return $this->response;
    }

    public function getAllRequest($id_user) {
        try {
            $sql = "SELECT * FROM user WHERE id_user = ?";
            $sth = $this->db->prepare($sql);
            $result = $sth->execute(
                array($id_user)
            );
            $result = $sth->fetch(PDO::FETCH_OBJ);
            if (isset($result->id_user)) {
                $sql = "SELECT * FROM $this->tableFriend WHERE id_user = ?";
                $sth = $this->db->prepare($sql);
                $result = $sth->execute(
                    array($id_user)
                );
                $arrayResult = $sth->fetchAll();
                if ($result) {
                    if (count($arrayResult) != 0) {
                        $this->response->setResponse(true, $arrayResult);
                    } else {
                        $this->response->setResponse(false, $this->codeErrors['getAllRequest']['GR001']);
                    }
                }
            } else {
                $this->response->setResponse(false, $this->codeErrors['getAllRequest']['GR002']);
            }
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
        return $this->response;
    }

    /**
     * parameters {
     *      id_user: int        el que acepta
     *      id_friend: int      el aceptado
     * }
     */
    public function acceptFriend($data) {
        try {
            if (isset($data['id_user'], $data['id_friend']) && $data['id_user'] != '' && $data['id_friend'] != '') {
                $sql = "UPDATE $this->tableFriend SET request_user = ?, request_friend = ? WHERE id_user = ? AND request_user = ? AND id_friend = ? AND request_friend = ?";
                $sth = $this->db->prepare($sql);
                $result = $sth->execute(
                    array(
                        $this->statusRequest['accepted'],
                        $this->statusRequest['accepted'],
                        $data['id_user'],
                        $this->statusRequest['waiting'],
                        $data['id_friend'],
                        $this->statusRequest['sent']
                    )
                );
                if ($result) {
                    $sth->execute(
                        array(
                            $this->statusRequest['accepted'],
                            $this->statusRequest['accepted'],
                            $data['id_friend'],
                            $this->statusRequest['sent'],
                            $data['id_user'],
                            $this->statusRequest['waiting']
                        )
                    );

                    $sql = "INSERT INTO $this->tableChat (id_remitter, id_receiver) VALUES (?, ?)";
                    $sth = $this->db->prepare($sql);
                    $result = $sth->execute(
                        array(
                            $data['id_user'],
                            $data['id_friend']
                        )
                    );
                    $result = $sth->execute(
                        array(
                            $data['id_friend'],
                            $data['id_user']
                        )
                    );

                    $this->response->setResponse(true, $this->codeErrors['acceptFriend']['AF001']);
                } else {
                    $this->response->setResponse(false, $this->codeErrors['acceptFriend']['AF002']);
                }
            }
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
        return $this->response;
    }

    /**
     * parameters {
     *      id_user: int        el que elimina
     *      id_friend: int      el eliminado, como ella a ti :,v  https://www.youtube.com/watch?v=Bk3lknaWI9Q
     * }
     */
    public function removeFriend($data) {
        try {
            if (isset($data['id_user'], $data['id_friend']) && $data['id_user'] != '' && $data['id_friend'] != '') {
                $sql = "DELETE FROM $this->tableFriend WHERE id_user = ? AND request_user = ? AND id_friend = ? AND request_friend = ?";
                $sth = $this->db->prepare($sql);
                $result = $sth->execute(
                    array(
                        $data['id_user'],
                        $this->statusRequest['accepted'],
                        $data['id_friend'],
                        $this->statusRequest['accepted']
                    )
                );
                
                if ($result) {
                    $sth->execute(
                        array(
                            $data['id_friend'],
                            $this->statusRequest['accepted'],
                            $data['id_user'],
                            $this->statusRequest['accepted']
                        )
                    );

                    $sql = "DELETE FROM $this->tableChat WHERE id_remitter = ? AND id_receiver = ?";
                    $sth = $this->db->prepare($sql);
                    $result = $sth->execute(
                        array(
                            $data['id_user'],
                            $data['id_friend']
                        )
                    );
                    $result = $sth->execute(
                        array(
                            $data['id_friend'],
                            $data['id_user']
                        )
                    );
                    $this->response->setResponse(true, $this->codeErrors['removeFriend']['RM001']);
                } else {
                    $this->response->setResponse(false, $this->codeErrors['removeFriend']['RM002']);
                }
            }
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
        return $this->response;
    }

    public function getAllFriend($data) {

    }
}
