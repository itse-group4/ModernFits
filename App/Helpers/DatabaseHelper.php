<?php

namespace App\Helpers;
use App\Config\Database;

/**
 * Provides a basic and low-level inteface with the database. 
 *
 * @copyright  2023 ModernFit-Group:4
 * @category   Helpers
 * @since      Class available since Release 1.0.1
 */ 
class DatabaseHelper {

    function __construct()
    {
        try {
            $this->connection = new \mysqli(
                Database['server'],
                Database['username'],
                Database['password'],
                Database['database'],
                Database['port']
            );
        } catch (\mysqli_sql_exception $e) {
            // Log the error or handle it appropriately
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function __destruct() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    public function query($query, $associate)
    {

        $statement = $this->connection->prepare($query);

        if (!$statement) {
            throw new \Exception($this->connection->error);
        }

        $statement->execute();
           
        $results = $statement->get_result();

        if ($associate) {
            $results = $results->fetch_assoc();
        } else {
            $results = $results->fetch_all(MYSQLI_ASSOC);
        }

        return $results;
    }

    //If these dont work i sincerely apologise - I'll rewrite them again back to how they used to be 
    public function create($table, $columns=[], $values=[])
    {
        // Get last object in database and get it's ID
        $statement = $this->connection->prepare("SELECT MAX(id) FROM " . $table);
        $statement->execute();
        
        $lastItem = $statement->get_result()->fetch_assoc()['MAX(id)'];

        // Inject id/created_at/updated_at into values string
        
        if($values['id'] == null)
        {
            $values['id'] = $lastItem+1;
        }

        $values['updated_at'] = date("Y-m-d H:i:s");
        $values['created_at'] = date("Y-m-d H:i:s");

        // Prepare columns and values string
        $columns = implode(', ', array_map(function ($col) {
            return "`$col`";
        }, $columns));

        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        
        $prepare = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $placeholders . ");";
        $statement = $this->connection->prepare($prepare);

        if (!$statement) {
            throw new \Exception($this->connection->error);
        }

        $statement->bind_param(str_repeat('s', count($values)), ...array_values($values));

        $statement->execute();
        
        return true;
    }

    public function read($table, $columns="*", $query=null, $associate=false)
    {
        $prepare = "SELECT " . $columns . " FROM " . $table; 

        if($query != null)
        {
            $prepare = $prepare . " " . $query;
        }

        $query = $this->query($prepare, $associate);
        $this->connection->close();

        return $query;
    }

    public function count($table, $column = "id")
    {
        $prepare = "SELECT COUNT(" . $column . ") FROM " . $table;
        $query = $this->query($prepare, true);
        $query = $query['COUNT('.$column.')'];
    
        return $query;
    }

    public function update($table, $id, $values = []) {

        $statement = "UPDATE " . $table . " SET ";
        $values['updated_at'] = date("Y-m-d H:i:s");

        foreach ($values as $v => $val) {            
            $i = array_search($v, array_keys($values));

            if(count($values) == $i+1)
            {
                $statement = $statement . "`". $v . "` = '" . $val . "'";
            } else {
                $statement = $statement . "`". $v . "` = '" . $val . "', ";
            }
        }
        $prepare = $statement . " WHERE (`id` = '" . $id . "');";

        $statement = $this->connection->prepare($prepare);

        if($statement == false)
        {
           return header("location: /500");;

            // print_r($this->connection->error);
            // die();
        }

        $exectute = $statement->execute();

        $this->connection->close();

        return true;
    }

    public function delete($table, $id)
    {
        $prepare = "DELETE FROM " . $table . " WHERE id = '" . $id . "'";
        $statement = $this->connection->prepare($prepare);

        if($statement == false)
        {
            return header("location: /500");;

            // var_dump($this->connection->error);
            // die();
        }
        
        $statement->execute();
        $this->connection->close();

        return true;
    }
    
}