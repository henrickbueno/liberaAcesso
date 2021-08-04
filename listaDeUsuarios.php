<?php
    require_once 'classeLiberaEmpresas.php';

    $newPdo = new liberaAcessoEmpresas("organizations", "root", "");
    $listaDeEmpresas = $newPdo->buscaDadosUsuarios();
    
    if (isset($_POST['id'])) {
        if ($_POST['action'] == 'bloquear') {
            $resultado = $newPdo->desbloqueiaAcessoUsuario($_POST['id']);
        }else {
            $resultado = $newPdo->bloqueiaAcessoUsuario($_POST['id']);    
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
        <title>Acesso - Usuarios</title>
    </head>
    <body>
        <nav aria-label="breadcrumb" class="col-12 cabecalho">
            <img src="imagens/Favicon-Corbee-152.png" alt="bg" class="imgBg">
            <ol class="breadcrumb menu">
                <li><a href="menu.php">Lista de Empresas</a></li>
            </ol>
        </nav>
        <div class="card-body container">
        <span class="badge text-dark" id="badge">Empresa: <?php echo ucfirst($_GET['empresa_name']); ?></span>
            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" style="text-align: center;"> NOME </th>
                        <th scope="col" style="text-align: center"> EMAIL </th>
                        <th scope="col" > <!----> </th>
                        <th scope="col" > <!----> </th>
                    </tr>
                </thead> <?php 
                foreach ($listaDeEmpresas as $indice => $usuario) {
                    if ($usuario['organization_id'] == $_GET['empresa_id']){?>
                        <tr>
                            <td style="text-align: center"> <?php echo $usuario['name'] ?></td>
                            <td style="text-align: center"> <?php echo $usuario['email'] ?></td>
                            <td style="text-align: right">
                                <?php 
                                    if ($usuario["deleted_at"] == null) {?>
                                        <button class="btn btn-danger btn-usuario-inativar" data-id="<?php echo $usuario['id']; ?>">INATIVAR</button><?php
                                    } else { ?>
                                        <button class="btn btn-success btn-usuario-ativar" data-id="<?php echo $usuario['id']; ?>">ATIVAR</button><?php
                                    } 
                                ?>
                            </td>
                        </tr> <?php
                    }         
                } ?>
            </table>  
        </div>

        
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="main.js"></script>
    </body>
</html>
<script>
    $('.btn').click(function () {
        var id = $(this).attr('data-id');
        ajaxDadosUsuario(id, $(this).hasClass('btn-usuario-inativar') ? 'bloquear' : 'liberar', $(this))
    });

    function ajaxDadosUsuario(id, action, btn) {
        $.ajax({
            url : 'listaDeUsuarios.php',
            type: 'post',
            dataType: 'json',
            data:{id , action},
            beforeSend: function () {
                if (btn.hasClass('btn-usuario-inativar')) {
                    btn.html('INATIVANDO...')
                    btn.attr("disabled", true)
                }else{
                    btn.html('ATIVANDO...')
                    btn.attr("disabled", true)
                }
            },
            success: function (response) {
                if (response.resultado == true) {
                    alert("Acao realizada com sucesso!");
                    location.reload();
                }else{
                    alert('Nao foi possível completar o processo');
                }
                console.log(response);
            },
            error: function () {
                alert('nao funcionou');
            },
            complete: function () {
                if (btn.hasClass('btn-usuario-inativar')) {
                    btn.html('INATIVAR')
                    btn.attr("disabled", false)
                }
            }
        });
    }
</script>