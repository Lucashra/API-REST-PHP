<?php 

use Util\RotasUtil;
use Util\ConstantesGenericasUtil as Constantes;
use Validator\RequestValidator;

include 'bootstrap.php';

try {
    $RequestValidator = new RequestValidator(RotasUtil::getRotas());
    $retorno = $RequestValidator->processarRequest(); 

} catch (Exception $exception) {;
    echo json_encode([
        Constantes::TIPO => Constantes::TIPO_ERRO,
        Constantes::RESPOSTA => $exception->getMessage
    ]);

}   