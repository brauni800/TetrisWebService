<?php
namespace App\User;

use App\Lib\Database;
use App\Lib\Response;

class UserModel {
    private $db;
    private $table = 'user';
    private $response;
    
    public function __CONSTRUCT() {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }

    public function Login($data) {
        try {
            if (isset($data['username']) && isset($data["password"])) {
                $sql = "SELECT username, password FROM $this->table WHERE username = ?";
                $sth = $this->db->prepare($sql);
                $sth->execute(
                    array(
                        $data['username']
                    )
                );
                $encode = json_encode($sth->fetchAll());
                $decode = json_decode($encode, true);

                if ($decode) {
                    if ($decode[0]['password'] == $data['password']) {
                        $this->response->setResponse(true, "Successful login");
                    } else {
                        $this->response->setResponse(false, "Access denied");
                    }
                } else {
                    $this->response->setResponse(false, "Access denied");
                }
                
            } else {
                $this->response->setResponse(false, "DB error: " . implode(" , ", $data));
            }
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function Signup($data) {
        try {
            if (isset($data['username'])) {
                $sql = "SELECT username FROM $this->table WHERE username = ?";
                $sth = $this->db->prepare($sql);
                $sth->execute(
                    array(
                        $data['username']
                    )
                );
                $encode = json_encode($sth->fetchAll());
                $decode = json_decode($encode, true);

                if (!$decode) {
                    if (isset($data["password"]) && $data["password"] != '') {
                        $sql = "INSERT INTO $this->table (username, password) VALUES (?, ?)";
                        $sth = $this->db->prepare($sql);
                        $sth->execute(
                            array(
                                $data['username'],
                                $data['password']
                            )
                        );
                        $this->response->setResponse(true,  "Successful registration");
                    } else {
                        $this->response->setResponse(false, "Undefined password");
                    }
                } else {
                    $this->response->setResponse(false, "This account already exists");
                }
            } else {
                $this->response->setResponse(false, "DB error: " . implode(" , ", $data));
            }
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }
}
