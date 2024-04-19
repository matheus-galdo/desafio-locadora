<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Desafio Locadora
Um projeto de uma pequena locadora de livros.

## Pré-requisitos
- PHP >= 8.x
- Composer
- Servidor web (por exemplo, Apache, Nginx) ou servidor embutido do PHP

Também é possível fazer a instalação apenas utilizando docker e docker-compose

## Instalação
Clone o repositório e navegue até o diretório do projeto::
```
git clone https://github.com/matheus-galdo/desafio-locadora
cd desafio-locadora
```

Instale as dependências usando o Composer:
```
composer install
```

Faça uma cópia do arquivo de configuração .env.example e chame-a de .env, configure as variáveis de ambiente neste arquivo, incluindo as configurações de banco de dados:
```
cp .env.example .env
```

Gere a chave de aplicativo:
```
php artisan key:generate
```

Execute as migrações do banco de dados (e, opcionalmente, os seeders):
```
php artisan migrate --seed
```

Inicie o servidor embutido do PHP (ou configure seu servidor web) e acesse o aplicativo no navegador:
```
php artisan serve
```

Inicie o worker da fila de jobs assíncronos que será responsável por executar os jobs cadastrados no banco de dados:
```
php artisan queue:work
```

### Instalação usando docker
Para instalar o projeto usando docker, siga os seguintes passos:

Clone o repositório e navegue até o diretório do projeto::
```
git clone https://github.com/matheus-galdo/desafio-locadora
cd desafio-locadora
```

Faça uma cópia do arquivo de configuração .env.example e chame-a de .env, configure as variáveis de ambiente neste arquivo, incluindo as configurações de banco de dados:
```
cp .env.example .env
```

Execute os containers do projeto e do banco de dados
```
docker-compose up
```

## API
Veja a lista com os endpoints da API e como usar cada um deles. 
Para ver mais exemplos das rotas, execute o projeto e navegue até http://localhost/docs

### Authentication
<details>
    <summary>POST /api/login</summary>
    Realiza o login de um usuário e retorna o token de autenticação JWT
</details>

<details>
    <summary>POST /api/register</summary>
    Registra um novo usuário na aplicação
</details>

<details>
    <summary>POST /api/me</summary>
    Retorna o usuário logado
</details>

### Authors
Todas as rotas de Author são autenticadas, então é necessário enviar o token de sessão no header Authorization
<details>
    <summary>GET /api/authors</summary>
    Retorna uma lista de todos os autores cadastrados
</details>

<details>
    <summary>POST /api/authors</summary>
    Cria um novo autor com os dados fornecidos
</details>

<details>
    <summary>GET /api/authors/{author}</summary>
    Exibe os detalhes de um autor específico
</details>

<details>
    <summary>POST /api/authors</summary>
    Cria um novo autor com os dados fornecidos
</details>

<details>
    <summary>PUT, PATCH /api/authors/{author}</summary>
    Atualiza os dados de um autor existente
</details>

<details>
    <summary>DELETE /api/authors/{author}</summary>
    Remove um autor do sistema
</details>

### Books
Todas as rotas de Books são autenticadas, então é necessário enviar o token de sessão no header Authorization
<details>
    <summary>GET /api/books</summary>
    Retorna uma lista de todos os livros cadastrados
</details>

<details>
    <summary>POST /api/books</summary>
    Cria um novo livro com os dados fornecidos
</details>

<details>
    <summary>GET /api/books/{book}</summary>
    Exibe os detalhes de um livro específico
</details>

<details>
    <summary>POST /api/books</summary>
    Cria um novo livro com os dados fornecidos
</details>

<details>
    <summary>PUT, PATCH /api/books/{book}</summary>
    Atualiza os dados de um livro existente
</details>

<details>
    <summary>DELETE /api/books/{book}</summary>
    Remove um livro do sistema
</details>

### Loans
Todas as rotas de Loan são autenticadas, então é necessário enviar o token de sessão no header Authorization
<details>
    <summary>GET /api/loans</summary>
    Retorna uma lista de todos os empréstimos cadastrados
</details>

<details>
    <summary>POST /api/loans</summary>
    Registra um novo empréstimo de livro para um usuário
</details>

<details>
    <summary>GET /api/loans/{loan}</summary>
    Exibe os detalhes de um empréstimo específico
</details>

<details>
    <summary>POST /api/loans/{loan}/return</summary>
    Marca um empréstimo como devolvido
</details>

## Testes
Execute os testes automatizados para garantir que o projeto esteja funcionando corretamente:
```
php artisan test
```

