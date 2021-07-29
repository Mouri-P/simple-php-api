<?php

class Employees
{
    private $connection;
    private $table = "employees";

    // table field names
    private $id = "id";
    private $name = "name";
    private $department = "department";
    private $roll = "roll";
    private $join_date = "join_date";

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function read()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$this->name}";

        $statement = $this->connection->prepare($sql);
        $statement->execute();
        return $statement;
    }

    public function create($input_arr)
    {

        $sql = "INSERT INTO `{$this->table}` (`{$this->name}`, `{$this->department}`, `{$this->roll}`, `{$this->join_date}`) VALUES (:emp_name, :department, :roll, :join_date)";
        $statement = $this->connection->prepare($sql);

        $statement->bindParam(':emp_name', $input_arr['name']);
        $statement->bindParam(':department', $input_arr['department']);
        $statement->bindParam(':roll', $input_arr['roll']);
        $statement->bindParam(':join_date', $input_arr['join_date']);

        return $statement->execute() ? true : false;
    }

    public function update($input_arr)
    {
        $sql = "UPDATE `{$this->table}` SET `{$this->name}` = :emp_name, `{$this->department}` = :department, `{$this->roll}` = :roll, `{$this->join_date}` = :join_date WHERE (`{$this->id}` = :id)";
        $statement = $this->connection->prepare($sql);

        $statement->bindParam(':emp_name', $input_arr['name']);
        $statement->bindParam(':department', $input_arr['department']);
        $statement->bindParam(':roll', $input_arr['roll']);
        $statement->bindParam(':join_date', $input_arr['join_date']);
        $statement->bindParam(':id', $input_arr['id']);

        return $statement->execute() ? true : false;
    }

    public function delete($input_arr)
    {
        $sql = "DELETE FROM {$this->table} WHERE ({$this->id} = :id)";
        
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id', $input_arr['id']);

        return $statement->execute() ? true : false;
    }
}
