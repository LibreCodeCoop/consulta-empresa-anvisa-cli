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

## Coletar clientes
Para coletar clientes, informe a planilha de clientes da seguinte forma:

```bash
docker-compose exec php7 bin/consulta-empresa -c nomearquivo.xlsx
```

### Formato do arquivo de entrada

**CNPJ**|**RAZAO SOCIAL**|**Anvisa Med**|**Anvisa San**|**Anvisa Corr**|**Validade Med**|**Validade Cor**|**Validade San**
:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:

Necessário preencher apenas o CNPJ

## Coletar prospects
Para coletar prospects, informe a planilha de prospects da seguinte forma:

```bash
docker-compose exec php7 bin/consulta-empresa -p prospects.xlsx
```

### Formato do arquivo de entrada

**CNPJ**|**RazaoSocial**|**Anvisa Med**|**Anvisa San**|**Anvisa Corr**|**Validade Med**|**Validade Cor**|**Validade San**|**Endereco**|**Bairro**|**Numero**|**Complemento**|**cep**|**Cidade**|**Estado**|**Telefone**
:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:|:-----:

Necessário preencher apenas o CNPJ
