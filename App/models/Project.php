<?php

namespace Model;

use InvalidArgumentException;
use PDO;
use PDOException;

use Config\Database;

require_once ('./App/config/database.php');

class Project
{
    private  $db;

    
    public function __construct()
    {
        $database = Database::getInstance();
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

        if($stmt->rowCount() > 0){
        // $this->db->commit();
            return 'Cadastrado com sucesso!';
        }
    }else{
        throw new InvalidArgumentException('Os campos nome e prazo final são obrigatórios');
    }
        

    }

    public function update($id, $data){
        $validateUpdate = 'SELECT * FROM projects WHERE id_project = :id_project';
        $val = $this->db->prepare($validateUpdate);

        $val->bindParam(':id_project', $id);
        $val->execute();
        
        // $val->debugDumpParams();exit;
        if($val->rowCount() > 0){
          
                $project = $val->fetch($this->db::FETCH_ASSOC);
                $sqlUpdate = 'UPDATE projects SET name = :name, final_date = :final_date WHERE id_project = :id_project';
                
                $name = $data['name'] === null ? $project['name'] : $data['name'];
                $final_date = $data['final_date'] === null ? $project['final_date'] : $data['final_date'];
                
                
                $this->db->beginTransaction();
                $stmt = $this->db->prepare($sqlUpdate);

                $stmt->bindParam(':id_project', $id);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':final_date', $final_date);
                $stmt->execute();
                // $stmt->debugDumpParams();exit;
                if($stmt->rowCount() > 0){
                    $this->db->commit();
                    return [
                        'status' => 'success',
                        'message' => 'Projeto editado com sucesso!'
                    ];
                }else{
                    return [
                        'status' => 'error',
                        'message' => 'Nenhuma linha afetada!'
                    ];
                }
            

        }
        http_response_code(404);
        return [
            'status' => 'not_found',
            'message' => 'Projeto nao encontrado!'
        ];

    }

    public function delete($id)
    {
        $consultaDelete = 'DELETE FROM projects WHERE id_project = :id_project';

            $this->db->beginTransaction();
            $stmt = $this->db->prepare($consultaDelete);
            $stmt->bindParam(':id_project', $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $this->db->commit();
                return 'Deletado com sucesso';
            }
       
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

        // verificar se existem tarefas em aberto
        $sqlProject = "SELECT t.status FROM projects p 
                        INNER JOIN tasks t ON p.id_project = t.id_project
                        WHERE t.status = 1 and p.id_project = :id_project";
        $stmt = $this->db->prepare($sqlProject);
        $stmt->bindParam(':id_project', $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            http_response_code(500);
            return [
                'status' => 'error',
                'message' => 'Existem tarefas em aberto para esse projeto!'
            ];
        }else{
            $sql = 'UPDATE projects SET status = :status WHERE id_project = :id_project';
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':id_project', $id);
            $stmt->bindParam(':status', $status);
        try {
            $stmt->execute();
            // Verifica se a consulta foi executada corretamente
            if ($stmt->rowCount() > 0) {
                return [
                    'status' => 'success',
                    'message' => 'Status atualizado com sucesso!'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Nenhuma linha afetada!'
                ];
            }
        } catch (\Exception $error) {
            http_response_code(500);
            
            return [
                'status' => 'error',
                'message' => 'Ocorreu um erro ao atualizar o status da Tarefa'
            ];
        }
        }

        
    }


    
    public function getDb()

    {
     
        return $this->db;
    }
}