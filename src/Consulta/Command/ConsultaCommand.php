<?php

namespace ConsultaEmpresa\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Bissolli\ValidadorCpfCnpj\Documento;
use Goutte\Client;
use function GuzzleHttp\json_encode;

class ConsultaCommand extends Command
{
    private $validList = [];
    private $invalidList = [];
    /**
     * @var Client
     */
    private $client;
    private $output = [];
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

        $this->client = new Client();
        $this->processaLista();
        $output->write(json_encode($this->output));
    }

    private function processaLista()
    {
        foreach($this->validList as $cnpj) {
            $empresa = $this->getNomeFantasia($cnpj);
            $empresa['funcionamento'] = $this->consultaEmpresa($cnpj);
            foreach($empresa['funcionamento'] as $key => $processo) {
                $response = $this->consultaFuncionamento($processo['numeroProcesso']);
                $empresa['funcionamento'][$key] = array_merge(
                    $empresa['funcionamento'][$key],
                    $response
                );
            }
            $this->output[$cnpj] = $empresa;
        }
    }

    private function consultaEmpresa($cnpj)
    {
        $funcionamento = [];
        $this->client->setHeader('Authorization', 'Guest');
        $page = 1;
        do {
            $this->client->request('GET',
                'https://consultas.anvisa.gov.br/api/empresa/funcionamento?' .
                http_build_query([
                    'count' => 100,
                    'filter[cnpj]' => $cnpj,
                    'page' => $page
                ])
            );
            $content = json_decode($this->client->getResponse()->getContent(), true);
            if(!$content){
                throw new \Exception('Invalid response in ' . __FUNCTION__);
            }
            if(isset($content['error'])) {
                throw new \Exception($content['error']);
            }
            if(!isset($content['content'])) {
                throw new \Exception('Invalid content in ' . __FUNCTION__);
            }
            $funcionamento = array_merge($funcionamento, $content['content']);
            $page++;
        } while($content['number'] < $content['totalPages'] -1);
        return $funcionamento;
    }

    public function getNomeFantasia($cnpj)
    {
        $this->client->setHeader('Authorization', 'Guest');
        $this->client->request('GET', 'https://consultas.anvisa.gov.br/api/empresa/' . $cnpj);
        $content = json_decode($this->client->getResponse()->getContent(), true);
        if(!$content){
            throw new \Exception('Invalid response in ' . __FUNCTION__);
        }
        if(isset($content['error'])) {
            throw new \Exception($content['error']);
        }
        return $content;
    }

    private function consultaFuncionamento($processo)
    {
        $this->client->setHeader('Authorization', 'Guest');
        $this->client->request('GET', 'https://consultas.anvisa.gov.br/api/empresa/funcionamento/' . $processo);
        $content = json_decode($this->client->getResponse()->getContent(), true);
        if(!$content){
            throw new \Exception('Invalid response in ' . __FUNCTION__);
        }
        if(isset($content['error'])) {
            throw new \Exception($content['error']);
        }
        return $content;
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
