<?php

namespace Model;

use InvalidArgumentException;
use PDO;
use PDOException;


class User
{
    private  $db;

    
    public function __construct()
    {
        $this->db = $this->setDB();
    }

   
    public function setDB()
    {
        try {
            
            return new PDO(
                'mysql:host=localhost; dbname=api;', 'root', ''
            );
        } catch (PDOException $exception) {
            throw new PDOException($exception->getMessage());
        }
    }

    public function getAll()
    {
    

       $sql = 'SELECT name, cpf, email FROM users';
     
       $stmt = $this->db->query($sql);
       $list_users = $stmt->fetchAll($this->db::FETCH_ASSOC);
       return $list_users;
        
    }

    public function getById($id)
    {
    

       $sql = 'SELECT name, cpf, email FROM users WHERE id_user = :id_user';
     
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':id_user', $id);
       $stmt->execute();
    //    $stmt->debugDumpParams();exit;
       $user = $stmt->fetchAll($this->db::FETCH_ASSOC);
       return $user;
        
    }

    public function insert($name, $email, $cpf){
 
        if ($name != null &&  $cpf != null){
        
            $sqlInsert = 'INSERT INTO users (name, email, cpf) VALUES (:name, :email, :cpf)';
   
            $stmt = $this->db->prepare($sqlInsert);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':cpf', $cpf);

            $stmt->execute();

            if($stmt->rowCount() > 0){
                return 'Cadastrado com sucesso!';
            }
        }else{
            throw new InvalidArgumentException('Os campos nome, email e cpf são obrigatórios');
        }
        

    }

    public function checkCpfExists($cpf){
        $sql = "SELECT * FROM users WHERE cpf = :cpf";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function checkEmailExists($email){
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function update($id, $data){
     
        $validateUpdate = 'SELECT * FROM users WHERE id_user = :id_user';
        $val = $this->db->prepare($validateUpdate);

        $val->bindParam(':id_user', $id);
        $val->execute();
        
        if($val->rowCount() > 0){
            $user = $val->fetch($this->db::FETCH_ASSOC);
            $validateEmail = 'SELECT * FROM users WHERE email = :email and id_user <> :id_user';  // criar metodo para verificar por id email e cpf
            $val2 = $this->db->prepare($validateEmail);
            $val2->bindParam(':id_user', $id);
            $val2->bindParam(':email', $data['email']);
            $val2->execute();
            if ($val2->rowCount() > 0){
                http_response_code(409);
                return [
                    'status' => 'error',
                    'message' => 'Email e/ou CPF pertencem a outro usuario'
                ];
            }else{
                $sqlUpdate = 'UPDATE users SET name = :name, email = :email, cpf = :cpf WHERE id_user = :id_user';
                
                $name = $data['name'] === null ? $user['name'] : $data['name'];
                $email = $data['email'] === null ? $user['email'] : $data['email'];
                $cpf = $data['cpf'] === null ? $user['cpf'] : $data['cpf'];
                
                
                $this->db->beginTransaction();
                $stmt = $this->db->prepare($sqlUpdate);

                $stmt->bindParam(':id_user', $id);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':cpf', $cpf);
                $stmt->execute();

                if($stmt->rowCount() > 0){
                    $this->db->commit();
                    return [
                        'status' => 'success',
                        'message' => 'Usuario editado com sucesso!'
                    ];
                }
            }

        }
        http_response_code(404);
        return [
            'status' => 'not_found',
            'message' => 'Usuario nao encontrado!'
        ];

    }

    public function delete($id)
    {
               
        $consultaDelete = 'DELETE FROM users WHERE id_user = :id_user';
 
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($consultaDelete);
            $stmt->bindParam(':id_user', $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $this->db->commit();
                return 'Deletado com sucesso';
            }
  
    }

    public function getUser($email, $cpf){
        $sql = 'SELECT * FROM users WHERE cpf = :cpf and email = :email';
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch($this->db::FETCH_ASSOC);

        return $user;
    }



    
    public function getDb()

    {
     
        return $this->db;
    }
}