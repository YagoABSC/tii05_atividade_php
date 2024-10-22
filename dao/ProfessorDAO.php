<?php
require_once 'BaseDAO.php';
require_once 'entity/Professor.php';
require_once 'config/Database.php';

class ProfessorDAO implements BaseDAO
{
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getById($id) {
        $sql = "SELECT * FROM Professor WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return new Professor($row['id'], $row['nome'], $row['disciplina_id']);
    }

    public function getAll() {
        $sql = "SELECT * FROM Professor";
        $stmt = $this->db->query($sql);
        $professores = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $professores[] = new Professor($row['id'], $row['nome'], $row['disciplina_id']);
        }
        return $professores;
    }

    public function create($professor) {
        $sql = "INSERT INTO Professor (nome, disciplinaID) VALUES (:nome, :disciplinaID)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome', $professor->getNome());
        $stmt->bindParam(':disciplinaID', $professor->getDisciplinaID());
        $stmt->execute();
    }

    public function update($professor) {
        $sql = "UPDATE Professor SET nome = :nome, disciplinaID = :disciplinaID WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome', $professor->getNome());
        $stmt->bindParam(':disciplinaID', $professor->getDisciplinaID());
        $stmt->bindParam(':id', $professor->getId());
        $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM Professor WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
?>
