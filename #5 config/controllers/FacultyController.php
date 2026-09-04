<?php

class FacultyController {

    private $faculty;

    public function __construct($faculty) {
        $this->faculty = $faculty;
    }

    public function index() {
        return $this->faculty->getAll();
    }

    public function create($data) {
        return $this->faculty->create($data);
    }

    public function getById($id) {
        return $this->faculty->getById($id);
    }

    public function update($data) {
        return $this->faculty->update($data);
    }

    public function delete($id) {
        return $this->faculty->delete($id);
    }
}
?>