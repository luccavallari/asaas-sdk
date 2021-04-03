<?php

namespace Luccavallari\Asaas;

use Luccavallari\Asaas\Connection;
use Luccavallari\Asaas\Exceptions\NfsException;
use Exception;

/**
 * Class Nfs - nota fiscal de serviço
 * @package app\Asaas
 *
 *
    \"name\": \"".((!empty($data['name'])) ? $data['name'] : '')."\",
    \"email\": \"".((!empty($data['email'])) ? $data['email'] : '')."\",
    \"company\": \"".((!empty($data['company'])) ? $data['company'] : '')."\",
    \"phone\": \"".((!empty($data['phone'])) ? $data['phone'] : '')."\",
    \"mobilePhone\": \"".((!empty($data['mobilePhone'])) ? $data['mobilePhone'] : '')."\",
    \"postalCode\": \"".((!empty($data['postalCode'])) ? $data['postalCode'] : '')."\",
    \"address\": \"".((!empty($data['address'])) ? $data['address'] : '')."\",
    \"addressNumber\": \"".((!empty($data['addressNumber'])) ? $data['addressNumber'] : '')."\",
    \"complement\": \"".((!empty($data['complement'])) ? $data['complement'] : '')."\",
    \"province\": \"".((!empty($data['province'] )) ? $data['province'] : '')."\",
    \"city\": \"".((!empty($data['city'])) ? $data['city'] : '')."\",
    \"state\": \"".((!empty($data['state'])) ? $data['state'] : '')."\",
    \"cpfCnpj\": \"".((!empty($data['cpfCnpj'])) ? $data['cpfCnpj'] : '')."\",
    \"additionalEmails\": \"".((!empty($data['additionalEmails'])) ? $data['additionalEmails'] : '')."\",
    \"notificationDisabled\": ".((!empty($data['notificationDisabled']) && $data['notificationDisabled'] == 1) ? 'true' : 'false').",
    \"externalReference\": \"".((!empty($data['externalReference'])) ? $data['externalReference'] : '')."\"
 */

class Nfs
{
    
    public $http;
    protected $cliente;

    public $cli;

    
    public function __construct(Connection $connection)
    {
        $this->http = $connection;
    }
    
    // Agenda uma nota fiscal
    public function create(array $dadosNfs){
        $dadosNfs = $this->setCliente($dadosNfs);
        if(!empty($dadosNfs['error'])){
            return $dadosNfs;
        }else {
            return $this->http->post('/invoices', $dadosNfs);
        }
    }

    // Atualiza nota fiscal
    public function update($id, array $dadosNfs){
        $dadosNfs = $this->setCliente($dadosNfs);
        if(!empty($dadosNfs['error'])){
            return $dadosNfs;
        }else {
            return $this->http->get('/invoices/' . $id, $dadosNfs, 'PUT');
        }
    }
	
	    // Retorna os dados da NFS de acordo com o Id
    public function getById($id){
        return $this->http->get('/invoices/'.$id);
    }

    // Deleta uma NFS
    public function delete($id){
        return $this->http->get('/customers/'.$id,'','DELETE');
    }

    // Retorna a listagem de NFS
    public function getAll($filtros = false){
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
        return $this->http->get('/invoices'.$filtro);
    }


    public function emitir($id)
    {
		$dadosNfs = "";
		return $this->http->post('/invoices/'.$id.'/authorize', $dadosNfs);        
    }
	
	public function cancelar($id)
    {
		$dadosNfs = "";
		return $this->http->post('/invoices/'.$id.'/cancel', $dadosNfs);        
    }

	    // Retorna os dados de configurações municipais
		
    public function getConfigs(){
        return $this->http->get('/customerFiscalInfo/municipalOptions');
    }
	
	public function getInfos(){
        return $this->http->get('/customerFiscalInfo');
    }


    
    /**
     * Faz merge nas informações do cliente.
     * 
     * @see https://asaasv3.docs.apiary.io/#reference/0/clientes/criar-novo-cliente
     * @param Array $cliente
     * @return Array
     */
    public function setNfs($nfs)
    {
        try {
            if ( ! $this->nfs_valid($nfs) ) {
                return NfsException::invalidNfs();
            }

            $this->nfs = array(
    /*  verificar os campos obrigatorios e validar */
				"id" => "",
				"status"=> "",
				"customer"=> "",
				"type"=> "",
				"statusDescription"=> "",
				"serviceDescription"=> "",
				"validationCode"=> null,
				"value"=> "",
				"deductions"=> "",
				"effectiveDate"=> "",
				"observations"=> "",
				"estimatedTaxesDescription"=> "",
				"payment"=> "",
				"installment"=> null,
				"externalReference"=> null,
				"taxes"=> array(
					"retainIss"=> false,
					"iss"=> 3,
					"cofins"=> 3,
					"csll"=> 1,
					"inss"=> 0,
					"ir"=> 1.5,
					"pis"=> 0.65
					),
					"municipalServiceId"=> null,
					"municipalServiceCode"=> "1.01",
					"municipalServiceName"=> "Análise e desenvolvimento de sistemas"
				);
            
            $this->nfs = array_merge($this->nfs, $nfs);
            return $this->nfs;
            
        } catch (Exception $e) {
            return 'Erro ao definir a NFS. - ' . $e->getMessage();
        }
    }
    
    /**
     * Verifica se os dados da nfse são válidos.
     * 
     * @param array $nfs
     * @return Boolean
     */
    public function nfs_valid($nfs)
    {
        return ! ( (empty($nfs['name']) OR empty($nfs['cpfCnpj']) OR empty($nfs['email'])) ? 1 : '' );
    }
}