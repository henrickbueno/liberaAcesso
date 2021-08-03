<?php
    class liberaAcessoEmpresas {

        //estanciar o pdo
        private $pdo;

        //função para conexao com o banco de dados
        public function __construct($dbname, $user, $senha)
        {
            try { //parametros para o PDO = NOME DO BANCO DE DADOS, NOME DO USUARIO (DEFAULT ->ROOT), SENHA (DEFAULT "")
                $this->pdo = new PDO("mysql:dbname=".$dbname,$user,$senha);
            } catch (PDOException $th) {
                echo "ERRO DE CONEXÃO COM O BANCO DE DADOS " . $th->getMessage();
            } catch (Exception $th){
                echo "ERRO GENÉRICO " . $th->getMessage();
            }
        }

        // função para fazer o SELECT no bando de dados
        public function buscaDadosEmpresas()
        {
            $select = $this->pdo->query("SELECT id, name, confirmated_at, created_at  FROM organizations");
            return $select->fetchAll(PDO::FETCH_ASSOC);
        }

        // FUNCAO PARA COLOCAR A DATA DE HOJE NO BANCO DE DADOS
        public function liberaEmpresa($id)
        {   
            $stmt = $this->pdo->prepare("UPDATE organizations SET confirmated_at = NOW() WHERE id = :id");
            return $stmt->execute([
                "id" => $id
            ]);
        }

        //FUNCAO PARA TIRAR A DATA DO BANCO DE DADOS
        public function bloqueiaEmpresa($id)
        {
            $stmt = $this->pdo->prepare("UPDATE organizations SET confirmated_at = null WHERE id = :id");
            return $stmt->execute([
                "id" => $id
            ]);
        }

        // FUNCAO PARA ADICIONAR 7 DIAS
        public function addSeteDias($id)
        {
            $stmt = $this->pdo->prepare('UPDATE organizations SET created_at = NOW() WHERE id = :id');
            return $stmt->execute([
                "id" => $id
            ]);
        }

        public function buscaDadosUsuarios()
        {
            $select = $this->pdo->query("SELECT id, name, email, deleted_at, organization_id FROM users");
            return $select->fetchAll(PDO::FETCH_ASSOC);
        }

        public function bloqueiaAcessoUsuario($id)
        {
            $stmt = $this->pdo->prepare("UPDATE users SET deleted_at = NULL WHERE id = :id");
            return $stmt->execute([
            "id" => $id
            ]);
        }

        public function desbloqueiaAcessoUsuario($id)
        {
            $stmt = $this->pdo->prepare("UPDATE users SET deleted_at = NOW() WHERE id = :id");
            return $stmt->execute([
            "id" => $id
            ]);
        }
    }
