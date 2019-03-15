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
docker-compose exec php7 bin/consulta-empresa <lista-de-cnpj>
```

Onde:

| Campo             |  Descrição                         |
|-------------------|------------------------------------|
| `<lista-de-cnpj>` | Lista de CNPJ separada por vírgula |
