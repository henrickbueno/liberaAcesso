<?php
    require_once 'classeLiberaEmpresas.php';

    $newPdo     = new liberaAcessoEmpresas("organizations", "root", "");
    $listaDeEmpresas = $newPdo->buscaDadosEmpresas();
    
    if (isset($_POST['id'])) {
        if ($_POST['action'] == 'bloquear') {
            $resultado = $newPdo->bloqueiaEmpresa($_POST['id']);
        } else{
            $resultado = $newPdo->liberaEmpresa($_POST['id']);
        }
        if ($_POST['action'] == 'adicionar') {
            $resultado = $newPdo->addSeteDias($_POST['id']);
        } 
        sleep(1);
        echo json_encode(['resultado' => $resultado]);
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <link rel="stylesheet" href="style.css">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Acesso - Empresas</title>
    </head>
    <body>
        <nav aria-label="breadcrumb" class="col-12 cabecalho">
            <img src="imagens/Favicon-Corbee-152.png" alt="bg" class="imgBg">
            <ol class="breadcrumb menu">
                <li><a href="#">Lista de Empresas</a></li>
            </ol>
        </nav>
        
        <div class="card-body container">
            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th> NOME </th>
                        <th> STATUS </th>
                        <th style="text-align: center;">  PR&Oacute;XIMO BLOQUEIO DO TESTE: </th>
                        <th> <!-- ESPAÇO VAZIO PARA MANTER LAYOUT --></th>
                    </tr>
                </thead> <?php 
                foreach ($listaDeEmpresas as $indice => $empresa) { ?>
                    <tr>
                        <?php 
                            // COLUNA NOME DAS EMPRESAS
                            if (!empty($empresa['name'])) { ?>
                                <td><?= $empresa['name'] ?></td> <?php 
                            }
                            //  COLUNA ICONES DE STATUS
                            if (!empty($empresa['confirmated_at'])) {?>
                                <td style="text-align: center;"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        width="16" height="16" fill="currentColor" 
                                        class="bi bi-check-circle-fill color-icon" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                </td>
                                <!-- COLUNA DO PROXIMO BLOQUEIO -->
                                <td style="text-align: center;"> 
                                    <?php 
                                        if (isset($empresa['created_at'])) {
                                            echo date('d/m/Y', strtotime('+ 7 days', strtotime($empresa['created_at'])));
                                        }else {
                                            echo '-';
                                        }  ?>
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-danger bloquearAcesso" data-id="<?php echo $empresa['id']; ?>">BLOQUEAR ACESSO</button>
                                    <button class="btn btn-warning adicionaSete" data-id="<?php echo $empresa['id']; ?>">Liberar Teste</button>
                                    <a href="listaDeUsuarios.php?empresa_id=<?php echo $empresa['id']; ?>&empresa_name=<?php echo $empresa['name']; ?>">
                                        <button class="btn btn-dark" style="padding: 0px;" data-id="<?php echo $empresa['id']; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16" style="color: #fff;">
                                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                            </svg>
                                        </button>
                                    </a>
                                </td><?php 
                            } else { ?>
                                <td style="text-align: center;"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg icon-block" viewBox="0 0 16 16">
                                        <path d="M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z"/>
                                    </svg>
                                </td>
                                <!-- COLUNA DO PROXIMO BLOQUEIO -->
                                <td style="text-align: center;"> 
                                    <?php 
                                        if (isset($empresa['created_at'])) {
                                            echo date('d/m/Y', strtotime('+ 7 days', strtotime($empresa['created_at'])));
                                        }else {
                                            echo '-';
                                        }  ?>
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-success liberarAcesso" data-id="<?php echo $empresa['id']; ?>">LIBERAR ACESSO</button>
                                </td> <?php 
                            } 
                        ?>
                    </tr> <?php 
                } ?>
            </table>  
        </div>

        
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="main.js"></script>
    </body>
</html>


