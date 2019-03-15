<?php

namespace ConsultaEmpresa\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Oasis\Parser;
use Bissolli\ValidadorCpfCnpj\Documento;

class ConsultaCommand extends Command
{
    private $validList = [];
    private $invalidList = [];
    protected function configure()
    {
        $this
            ->setName('consulta')
            ->setDescription('Realiza consulta de CNPJ.')
            ->setDefinition([
                new InputArgument('cnpj', InputArgument::REQUIRED, 'Lista de CNPJ separada por vírgula')
            ])
            ->setHelp(<<<HELP
                O comando <info>consulta</info> realiza consulta de empresa.
                HELP
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $lista = explode(',', $input->getArgument('cnpj'));
        $this->validateCnpj($lista);
        if ($this->invalidList) {
            $output->writeln('<error>Inválidos:</error> '.implode(',', $this->invalidList));
            return 1;
        }
    }

    private function validateCnpj(array $lista)
    {
        foreach($lista as $cnpj) {
            $document = new Documento($cnpj);
            if(!$document->isValid()) {
                $this->invalidList[] = $cnpj;
            } else {
                $this->validList[] = $document->getValue();
            }
        }
    }
}
