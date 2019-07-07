# Consulta empresas na ANVISA

Consulta empresas na ANVISA utilizando a seguinte URL:

```
https://consultas.anvisa.gov.br/#/empresas/empresas/q/?cnpj=<cnpj>
```

A execução do projeto com Docker é bem simples:

```bash
git clone https://github.com/lyseontech/consulta-empresa
cd consulta-empresa
docker-compose up -d
```

## Importação via arquivo XLSX

### Coletar clientes
Para coletar clientes ou prospects, informe a planilha de entrada da seguinte
forma:

```bash
docker-compose exec php7 bin/consulta-empresa --arquivo=nomearquivo.xlsx
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
docker-compose exec php7 bin/consulta-empresa --apirequest=http://exemplo.com/api/get --apisend=http://exemplo.com/api/save
```

### apirequest

O endpoint de request de dados deverá ter estrutura que respeite o json-schema
do arquivo:

[json-schema](assets/api-get-schema.json)

### apisend

O endpoint que recebe os dados processados da API deve aceitar arquivos JSON que
respeitem o seguinte exemplo:

```json
{
  "ANVISA": [
    {
      "FIL": " ",
      "CNPJ": "49150956000169",
      "XANVMED": "01.126.2-80",
      "XDTAMED": "14/10/2019",
      "XANVSAN": "01.126.2-80",
      "XDTASAN": "14/10/2019",
      "XANVCOR": "01.126.2-80",
      "XDTACOR": "14/10/2019"
    }
  ]
}
```
