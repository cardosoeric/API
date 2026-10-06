# API REST de Usuários e Autenticação

Projeto desenvolvido para a Unidade Curricular **Desenvolver Serviços Web**.

## 1. Identificação

* **Nome completo:** Eric de Oliveira Cardoso
* **Curso:** Desenvolvimento de Sistemas
* **Unidade Curricular:** Desenvolver Serviços Web
* **Modalidade:** Individual

## 2. Objetivo

Desenvolver uma API REST utilizando PHP e Slim Framework para gerenciamento de usuários e autenticação.

Cada usuário possui:

* ID
* Nome
* E-mail
* Login
* Senha

A aplicação inicia com 5 usuários cadastrados.

## 3. Tecnologias utilizadas

* PHP
* Slim Framework
* Slim PSR-7
* Composer
* JSON
* API REST

## 4. Como instalar as dependências

É necessário ter o PHP e o Composer instalados.

Abra o terminal na pasta do projeto e execute:

```bash
composer install
```

Esse comando instala as dependências do Slim Framework.

## 5. Como executar a API

Depois de instalar as dependências, execute:

```bash
php -S localhost:8080 -t public
```

A API ficará disponível em:

```text
http://localhost:8080
```

Os testes podem ser realizados utilizando o Postman ou Insomnia.

## 6. Usuários iniciais

A aplicação inicia com 5 usuários cadastrados.

A senha inicial de todos os usuários é:

```text
123456
```

| ID | Nome                     | E-mail                                            | Login   |
| -: | ------------------------ | ------------------------------------------------- | ------- |
|  1 | Eric de Oliveira Cardoso | [eric@example.com](mailto:eric@example.com)       | eric    |
|  2 | Ana Silva                | [ana@example.com](mailto:ana@example.com)         | ana     |
|  3 | Bruno Santos             | [bruno@example.com](mailto:bruno@example.com)     | bruno   |
|  4 | Carlos Souza             | [carlos@example.com](mailto:carlos@example.com)   | carlos  |
|  5 | Mariana Oliveira         | [mariana@example.com](mailto:mariana@example.com) | mariana |

## 7. Endpoints

### GET /status

Verifica se a API está funcionando.

```text
GET http://localhost:8080/status
```

Resposta:

```json
{
    "status": "online",
    "mensagem": "API de usuários funcionando corretamente"
}
```

Status HTTP:

```text
200 OK
```

---

### GET /usuarios

Lista todos os usuários cadastrados.

```text
GET http://localhost:8080/usuarios
```

As senhas não são exibidas.

---

### GET /usuarios/{id}

Busca um usuário pelo ID.

Exemplo:

```text
GET http://localhost:8080/usuarios/1
```

Resposta:

```json
{
    "id": 1,
    "nome": "Eric de Oliveira Cardoso",
    "email": "eric@example.com",
    "login": "eric"
}
```

Se o usuário não existir:

```json
{
    "erro": "Usuário não encontrado"
}
```

Status HTTP:

```text
404 Not Found
```

---

### POST /usuarios

Cadastra um novo usuário.

```text
POST http://localhost:8080/usuarios
```

Body:

```json
{
    "nome": "João da Silva",
    "email": "joao@example.com",
    "login": "joao",
    "senha": "123456"
}
```

Resposta:

```json
{
    "id": 6,
    "nome": "João da Silva",
    "email": "joao@example.com",
    "login": "joao"
}
```

Status HTTP:

```text
201 Created
```

---

### PUT /usuarios/{id}

Atualiza os dados de um usuário.

Exemplo:

```text
PUT http://localhost:8080/usuarios/1
```

Body:

```json
{
    "nome": "Eric Oliveira Atualizado",
    "email": "eric.novo@example.com",
    "login": "ericnovo"
}
```

Resposta:

```json
{
    "id": 1,
    "nome": "Eric Oliveira Atualizado",
    "email": "eric.novo@example.com",
    "login": "ericnovo"
}
```

Status HTTP:

```text
200 OK
```

---

### DELETE /usuarios/{id}

Exclui um usuário.

Exemplo:

```text
DELETE http://localhost:8080/usuarios/5
```

Resposta:

```json
{
    "mensagem": "Usuário excluído com sucesso"
}
```

Status HTTP:

```text
200 OK
```

---

### POST /login

Realiza a autenticação do usuário.

```text
POST http://localhost:8080/login
```

Body:

```json
{
    "login": "eric",
    "senha": "123456"
}
```

