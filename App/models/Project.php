<?php
namespace Model;

use InvalidArgumentException;
use PDO;
use PDOException;

use Config\Database;


class Project
{
    private  $db;

    
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getDb();
    }

    public function getAll()
    {
    
        $sql = 'SELECT name, final_date, if(status = 1, "em andamento", "finalizado") as status FROM projects';
     
        $stmt = $this->db->query($sql);
        $list_projects = $stmt->fetchAll($this->db::FETCH_ASSOC);
        return $list_projects;
        
    }

    public function getById($id)
    {
        $sql = 'SELECT name, final_date, if(status = 1, "em andamento", "finalizado") as status FROM projects WHERE id_project = :id_project';
     
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_project', $id);
        $stmt->execute();
        $project = $stmt->fetchAll($this->db::FETCH_ASSOC);
        return $project;

        
    }

    public function insert($name, $final_date){
        
       // echo "insert";exit;
       if ($name != null &&  $final_date != null){
        
        $sqlInsert = 'INSERT INTO projects (name, final_date) VALUES (:name, :final_date)';
        //echo $sqlInsert;exit;
        $stmt = $this->db->prepare($sqlInsert);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':final_date', $final_date);

        $stmt->execute();

        return $stmt->rowCount() > 0 ? true : false;
    }else{
        throw new InvalidArgumentException('Os campos nome e prazo final são obrigatórios');
    }
        

    }

    public function update($id, $name, $final_date, $project){
          
                
                $sqlUpdate = 'UPDATE projects SET name = :name, final_date = :final_date WHERE id_project = :id_project';
                
                $name = $name === null ? $project['name'] : $name;
                $final_date = $final_date === null ? $project['final_date'] : $final_date;
                
                
                $this->db->beginTransaction();
                $stmt = $this->db->prepare($sqlUpdate);

                $stmt->bindParam(':id_project', $id);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':final_date', $final_date);
                $stmt->execute();
                // $stmt->debugDumpParams();exit;
                return $stmt->rowCount() > 0 ? true : false;
      

    }

    public function verifyTask($id){
        $sqlProject = "SELECT t.status FROM projects p 
        INNER JOIN tasks t ON p.id_project = t.id_project
        WHERE t.status = 1 and p.id_project = :id_project";
        $stmt = $this->db->prepare($sqlProject);
        $stmt->bindParam(':id_project', $id);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? true : false; 
    }

    public function delete($id)
    {
        $consultaDelete = 'DELETE FROM projects WHERE id_project = :id_project';

            $this->db->beginTransaction();
            $stmt = $this->db->prepare($consultaDelete);
            $stmt->bindParam(':id_project', $id);
            $stmt->execute();
            return $stmt->rowCount() > 0 ? true : false;
            
       
    }

    public function getFinalDate($id_project){
        $sql = 'SELECT final_date FROM projects WHERE id_project = :id_project';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_project', $id_project);
        $stmt->execute();
        $project = $stmt->fetch($this->db::FETCH_ASSOC);

        return $project['final_date'];
    }



    public function updateStatus($id, $status){

      
            $sql = 'UPDATE projects SET status = :status WHERE id_project = :id_project';
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':id_project', $id);
            $stmt->bindParam(':status', $status);
       
            $stmt->execute();
            return $stmt->rowCount() > 0 ? true : false;
      

        
    }


}