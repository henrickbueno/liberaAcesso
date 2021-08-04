<?php

class login {

    private $pdo;

    public function __construct($dbname , $user, $key)
    {
        try { //parametros para o PDO = NOME DO BANCO DE DADOS, NOME DO USUARIO (DEFAULT ->ROOT), SENHA (DEFAULT "")
            $this->pdo = new PDO("mysql:dbname=".$dbname,$user,$key);
        } catch (PDOException $th) {
            echo "ERRO DE CONEX�O COM O BANCO DE DADOS " . $th->getMessage();
        } catch (Exception $th){
            echo "ERRO GEN�RICO " . $th->getMessage();
        }
    }

    // fun��o para fazer o SELECT no bando de dados
    public function buscaUsuariosLogin()
    {
        $select = $this->pdo->query("SELECT * FROM usuario");
        return $select->fetchAll(PDO::FETCH_ASSOC);
    }
}