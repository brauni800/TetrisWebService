<?php
namespace App\Login;

use App\Lib\Database;
use App\Lib\Response;

class LoginModel
{
    private $db;
    private $table = 'user';
    private $response;
    
    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }

    public function Login($data)
    {
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
    
    /**
     * Metodos para tomar de ejemplo
     */

    public function GetAll()
    {
		try
		{
			$result = array();

			$stm = $this->db->prepare("SELECT * FROM $this->table");
			$stm->execute();
            
			$this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();
            
            return $this->response;
		}
		catch(Exception $e)
		{
			$this->response->setResponse(false, $e->getMessage());
            return $this->response;
		}
    }
    
    public function Get($id)
    {
		try
		{
			$result = array();

			$stm = $this->db->prepare("SELECT * FROM $this->table WHERE id = ?");
			$stm->execute(array($id));

			$this->response->setResponse(true);
            $this->response->result = $stm->fetch();
            
            return $this->response;
		}
		catch(Exception $e)
		{
			$this->response->setResponse(false, $e->getMessage());
            return $this->response;
		}  
    }
    
    public function InsertOrUpdate($data)
    {
		try 
		{
            if(isset($data['id']))
            {
                $sql = "UPDATE $this->table SET 
                            Nombre          = ?, 
                            Apellido        = ?,
                            Correo          = ?,
                            Sexo            = ?,
                            Sueldo          = ?,
                            Profesion_id    = ?,
                            FechaNacimiento = ?
                        WHERE id = ?";
                
                $this->db->prepare($sql)
                     ->execute(
                        array(
                            $data['Nombre'], 
                            $data['Apellido'],
                            $data['Correo'],
                            $data['Sexo'],
                            $data['Sueldo'],
                            $data['Profesion_id'],
                            $data['FechaNacimiento'],
                            $data['id']
                        )
                    );
            }
            else
            {
                $sql = "INSERT INTO $this->table
                            (Nombre, Apellido, Correo, Sexo, Sueldo, Profesion_id, FechaNacimiento, FechaRegistro)
                            VALUES (?,?,?,?,?,?,?,?)";
                
                $this->db->prepare($sql)
                     ->execute(
                        array(
                            $data['Nombre'], 
                            $data['Apellido'],
                            $data['Correo'],
                            $data['Sexo'],
                            $data['Sueldo'],
                            $data['Profesion_id'],
                            $data['FechaNacimiento'],
                            date('Y-m-d')
                        )
                    ); 
            }
            
			$this->response->setResponse(true);
            return $this->response;
		}catch (Exception $e) 
		{
            $this->response->setResponse(false, $e->getMessage());
		}
    }
    
    public function Delete($id)
    {
		try 
		{
			$stm = $this->db
			            ->prepare("DELETE FROM $this->table WHERE id = ?");			          

			$stm->execute(array($id));
            
			$this->response->setResponse(true);
            return $this->response;
		} catch (Exception $e) 
		{
			$this->response->setResponse(false, $e->getMessage());
		}
    }
}
