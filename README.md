Sobre o projeto
=============
Esse Web Service foi desenvolvido com o intuito de disponibilizar algumas informações do estoque de peças, em sua construção foi utilizado o PHP a partir da versão 5.6. 
Em relação ao banco de dados foi utilizado Oracle e para conexão com o banco foi utilizado o conector ADOdb.

Exemplo de utilização
=============

Como parâmetro é esperado o recebimento de um arquivo do tipo **json**, [**via método GET**](https://github.com/pandao/editor.md "Heading link"), esse parâmetro deve conter um **hash**, pois ele é associado ao **CNPJ** que está previamente cadastrado na aplicação, dentro de *config.ini*. Junto a url pode ser enviado como parâmetro o número da página e o limite de itens por página, caso esses valores não sejam preenchidos, o valor padrão é page=1 e limit=500.

### Exemplo de envio:

> URL: .../index.php?page=1&limit=500
```
{
  "hash" : "73f6637c38e73b4a5f54e3bc804ccca"
}
```

### Exemplo de retorno:

```
{
    "response": {
        "headers": {
            "records_total": "15001",
            "records_on_page": 1,
            "last_page": 15001,
            "current_page": 2
        },
        "data": [
            {
            	"codigo": "1000",
            	"margem": 5,
            	"descricao": "DESC 00100",
            	"empresa": "E00001",
            	"prateleira": "J2",
            	"valor": "100,00",
            	"linha": 22,
            	"custoMedio": 0,
            	"grupoDesc": 0,
            	"estoque": 45
            }
        ]
    }
}
```
### Observação:
Para cada **CNPJ** existe um **hash** específico e para o retorno de todos os **CNPJ's** também é necessário passar uma **hash** específico.

### Configuração:

> Arquivo: Config/config.ini

- Parâmetros para conexão com o banco de dados.
- Cadastro de **CNPJ**.
- Palavra chave para verificação da **hash**.
- Palavra chave para retorno de todos os **CNPJ's**.


