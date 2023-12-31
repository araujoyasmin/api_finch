<?php
namespace Model;

use Config\Database;

class User
{
    private  $db;
 

    
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getDb();
    }

    public function getAll()
    {
    

       $sql = 'SELECT name, cpf, email, if(id_perfil = 1, "executor", "gerente") as perfil FROM users';
     
       $stmt = $this->db->query($sql);
       $list_users = $stmt->fetchAll($this->db::FETCH_ASSOC);
       return $list_users;
        
    }

    public function getById($id)
    {
    

       $sql = 'SELECT name, cpf, email, if(id_perfil = 1, "executor", "gerente") as perfil FROM users WHERE id_user = :id_user';
     
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':id_user', $id);
       $stmt->execute();
    //    $stmt->debugDumpParams();exit;
       $user = $stmt->fetch($this->db::FETCH_ASSOC);
       return $user;
        
    }

    public function insert($name, $email, $cpf, $perfil){
 
       
        
            $sqlInsert = 'INSERT INTO users (name, email, cpf, id_perfil) VALUES (:name, :email, :cpf, :perfil)';
   
            $stmt = $this->db->prepare($sqlInsert);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':perfil', $perfil);

            $stmt->execute();

            return $stmt->rowCount() > 0 ? true : false;
            
       
        

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

    public function verifyEmail($id, $email){
        $sql = 'SELECT * FROM users WHERE email = :email and id_user <> :id_user'; 
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_user', $id);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        // $stmt->debugDumpParams();exit;
        return $stmt->rowCount();
    }

    public function verifyCpf($id, $cpf){
        $sql = 'SELECT * FROM users WHERE cpf = :cpf and id_user <> :id_user'; 
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_user', $id);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function update($id, $name, $email, $cpf, $user){
   
        $sqlUpdate = 'UPDATE users SET name = :name, email = :email, cpf = :cpf WHERE id_user = :id_user';
        
        $name = $name === null ? $user['name'] : $name;
        $email = $email === null ? $user['email'] : $email;
        $cpf = $cpf === null ? $user['cpf'] : $cpf;
        
        $stmt = $this->db->prepare($sqlUpdate);

        $stmt->bindParam(':id_user', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        // $stmt->debugDumpParams();exit;
        return $stmt->rowCount() > 0 ? true : false;

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



}