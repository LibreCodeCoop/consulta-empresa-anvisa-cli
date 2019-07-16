# Consulta empresas na ANVISA

Consulta empresas na ANVISA utilizando a seguinte URL:

```
https://consultas.anvisa.gov.br/#/empresas/empresas/q/?cnpj=<cnpj>
```

## Docker

Executar a aplicação via Docker é recomendável para quem queira ter um ambiente isolado e controlado sem ter necessidade de realizar alterações no sistema operacional. Esta é a solução mais recomendável para desenvolvedores do projeto.

A execução do projeto com Docker é bem simples:

```bash
git clone https://github.com/lyseontech/consulta-empresa
cd consulta-empresa
cp .env.develop .env
docker-compose up -d
```
Nos comandos abaixo, onde você lê `consulta-empresa.phar` coloque o seguinte
comando:

```bash
docker-compose exec php7 bin/consulta-empresa.php
```

exemplo:

```bash
docker-compose exec php7 bin/consulta-empresa.php consulta --help
```

## PHAR

Executar a aplicação via `phar` é para usuários finais.

Baixe a versão mais recente do projeto em [releases](https://github.com/LyseonTech/consulta-empresa-anvisa-cli/releases/latest/download/consulta-empresa.phar)

## Importação via arquivo XLSX

### Coletar clientes
Para coletar clientes ou prospects, informe a planilha de entrada da seguinte
forma:

```bash
php consulta-empresa.phar --arquivo=nomearquivo.xlsx
```

### Formato do arquivo de entrada

8 colunas:

**CNPJ**|**RAZAO SOCIAL**|**Anvisa Med**|**Anvisa San**|**Anvisa Corr**|**Validade Med**|**Validade Cor**|**Validade San**
:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:

Necessário preencher apenas o CNPJ

### Formato do arquivo de entrada

16 colunas:

**CNPJ**|**RazaoSocial**|**Anvisa Med**|**Anvisa San**|**Anvisa Corr**|**Validade Med**|**Validade Cor**|**Validade San**|**Endereco**|**Bairro**|**Numero**|**Complemento**|**cep**|**Cidade**|**Estado**|**Telefone**
:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:

Necessário preencher apenas o CNPJ

### Saída

Será criado um arquivo com o mesmo nome do arquivo de entrada porém com o nome 
prefixado com `output-`

## Importação via API

Para importar via API será necessário informar a URL que a aplicação irá fazer a
requisição e a URL onde a aplicação deverá devolver os dados processados.

```bash
php dconsulta-empresa.phar --apirequest=http://exemplo.com/api/get --apisend=http://exemplo.com/api/save
```

### apirequest

O endpoint de request de dados deverá ter estrutura que respeite o json-schema
do arquivo:

[json-schema](assets/api-get-schema.json)

### apisend

O endpoint que recebe os dados processados da API deve aceitar requisições POST com JSON no body que
respeitem o seguinte exemplo:

```json
{
  "ANVISA": [
    {
      "FIL": " ",
      "CNPJ": "49543956000169",
      "XANVMED": "01.876.2-80",
      "XDTAMED": "14/10/2019",
      "XANVSAN": "01.876.2-80",
      "XDTASAN": "14/10/2019",
      "XANVCOR": "01.876.2-80",
      "XDTACOR": "14/10/2019"
    }
  ]
}
```
### CSV de log

Apenas no modo API há um argumento a mais na aplicação para gerar um CSV de log.
Este CSV será gerado com a seguinte estrutura:

**cnpj**|**correlatos-autorizacao**|**correlatos-validade**|**medicamentos-autorizacao**|**medicamentos-validade**|**saneantes-autorizacao**|**saneantes-validade**|**status**|**data-consulta**
:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:

### Limite de dados processados

No modo api há o argumento `-l` para informar um limite de registros a serem
processados.
Exemplo:

```bash
php dconsulta-empresa.phar --apirequest=http://exemplo.com/api/get --apisend=http://exemplo.com/api/save -l 10
```

Desta forma mesmo que a API retorne 1000 registros, apenas irá processar 10.

### Modo verboso

Importação via API possui modo verboso para avaliar possíveis problemas durante
o acesso a API. Para utilizar o modo verboso, basta passar um `-v`

## Notas para desenvolvedores

Para gerar o arquivo `phar` do projeto execute o script `bin/compile`
