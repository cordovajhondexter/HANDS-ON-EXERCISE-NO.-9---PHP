<?php

class Faculty {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create($data) {

        $sql = "INSERT INTO faculty
                (first_name, middle_name, last_name, age, gender, address, position, salary)
                VALUES
                (:first_name, :middle_name, :last_name, :age, :gender, :address, :position, :salary)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute($data);
    }

    // READ
    public function getAll() {

        $sql = "SELECT * FROM faculty ORDER BY faculty_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt;
    }

    // GET ONE
    public function getById($id) {

        $sql = "SELECT * FROM faculty
                WHERE faculty_id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function update($data) {

        $sql = "UPDATE faculty SET
                first_name = :first_name,
                middle_name = :middle_name,
                last_name = :last_name,
                age = :age,
                gender = :gender,
                address = :address,
                position = :position,
                salary = :salary
                WHERE faculty_id = :faculty_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute($data);
    }

    // DELETE
    public function delete($id) {

        $sql = "DELETE FROM faculty
                WHERE faculty_id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}
?>