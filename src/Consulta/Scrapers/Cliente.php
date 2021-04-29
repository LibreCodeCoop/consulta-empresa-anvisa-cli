<?php
namespace ConsultaEmpresa\Scrapers;

use ConsultaEmpresa\Consulta\Consulta;

class Cliente extends Scrapper
{
    public function __construct()
    {
        $this->collumnMap['status']      = 'I';
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
            $valid = false;
            foreach ($funcionamento as $key => $processo) {
                if (!$processo['ativa']) {
                    continue;
                }
                $response = $consulta->consultaProcesso($processo['numeroProcesso']);
                $funcionamento[$key] = array_merge(
                    $processo,
                    $response
                );
                $type = $this->getType($response);
                if (!$type) {
                    continue;
                }
                $valid = true;
                $data[$type] = [
                    'autorizacao' => $processo['autorizacao'],
                    'validade'    => date('d/m/Y', strtotime('+105 days'))
                ];
            }
            if (!$valid) {
                $data['status'] = 'Tipos de autorização inválidos';
            }
        } catch (\Exception $e) {
            $data['status'] = $e->getMessage();
        }
        return $data;
    }
}
