<?php
namespace App\Friend;

use App\Lib\Database;
use App\Lib\Response;
use PDO;

class FriendModel {
    private $db;
    private $tableFriend = 'friend';
    private $response;
    private $codeErrors;
    private $statusRequest;

    public function __CONSTRUCT() {
        $this->db = Database::StartUp();
        $this->response = new Response();
        $this->codeErrors = json_decode(file_get_contents('http://localhost/TetrisWebService/app/codes.json'), true)['codes']['friend'];
        $this->statusRequest = [
            'waiting' => 'Waiting',
            'accepted' => 'Accepted'
        ];
    }

    public function sendFriendRequest($data) {
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
                    if ($result) {

                    } else {
                        $sql = "INSERT INTO $this->tableFriend (id_user, id_friend, request) VALUES (?, ?, ?)";
                        $sth = $this->db->prepare($sql);
                        $sth->execute(
                            array(
                                $data['id_user'],
                                $data['id_friend'],
                                $this->statusRequest['waiting']
                            )
                        );
                        $this->response->setResponse(true, 'Solicitud enviada');
                    }
                } else {
                    $this->response->setResponse(false, 'Uno de los usuarios no existe');
                }
            }
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
        return $this->response;
    }

    public function rejectFriendRequest($data) {
        try {

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
        return $this->response;
    }

    public function getAllRequest($data) {
        try {

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
        return $this->response;
    }

    public function acceptFriend($data) {
        try {

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
        return $this->response;
    }

    public function removeFriend($data) {
        try {

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
        return $this->response;
    }
}
