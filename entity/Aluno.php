<?php
class Aluno {
    private $matricula;
    private $nome;
   
    // Implemente o array de discplinas que será utilizado pela entidade Aluno
    private $disciplinas = [];

    public function __construct($matricula, $nome) {
        $this->matricula = $matricula;
        $this->nome = $nome;
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    // Implemente os getters e setters para as ler e inserir um array de discplinas no objeto aluno
    public function getDisciplinas() {
        return $this->disciplinas;
    }

    public function setDisciplinas($disciplinas) {
        $this->disciplinas = $disciplinas;
    }

}
?>
