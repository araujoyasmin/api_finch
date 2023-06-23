<?php

namespace Model;

use InvalidArgumentException;
use PDO;
use PDOException;

use Config\Database;

class Task
{
    private  $db;

    
    public function __construct()
    {
        $database = Database::getInstance();
        $this->db = $database->getDb();
    }
   

    public function getAll()
    {
    
        $sql = 'SELECT id_task, p.name AS name_projeto, u.name AS user_name, title, description, t.final_date, if(t.status = 1, "em andamento", "finalizada") as status FROM tasks t
                INNER JOIN projects p ON t.id_project = p.id_project
                INNER JOIN users u ON t.id_user = u.id_user ';
     
        $stmt = $this->db->query($sql);
        $list_tasks = $stmt->fetchAll($this->db::FETCH_ASSOC);
        return $list_tasks;
        
    }

    public function getById($id)
    {
    
        $sql = 'SELECT id_task, p.name AS name_projeto, u.name AS user_name, title, description, t.final_date, if(t.status = 1, "em andamento", "finalizada") as status FROM tasks t
                INNER JOIN projects p ON t.id_project = p.id_project
                INNER JOIN users u ON t.id_user = u.id_user
                WHERE id_task = :id_task ';
     
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_task', $id);
        $stmt->execute();
        $task = $stmt->fetchAll($this->db::FETCH_ASSOC);
        return $task;
        
    }

    public function insert($id_project, $id_user, $title, $description,$final_date){
        
            $sqlInsert = 'INSERT INTO tasks (id_project, id_user , title, description, final_date ) VALUES (:id_project, :id_user , :title, :description, :final_date)';
            
            $stmt = $this->db->prepare($sqlInsert);
            $stmt->bindParam(':id_project', $id_project);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':final_date', $final_date);
    
            $stmt->execute();
            // $stmt->debugDumpParams();exit;
            if($stmt->rowCount() > 0){
            
                return 'Cadastrado com sucesso!';
            }
       
    }


    public function update($id, $data){
        
        $validateUpdate = 'SELECT * FROM tasks WHERE id_task = :id_task';
        $val = $this->db->prepare($validateUpdate);

        $val->bindParam(':id_task', $id);
        $val->execute();
        
        //$val->debugDumpParams();exit;
        if($val->rowCount() > 0){
          
                $task = $val->fetch($this->db::FETCH_ASSOC);
                $sqlUpdate = 'UPDATE tasks SET id_project = :id_project, id_user = :id_user, title = :title, description = :description, final_date = :final_date WHERE id_task = :id_task';
                
                $id_project = $data['id_project'] === null ? $task['id_project'] : $data['id_project'];
                $id_user = $data['id_user'] === null ? $task['id_user'] : $data['id_user'];
                $title = $data['title'] === null ? $task['title'] : $data['title'];
                $description = $data['description'] === null ? $task['description'] : $data['description'];
                $final_date = $data['final_date'] === null ? $task['final_date'] : $data['final_date'];
                
                
                $this->db->beginTransaction();
                $stmt = $this->db->prepare($sqlUpdate);

                $stmt->bindParam(':id_task', $id);
                $stmt->bindParam(':id_project', $id_project);
                $stmt->bindParam(':id_user', $id_user);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':final_date', $final_date);
                $stmt->execute();
                // $stmt->debugDumpParams();exit;
                if($stmt->rowCount() > 0){
                    $this->db->commit();
                    return [
                        'status' => 'success',
                        'message' => 'Tarefa editada com sucesso!'
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
            'message' => 'Tarefa nao encontrada!'
        ];

    }

    public function delete($id)
    {
        $consultaDelete = 'DELETE FROM tasks WHERE id_task = :id_task';

            $this->db->beginTransaction();
            $stmt = $this->db->prepare($consultaDelete);
            $stmt->bindParam(':id_task', $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $this->db->commit();
                return 'Deletado com sucesso';
            }

        
    }

    public function updateStatus($id, $status){
        $sql = 'UPDATE tasks SET status = :status WHERE id_task = :id_task';
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':id_task', $id);
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
    
    public function getDb()

    {
     
        return $this->db;
    }
}