Resposta:

```json
{
    "mensagem": "Login realizado com sucesso",
    "usuario": {
        "id": 1,
        "nome": "Eric de Oliveira Cardoso",
        "email": "eric@example.com",
        "login": "eric"
    }
}
```

Status HTTP:

```text
200 OK
```

Caso as credenciais estejam incorretas:

```json
{
    "erro": "Login ou senha inválidos"
}
```

Status HTTP:

```text
401 Unauthorized
```

---

### PUT /usuarios/{id}/senha

Altera a senha de um usuário.

Exemplo:

```text
PUT http://localhost:8080/usuarios/1/senha
```

Body:

```json
{
    "senhaAtual": "123456",
    "novaSenha": "654321"
}
```

Resposta:

```json
{
    "mensagem": "Senha alterada com sucesso"
}
```

Status HTTP:

```text
200 OK
```

## 8. Exemplos de testes dos endpoints

Os testes podem ser realizados no Postman ou Insomnia.

### Teste 1 — Verificar API

```text
GET http://localhost:8080/status
```

Resultado esperado:

```text
200 OK
```

---

### Teste 2 — Listar usuários

```text
GET http://localhost:8080/usuarios
```

Resultado esperado:

```text
200 OK
```

A resposta deve apresentar os 5 usuários iniciais.

---

### Teste 3 — Buscar usuário

```text
GET http://localhost:8080/usuarios/1
```

Resultado esperado:

```text
200 OK
```

---

### Teste 4 — Criar usuário

```text
POST http://localhost:8080/usuarios
```

Body:

```json
{
    "nome": "João da Silva",
    "email": "joao@example.com",
    "login": "joao",
    "senha": "123456"
}
```

Resultado esperado:

```text
201 Created
```

---

### Teste 5 — Atualizar usuário

```text
PUT http://localhost:8080/usuarios/1
```

Body:

```json
{
    "nome": "Eric Atualizado",
    "email": "eric.atualizado@example.com",
    "login": "ericatualizado"
}
```

Resultado esperado:

```text
200 OK
```

---

### Teste 6 — Fazer login

```text
POST http://localhost:8080/login
```

Body:

```json
{
    "login": "eric",
    "senha": "123456"
}
```

Resultado esperado:

```text
200 OK
```

---

### Teste 7 — Alterar senha

```text
PUT http://localhost:8080/usuarios/1/senha
```

Body:

```json
{
    "senhaAtual": "123456",
    "novaSenha": "654321"
}
```

Resultado esperado:

```text
200 OK
```

---

### Teste 8 — Excluir usuário

```text
DELETE http://localhost:8080/usuarios/5
```

Resultado esperado:

```text
200 OK
```

## 9. Decisões técnicas

Os usuários são armazenados em uma estrutura de dados em memória para manter o projeto simples e focado nos requisitos da atividade.

O Slim Framework é utilizado para criação das rotas da API REST.

O método `getParsedBody()` é utilizado para receber os dados enviados nas requisições.

As senhas são protegidas utilizando `password_hash()` e `password_verify()`. Dessa forma, as senhas não são armazenadas diretamente em texto.

As respostas da API são enviadas em formato JSON.

Foram utilizados códigos HTTP adequados para cada situação:

* `200 OK` — operação realizada com sucesso.
* `201 Created` — usuário criado com sucesso.
* `400 Bad Request` — dados da requisição inválidos.
* `401 Unauthorized` — credenciais inválidas.
* `404 Not Found` — usuário não encontrado.
* `409 Conflict` — conflito com dados já cadastrados.

## 10. Estrutura do projeto

```text
api-usuarios-slim/
│
├── public/
│   └── index.php
│
├── vendor/
│
├── composer.json
├── composer.lock
├── README.md
└── .gitignore
```

## 11. Prints

Adicionar nesta seção os prints dos testes realizados no Postman ou Insomnia.

Recomenda-se adicionar prints de:

1. `GET /status`
2. `GET /usuarios`
3. `GET /usuarios/{id}`
4. `POST /usuarios`
5. `PUT /usuarios/{id}`
6. `DELETE /usuarios/{id}`
7. `POST /login`
8. `PUT /usuarios/{id}/senha`

Os prints devem mostrar a requisição realizada e a resposta retornada pela API.

## 12. Status da atividade

API REST de usuários e autenticação desenvolvida utilizando PHP e Slim Framework, com os endpoints obrigatórios implementados e preparados para testes.
