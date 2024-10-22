<?php
require_once 'BaseDAO.php';
require_once 'entity/Aluno.php';
require_once 'entity/Disciplina.php';
require_once 'config/Database.php';

class AlunoDAO implements BaseDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM Aluno WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return new Aluno($row['matricula'], $row['nome']);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM Aluno";
        $stmt = $this->db->query($sql);
        $alunos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $alunos[] = new Aluno($row['matricula'], $row['nome']);
        }
        return $alunos;
    }

    public function create($aluno)
    {
        $sql = "INSERT INTO Aluno (nome) VALUES (:nome)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome', $aluno->getNome());
        $stmt->execute();
    }

    public function update($aluno)
    {
        $sql = "UPDATE Aluno SET nome = :nome WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome', $aluno->getNome());
        $stmt->bindParam(':id', $aluno->getId());
        $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM Aluno WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Método para obter aluno com suas disciplinas
    public function getAlunoWithDisciplinas($alunoID)
    {
        /*
        Retorne a implementação de um objeto do tipo aluno, contendo suas respectivas disciplinas
         */

        $sql="SELECT a.matricula AS a_matricula, 
        a.nome AS a_nome, 
        d.id AS d_id, 
        d.nome AS d_nome, 
        d.carga_horaria AS d_cargaHoraria
        FROM aluno a
        JOIN disciplina_aluno da ON a.matricula = da.aluno_id
        JOIN disciplina d ON da.disciplina_id = d.id
        WHERE a.matricula = :aluno_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':aluno_id', $alunoID);
        $stmt->execute();

        $alunoID = null;
        $disciplina = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($alunoID === null){
                $alunoID = new Aluno(
                    $row['a_matricula'],
                    $row['a_nome']
                );                
            }

            $disciplina[] = new Disciplina(
                $row['d_id'],
                $row['d_nome'],
                $row['d_cargaHoraria']
            );
        }

        if($alunoID !== null){
            $alunoID->setDisciplinas($disciplina);
        }

        return $alunoID;
    }
}
