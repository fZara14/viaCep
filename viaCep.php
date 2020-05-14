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
    return $address;
}

function addressEmpty()
{
    return (object) [
        'cep' => '',
        'logradouro' => '',
        'bairro' => '',
        'localidade' => '',
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
    $url = "https://viacep.com.br/ws/{$cep}/json/";
    return json_decode(file_get_contents($url));
}