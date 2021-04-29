<?php
namespace ConsultaEmpresa\Scrapers;

use ConsultaEmpresa\Consulta\Consulta;

abstract class Scrapper
{
    protected $default = [
        'correlatos'   => ['autorizacao' => '', 'validade' => ''],
        'medicamentos' => ['autorizacao' => '', 'validade' => ''],
        'saneantes'    => ['autorizacao' => '', 'validade' => ''],
    ];
    protected $collumnMap = [
        'correlatos' => [
            'autorizacao' => 'E',
            'validade'    => 'G'
        ],
        'medicamentos' => [
            'autorizacao' => 'C',
            'validade'    => 'F'
        ],
        'saneantes' => [
            'autorizacao' => 'D',
            'validade'    => 'H'
        ]
    ];

    protected function getType($response)
    {
        // 8 = Produtos para Saúde (Correlatos)
        if ($response['tipoAutorizacao']['codigo'] == 8) {
            return 'correlatos';
        }
        // 3 = Saneantes
        if ($response['tipoAutorizacao']['codigo'] == 3) {
            return 'saneantes';
        }
        // 1 = Medicamentos
        if ($response['tipoAutorizacao']['codigo'] == 1) {
            if (!$response['tipoAutorizacao']['especial']) {
                return 'medicamentos';
            }
        }
    }

    public function write($worksheet, $row, $lastCol, $data)
    {
        if ($data) {
            foreach ($data as $type => $value) {
                if (is_array($value)) {
                    $worksheet->getCell($this->collumnMap[$type]['autorizacao'].$row)->setValue($value['autorizacao']);
                    $worksheet->getCell($this->collumnMap[$type]['validade'].$row)->setValue($value['validade']);
                } else {
                    $worksheet->getCell($this->collumnMap[$type].$row)->setValue($value);
                }
            }
        } else {
            for ($col = 3; $col < $lastCol; $col++) {
                $worksheet->getCellByColumnAndRow($col, $row)->setValue('');
            }
        }
    }
}
