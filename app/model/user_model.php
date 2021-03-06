<?php
namespace App\User;

use App\Lib\Database;
use App\Lib\Response;
use PDO;

class UserModel {
    private $db;
    private $table = 'user';
    private $response;
    private $codeErrors;
    
    public function __CONSTRUCT() {
        $this->db = Database::StartUp();
        $this->response = new Response();
        $this->codeErrors = json_decode(file_get_contents('http://localhost/TetrisWebService/app/codes.json'), true)['codes']['user'];
    }

    /**
     * params{
     *      username: str
     *      password: str
     * }
     */
    public function Login($data) {
        try {
            if (isset($data['username']) && isset($data["password"])) {
                $sql = "SELECT * FROM $this->table WHERE username = ?";
                $sth = $this->db->prepare($sql);
                $sth->execute(
                    array(
                        $data['username']
                    )
                );
                $result = $sth->fetch(PDO::FETCH_OBJ);

                if ($result) {
                    if ($result->password == $data['password']) {
                        $this->response->setResponse(true, $this->codeErrors['login']['LI001'], $result->id_user);
                    } else {
                        $this->response->setResponse(false, $this->codeErrors['login']['LI002']);
                    }
                } else {
                    $this->response->setResponse(false, $this->codeErrors['login']['LI002']);
                }
                
            } else {
                $this->response->setResponse(false, $this->codeErrors['login']['LI003'] . ': ' . implode(" , ", $data));
            }
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    /**
     * params{
     *      username: str
     *      password: str
     * }
     */
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
                        $this->response->setResponse(true,  $this->codeErrors['signup']['SU001']);
                    } else {
                        $this->response->setResponse(false, $this->codeErrors['signup']['SU002']);
                    }
                } else {
                    $this->response->setResponse(false, $this->codeErrors['signup']['SU003']);
                }
            } else {
                $this->response->setResponse(false, $this->codeErrors['signup']['SU004'] . ': ' . implode(" , ", $data));
            }
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }
}
