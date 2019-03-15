<?php

namespace ConsultaEmpresa\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

class AboutCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('about')
            ->setDescription('Exibe informações breves sobre o Consulta Empresa.')
            ->setHelp(<<<HELP
                <info>php consulta-empresa.phar about</info>

                HELP
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write(<<<HELP
            <info>Consulta Empresa</info>
            <comment>Coleta de dados de empresas no site da ANVISA.</comment>
            Veja https://github.com/lyseontech/consutla-empresa/ para mais informações.

            HELP
        );
    }
}
