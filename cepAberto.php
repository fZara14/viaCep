
<?php

function getAddress()
{
    if (isset($_POST['cep'])) {
        $cep = $_POST['cep'];

        $cep = filterCep($cep);
      


        if (isCep($cep)) {
            $address = getAddressCepAberto($cep);
            if (property_exists($address, 'erro')) {
                $address = addressEmpty();
                $address->cep = 'CEP não encontrado...';
            }
        } else {
            $address = addressEmpty();
            $address->cep = 'CEP INVÁLIDO!';
        }
    } else {
        $address = addressEmpty();
    }
    $cepAberto =  (object) [
        'cep' => $address->cep,
        'logradouro' => $address->logradouro,
        'bairro' => $address->bairro,
        'localidade' => $address->cidade->nome,
        'uf' => $address->estado->sigla
    ];
    return $cepAberto;
}

function addressEmpty()
{
    return (object) [
        'cep' => '',
        'logradouro' => '',
        'bairro' => '',
        'cidade' => '',
        'uf' => ''
    ];
}

function filterCep(string $cep): string
{
    return preg_replace('/[^0-9]/', '', $cep);
}

function isCep(string $cep): bool
{
    return preg_match('/^[0-9]{5}-?[0-9]{3}$/', $cep);
}

function getAddressCepAberto(string $cep)
{
    $url ="http://www.cepaberto.com/api/v3/cep?cep={$cep}";

    $opts = array(
        'http'=>array(
            'method' => 'GET',
            'header' => 'Authorization: Token token=198de5c72775fd46915416aaa0f2eeb1'
        )
        );

    $context = stream_context_create($opts);   
    return json_decode(file_get_contents($url,false,$context));
}