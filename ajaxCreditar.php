
<?php    

    require_once 'classeQuery.php';

    $newPdo          = new liberaAcessoEmpresas("organizations", "root", "");
    $resultado       = $_POST;
    $amounthConsumed = $_POST['valor'];
    $dataPacketId    = $_POST['packetId'];
    $orgId           = $_POST['organizationId'];
    $masterProfileId = $newPdo->getProfileMaster($orgId);
    $query           = $newPdo->creditaAmouth($orgId, $masterProfileId, $dataPacketId, $amounthConsumed);
    sleep(1);
    echo json_encode(['resultado' => $resultado]);
    exit;

    
