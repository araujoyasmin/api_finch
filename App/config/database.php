<?php

namespace Config;
class Database
{
    private static $instance;
    private $db;

    private function __construct()
    {
        $this->db = $this->setDB();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setDB()
    {
        try {
            return new \PDO(
                'mysql:host=localhost; dbname=api;',
                'root',
                ''
            );
        } catch (\PDOException $exception) {
            throw new \PDOException($exception->getMessage());
        }
    }

    public function getDb()
    {
        return $this->db;
    }
}
