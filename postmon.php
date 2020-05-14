
<?php

function getAddress()
{
    if (isset($_POST['cep'])) {
        $cep = $_POST['cep'];

        $cep = filterCep($cep);
      


        if (isCep($cep)) {
            $address = getAddressViaCep($cep);
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
    $postmon =  (object) [
        'cep' => $address->cep,
        'logradouro' => $address->logradouro,
        'bairro' => $address->bairro,
        'localidade' => $address->cidade,
        'uf' => $address->estado
    ];
    return $postmon;
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

function getAddressViaCep(string $cep)
{
    $url ="https://api.postmon.com.br/v1/cep/{$cep}";
    return json_decode(file_get_contents($url));
}