<?php
    require_once 'classeQuery.php';

    $newPdo          = new liberaAcessoEmpresas("organizations", "root", "");
    $listaDeEmpresas = $newPdo->buscaDadosEmpresas();
    $packets         = $newPdo->getPackets();
    
    // echo '<pre>';
    // var_dump($packets);
    // var_dump($pacote["name"]);
    // echo '</pre>';
    // exit;
    
    if (isset($_POST['id'])) {
        if ($_POST['action'] == 'bloquear') {
            $resultado = $newPdo->bloqueiaEmpresa($_POST['id']);
        } else{
            $resultado = $newPdo->liberaEmpresa($_POST['id']);
        }
        if ($_POST['action'] == 'adicionar') {
            $resultado = $newPdo->addSeteDias($_POST['id']);
        } 
        // sleep(1);
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
        
        <!-- INICIO DO MODAL -->
        
        <div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Pacotes de Cr&eacute;ditos</h5>
                    </div>
                    <div class="modal-body">
                        <Select id="pacotes" style="width: 45%;">
                            <option value="Escolha o Plano" selected>Escolha o Plano</option>
                            <?php foreach ($packets as $indice => $pacote) {?>
                                <option value="<?php echo $pacote['id'] . ";" . $pacote['qty'] ?>"><?php echo $pacote["name"] ?></option>
                            <?php } ?>
                        </Select>
                        <label for="creditosTotais" style="margin-left: 10%;">Cr&eacute;ditos Totais:</label>
                        <input type="number" id="creditosTotais" value="">
                        <input type="hidden" id="organizationId" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-success creditar" id="btn-creditar">Creditar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- FIM DO MODAL -->
        
        <div class="card-body container">
            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th> NOME </th>
                        <th> STATUS </th>
                        <th style="text-align: center;">  PR&Oacute;XIMO BLOQUEIO</th>
                        <th style="text-align: center;">CR&Eacute;DITOS</th>
                        <th> <!-- ESPAÇO VAZIO PARA MANTER LAYOUT --></th>
                    </tr>
                </thead> <?php 
                foreach ($listaDeEmpresas as $indice => $empresa) { ?>
                    
                    <tr>
                        <?php 
                            // COLUNA ID E NOME DAS EMPRESAS
                            if (!empty($empresa['name'])) { ?>
                                <td><?= $empresa['id'] ?></td>
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
                                        }  
                                    ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php 
                                        $creditoSms = $newPdo->getCreditosRestantes($empresa['id']);
                                        $hasCredits = false;
                                        foreach ($creditoSms as $key => $value) {
                                            if (!empty($value['type']) && $value['type'] == 'S') { ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z"/>
                                                </svg> <?php 
                                                echo $value['total']; 
                                                $hasCredits = true;
                                            }
                                            if (!empty($value['type']) && $value['type'] == 'Q') { ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                                </svg> <?php
                                                echo $value['total']; 
                                                $hasCredits = true;
                                            }
                                        }
                                        if (!$hasCredits) {
                                            echo "-";
                                        }
                                    ?>
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
                                    <button type="button" class="btn btn-success btnCreditar" data-id="<?php echo $empresa['id']; ?>" data-toggle="modal" data-target="#exampleModalCenter">
                                        Cr&eacute;ditos
                                    </button>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="main.js"></script>
    </body>
</html>
<script>
    // funcao para abrir o modal
    $(".btnCreditar").click(function () {
        $('#organizationId').val($(this).attr('data-id'));
    })

    // funcao para enviar o valor do select para o campo total de creditos
    $('#pacotes').change(function () {
        var idQty   = document.getElementById('pacotes').value;
        var qty     = idQty.split(';');
            if (qty[1]) {
                document.getElementById('creditosTotais').value = qty[1];
            }else{
                document.getElementById('creditosTotais').value = '';
            }
   })

    $("#btn-creditar").click(function () {
        var orgId       = document.getElementById('organizationId').value;
        var idQty       = document.getElementById('pacotes').value;
        var qty         = idQty.split(';');
        var id          = qty[0];
        var valorPacote = qty[1];
        var valor       = document.getElementById('creditosTotais').value;
        if (valor > valorPacote) {
            alert('O Valor creditado precisa ser menor do que o valor do pacote!');
            return;
        }
        if (valor == valorPacote) {
            ajaxCredito(id, 0, orgId, $('#btn-creditar').hasClass('creditar')? 'creditar' : 'parar');
            return;    
        }
        if (valor < valorPacote) {
            var valorAlterado = valorPacote - valor;
            ajaxCredito(id, valorAlterado, orgId, $('#btn-creditar').hasClass('creditar')? 'creditar' : 'parar');
            return;    
        }
    })

    // Ajax 
    function ajaxCredito(packetId, valor, organizationId, action) {
        $.ajax({
            url: 'ajaxCreditar.php',
            type: 'post',
            dataType: 'json',
            data: {packetId, valor, organizationId, action},
            beforeSend: function () {
                if ($('#btn-creditar').hasClass('creditar')) {
                    $('#btn-creditar').html('Creditando')
                    $('#btn-creditar').attr("disabled", true)
                }
            },
            success: function (response) {
                console.log(response.masterProfileId);
                location.reload();  
            },
            error: function () {
                alert('Deu erro!');
            },
            complete: function() {
                if ($('#btn-creditar').hasClass('creditar')) {
                    $('#btn-creditar').html('Creditar')
                    $('#btn-creditar').attr("disabled", true)
                    alert('Creditado com sucesso!')
                }
            }
        });
    }
    
</script>

