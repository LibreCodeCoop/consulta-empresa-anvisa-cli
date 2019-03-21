<?php

namespace ConsultaEmpresa\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use ConsultaEmpresa\Scrapers\Cliente;
use ConsultaEmpresa\Scrapers\Prospect;

class ConsultaCommand extends Command
{
    /**
     * @var Cliente|Prospect $processor
     */
    private $processor;
    protected function configure()
    {
        $this
            ->setName('consulta')
            ->setDescription('Realiza consulta de CNPJ.')
            ->setDefinition([
                new InputOption('clientes', 'c', InputArgument::OPTIONAL, 'Arquivo de clientes'),
                new InputOption('prospects', 'p', InputArgument::OPTIONAL, 'Arquivo de prospects')
            ])
            ->setHelp(<<<HELP
                O comando <info>consulta</info> realiza consulta de empresa.
                HELP
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $clientes = $input->getOption('clientes');
        $prospects = $input->getOption('prospects');
        if (!file_exists($clientes) && !file_exists($prospects)) {
            $output->writeln('<error>Arquivo de Cliente ou Prospect necessário</error>');
            return 1;
        }
        if ($clientes) {
            $this->process($clientes, 'cliente');
        }
        if ($prospects) {
            $this->process($prospects, 'prospect');
        }
    }

    private function process($filename, $type)
    {
        $className = 'ConsultaEmpresa\Scrapers\\'.ucfirst($type);
        $this->processor = new $className();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);
        $worksheet = $spreadsheet->getActiveSheet();
        // Cria coluna de status
        $lastCol = Coordinate::columnIndexFromString($worksheet->getHighestColumn());
        $worksheet->getCellByColumnAndRow($lastCol, 1)->setValue('Status');
        $highestRow = $worksheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; ++$row) {
            $cnpj = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
            $cnpj = str_pad($cnpj, 14, 0, STR_PAD_LEFT);
            $data = $this->processor->processCnpj($cnpj);
            $this->processor->write($worksheet, $row, $lastCol, $data);
            if($row == 30) break;
        }
        $this->saveFile($spreadsheet, $filename);
    }

    private function saveFile($spreadsheet, $filename)
    {
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = explode(DIRECTORY_SEPARATOR, $filename);
        $key = array_key_last($filename);
        $filename[$key] = 'output-'.$filename[$key];
        $filename = implode(DIRECTORY_SEPARATOR, $filename);
        $writer->save($filename);
    }
}
