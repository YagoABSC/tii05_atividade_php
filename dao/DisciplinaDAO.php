<?php
require_once 'BaseDAO.php';
require_once 'entity/Disciplina.php';
require_once 'entity/Aluno.php';
require_once 'entity/Professor.php';
require_once 'config/Database.php';

class DisciplinaDAO implements BaseDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM Disciplina WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return new Disciplina($row['id'], $row['nome'], $row['carga_horaria']);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM Disciplina";
        $stmt = $this->db->query($sql);
        $disciplinas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $disciplinas[] = new Disciplina($row['id'], $row['nome'], $row['carga_horaria']);
        }
        return $disciplinas;
    }

    public function create($disciplina)
    {
        $sql = "INSERT INTO Disciplina (nome, chave, carga_horaria) VALUES (:nome, :chave, :carga_horaria)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome', $disciplina->getNome());
        $stmt->bindParam(':chave', $disciplina->getChave());
        $stmt->bindParam(':carga_horaria', $disciplina->getCargaHoraria());
        $stmt->execute();
    }

    public function update($disciplina)
    {
        $sql = "UPDATE Disciplina SET nome = :nome, chave = :chave, carga_horaria = :carga_horaria WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome', $disciplina->getNome());
        $stmt->bindParam(':chave', $disciplina->getChave());
        $stmt->bindParam(':carga_horaria', $disciplina->getCargaHoraria());
        $stmt->bindParam(':id', $disciplina->getId());
        $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM Disciplina WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Método para obter disciplina com seus alunos
    public function getDisciplinaWithAlunos($disciplinaID)
    {
        /*
        Implemnte o retorno de uma disciplina, contendo seus respectivos alunos
        */

        $sql = "SELECT 
        d.id AS d_id, 
        d.nome AS d_nome, 
        d.carga_horaria AS d_cargaHoraria,
        a.matricula AS a_matricula, 
        a.nome AS a_nome
        FROM disciplina d
        JOIN disciplina_aluno da ON d.id = da.disciplina_id
        JOIN aluno a ON da.aluno_id = a.matricula
        WHERE d.id = :disciplina_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':disciplina_id', $disciplinaID);
        $stmt->execute();

        $disciplinaID = null;
        $aluno = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            if($disciplinaID === null){
                $disciplinaID = new Disciplina(
                    $row['d_id'],
                    $row['d_nome'],
                    $row['d_cargaHoraria']
                );
            }

            $aluno[] = new Aluno(
                $row['a_matricula'],
                $row['a_nome']
            );
        }

        if($disciplinaID !== null){
            $disciplinaID->setAlunos($aluno);
        }

        return $disciplinaID;
    }

    // Método para obter os professores da disciplina
    public function getProfessoresForDisciplina($disciplinaID)
    {
        $sql = "SELECT p.id, p.nome, p.disciplina_id FROM professor p
            WHERE p.disciplina_id = :disciplinaID";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':disciplinaID', $disciplinaID);
        $stmt->execute();

        $professores = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $professores[] = new Professor($row['id'], $row['nome'], $row['disciplina_id']);
        }

        return $professores;
    }
}
