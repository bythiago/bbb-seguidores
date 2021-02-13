# bbb-seguidores
> Instruções para instalação do projeto

1) Clonando o projeto

``` bash
git clone https://github.com/bythiago/bbb-seguidores.git && cd bbb-seguidores
```

2) O Laravel requer permissão de leitura e escrita nos diretórios "storage" e "bootstrap/cache', portanto, altere as permissões desses diretórios executando os comandos abaixo.

``` bash
sudo chmod -R 777 storage
sudo chmod -R 777 bootstrap/cache
```

3) Execute o comando para instalar todos pacotes PHP necessários.

``` bash
composer install --no-dev --optimize-autoloader
```

4) Edite o arquivo *.env* para adicionar as credenciais do instagram

``` text
INSTAGRAM_USERNAME=seu-usuario-do-instagram
INSTAGRAM_PASSWORD=sua-senha-do-instagram
```
