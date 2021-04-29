<?php
namespace ConsultaEmpresa\Scrapers;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class Scrapper
{
    /**
     * @var array
     */
    protected $default = [
        'correlatos'   => ['autorizacao' => '', 'validade' => ''],
        'medicamentos' => ['autorizacao' => '', 'validade' => ''],
        'saneantes'    => ['autorizacao' => '', 'validade' => ''],
    ];
    /**
     * @var array
     */
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

    /**
     * @param array<array> $response
     * @return string
     */
    protected function getType(array $response): string
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
        return '';
    }

    public function write(Worksheet $worksheet, int $row, int $lastCol, array $data): void
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
