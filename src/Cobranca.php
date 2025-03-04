<?php

namespace Luccavallari\Asaas;

use Luccavallari\Asaas\Connection;

class Cobranca {
    public $http;
    protected $cobranca;
    
    public function __construct(Connection $connection)
    {
        $this->http = $connection;
    }

    // Retorna a listagem de cobranças
    public function getAll(array $filtros){
        $filtro = '';
        if(is_array($filtros)){
            if($filtros){
                foreach($filtros as $key => $f){
                    if(!empty($f)){
                        if($filtro){
                            $filtro .= '&';
                        }
                        $filtro .= $key.'='.$f;
                    }
                }
                $filtro = '?'.$filtro;
            }
        }
        return $this->http->get('/payments'.$filtro);
    }

    // Retorna os dados da cobrança de acordo com o Id
    public function getById($id){
        return $this->http->get('/payments/'.$id);
    }

    // Retorna a listagem de cobranças de acordo com o Id do Cliente
    public function getByCustomer($customer_id){
        return $this->http->get('/payments?customer='.$customer_id);
    }

    // Retorna a listagem de cobranças de acordo com o Id da Assinaturas
    public function getBySubscription($subscription_id){
        return $this->http->get('/payments?subscription='.$subscription_id);
    }

    // Insere uma nova cobrança
    public function create(array $dadosCobranca){
        $dadosCobranca = $this->setCobranca($dadosCobranca);
        if(!empty($dadosCobranca['error'])){
            return $dadosCobranca;
        }else {
            return $this->http->post('/payments', $dadosCobranca);
        }
    }

    // Insere uma nova cobrança parcelada
    public function parcelada(array $dadosCobranca){

    }

    // Insere uma nova cobrança com split
        /* Saldo dividido em multiplas contas do Asaas*/
    public function split(array $dadosCobranca){

    }
	
	// Retorna as parcelas da cobrança de acordo com o ID do installment 
    public function getParcelas($id){
        return $this->http->get('/payments?installment='.$id);
    }

    // Atualiza os dados da cobrança
    public function update($id, array $dadosCobranca){

        return $this->http->post('/payments/' . $id, $dadosCobranca);
    }

    // Restaura cobrança removida
    public function restore($id){

    }

    // Estorna cobrança
    public function estorno($id){

    }

    // Confirmação em dinheiro
    public function confirmacao($id, $dados){
        $data = array(
            "paymentDate" => "2019-09-03",
            "value" => 100.00,
        );
        return $this->http->post('/customers', $dados);
    }

    // Deleta uma cobrança
    public function delete($id){
        return $this->http->get('/payments/'.$id,'','DELETE');
    }

    /**
     * Cria um novo boleto no Asaas.
     * @param Array $cliente
     * @return Boolean
     */
    public function create2($dados)
    {
        // Preenche as informações da cobranca
        $cobranca = $this->setCobranca($dados);
        
        // Faz o post e retorna array de resposta
        return $this->http->post('/payments', ['form_params' => $cobranca]);
    }

    /**
     * Faz merge nas informações das cobranças.
     *
     * @see https://asaasv3.docs.apiary.io/#reference/0/cobrancas/criar-nova-cobrancas
     * @param Array $cliente
     * @return Array
     */
    public function setCobranca($dados)
    {
        try {
            $this->cobranca = array(
                'customer'             => '',
                'billingType'          => '',
                'value'                => '',
                'dueDate'              => '',
                'description'          => '',
                'externalReference'    => '',
                'installmentCount'     => '',
                'installmentValue'     => '',
                'discount'             => '',
                'interest'             => '',
                'fine'                 => '',
            );

            $this->cobranca = array_merge($this->cobranca, $dados);
            return $this->cobranca;

        } catch (Exception $e) {
            return 'Erro ao definir o cliente. - ' . $e->getMessage();
        }
    }

    /**
     * Faz merge nas informações das cobranças.
     *
     * @see https://asaasv3.docs.apiary.io/#reference/0/cobrancas/criar-nova-cobrancas
     * @param Array $cliente
     * @return Array
     */
    public function setCobrancaCartao($dados)
    {
        try {
            $this->cobranca = array(
                'customer'             => '',
                'billingType'          => '',
                'value'                => '',
                'dueDate'              => '',
                'description'          => '',
                'externalReference'    => '',
                'installmentCount'     => '',
                'installmentValue'     => '',
                'discount'             => '',
                'interest'             => '',
                'fine'                 => '',
            );

            $this->cobranca = array_merge($this->cobranca, $dados);
            return $this->cobranca;

        } catch (Exception $e) {
            return 'Erro ao definir o cliente. - ' . $e->getMessage();
        }
    }
}
