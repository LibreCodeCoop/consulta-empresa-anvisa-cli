<?php
namespace ConsultaEmpresa\Scrapers;

use ConsultaEmpresa\Consulta\Consulta;

class Prospect extends Scrapper
{
    public function __construct()
    {
        $this->collumnMap['rua']         = 'I';
        $this->collumnMap['bairro']      = 'J';
        $this->collumnMap['numero']      = 'K';
        $this->collumnMap['complemento'] = 'L';
        $this->collumnMap['cep']         = 'M';
        $this->collumnMap['cidade']      = 'N';
        $this->collumnMap['uf']          = 'O';
        $this->collumnMap['telefone']    = 'P';
        $this->collumnMap['status']      = 'Q';
    }

    public function processCnpj(string $cnpj)
    {
        $data = $this->default;
        try {
            $consulta = new Consulta($cnpj);
            $funcionamento = $consulta->consultaFuncionamento(['filter[situacao]' => 'A']);
            if (!$funcionamento) {
                $data['status'] = 'Sem autorizações ativas';
                return $data;
            }
            $newest = [];
            $newest['key'] = 0;
            $newest['data'] = $funcionamento[0]['dataAutorizacao'];
            foreach ($funcionamento as $key => $processo) {
                if (!$processo['ativa']) {
                    continue;
                }
                $response = $consulta->consultaProcesso($processo['numeroProcesso']);
                $funcionamento[$key] = array_merge(
                    $processo,
                    $response
                );
                if ($newest['data'] < $processo['dataAutorizacao']) {
                    $newest['data'] = $processo['dataAutorizacao'];
                    $newest['key'] = $key; 
                }
                $type = $this->getType($response);
                if (!$type) {
                    continue;
                }
                $data[$type] = [
                    'autorizacao' => $processo['autorizacao'],
                    'validade'    => date('d/m/Y', strtotime('+105 days'))
                ];
            }
            if(!isset($type)) {
                $data['status'] = 'Tipos de autorização inválidos';
            }
            if (isset($funcionamento[$newest['key']]['endereco'])) {
                $data = array_merge($data, $funcionamento[$newest['key']]['endereco']);
            }
        } catch (\Exception $e) {
            $data['status'] = $e->getMessage();
        }
        return $data;
    }
}