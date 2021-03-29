<?php

namespace Luccavallari\Asaas;

use Luccavallari\Asaas\Assinatura;
use Luccavallari\Asaas\Cliente;
use Luccavallari\Asaas\Cobranca;
use Luccavallari\Asaas\Notificacao;
use Luccavallari\Asaas\Transferencia;
use Luccavallari\Asaas\Webhook;

class Asaas {
    
    public $cidade;
    public $assinatura;
    public $cliente;
    public $cobranca;
    public $notificacao;
    public $transferencia;
    public $webhook;
    
    public function __construct($token, $status = false) {
        $connection = new Connection($token, ((!empty($status)) ? $status : 'producao'));

        $this->assinatura  = new Assinatura($connection);
        $this->cidade      = new Cidades($connection);
        $this->cliente     = new Cliente($connection);
        $this->cobranca    = new Cobranca($connection);
        $this->notificacao = new Notificacao($connection);
        $this->transferencia = new Transferencia($connection);
        $this->webhook     = new Webhook($connection);
    }
}
