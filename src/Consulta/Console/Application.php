<?php
namespace ConsultaEmpresa\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ConsultaEmpresa\Command\AboutCommand;
use ConsultaEmpresa\Command\ConsultaCommand;

/**
 * The console application that handles the commands
 */
class Application extends BaseApplication
{
    /**
     * {@inheritDoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        if (PHP_VERSION_ID < 70302) {
            $output->writeln('<error>Suporte apenas para PHP 7.3.2 ou maior.</error>');
        }

        parent::doRun($input, $output);
    }
    
    public function getHelp()
    {
        return <<<HELP
            Consulta empresa

            Sistema para consulta de empresas no site da ANVISA.
            HELP;
    }
    
    /**
     * Initializes all commands.
     */
    protected function getDefaultCommands()
    {
        $commands = array_merge(parent::getDefaultCommands(), [
            new AboutCommand(),
            new ConsultaCommand()
        ]);

        return $commands;
    }
}
