<?php

namespace Luccavallari\Asaas\Exceptions;

class NfsException {

    public static function invalidNfs()
    {
        return array('error'=>'Os dados Obrigatorio são Nome, Cpf\Cnpj, E-mail, Os dados fornecidos para o cadastro do cliente não são válidos.');
    }
}
