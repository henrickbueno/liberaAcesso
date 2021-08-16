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

        //QUERY PARA BUSCAR OS PACOTES DE CREDITOS
        public function getPackets()
        {
            $packets = $this->pdo->query("SELECT id, name, qty, unitary_value, total_value, type, created_at, updated_at FROM data_packets");
            return $packets->fetchAll(PDO::FETCH_ASSOC);
        }
        // QUERY PARA BUSCAR O ID DO PERFIL MASTER DA EMPRESA
        public function getProfileMaster($id)
        {
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE profile_id IN (SELECT id FROM profiles WHERE name = 'Master' AND organization_id = :id) LIMIT 1");
            $stmt->execute([
                "id" => $id
            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        }

        //QUERY PARA TRAZER OS CREDITOS DAS EMPRESAS
        public function getCreditosRestantes($id)
        {
            $stmt = $this->pdo->prepare("SELECT SUM(qty - amount_consumed) AS total, type
                                        FROM organization_data_packets AS ODP 
                                        INNER JOIN data_packets AS DP ON ODP.data_packet_id = DP.id 
                                        WHERE organization_id = :id
                                        GROUP BY DP.type;");
            $stmt->execute([
                "id" => $id
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } 

        //QUERY PARA INSERIR A COMPRA DE CREDITOS NO BANCO DE DADOS
        public function creditaAmouth($organizationID, $user, $dataPacketId, $amountConsumed)
        {
            $stmt = $this->pdo->prepare("INSERT INTO organization_data_packets (
                                                            organization_id, 
                                                            user_id, 
                                                            data_packet_id, 
                                                            amount_consumed, 
                                                            finished, 
                                                            created_at, 
                                                            updated_at) 
                                                            VALUES (:organization_id, :user, :data_packet_id, :amount_consumed, '0', NOW(), NOW());");
            return $stmt->execute([
                "organization_id"   => $organizationID,
                "user"              => $user,
                "data_packet_id"    => $dataPacketId,
                "amount_consumed"   => $amountConsumed
            ]);
        }

    }
