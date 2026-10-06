```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$usuarios = [
    1 => [
        'id' => 1,
        'nome' => 'Eric de Oliveira Cardoso',
        'email' => 'eric@example.com',
        'login' => 'eric',
        'senha' => password_hash('123456', PASSWORD_DEFAULT)
    ],

    2 => [
        'id' => 2,
        'nome' => 'Ana Silva',
        'email' => 'ana@example.com',
        'login' => 'ana',
        'senha' => password_hash('123456', PASSWORD_DEFAULT)
    ],

    3 => [
        'id' => 3,
        'nome' => 'Bruno Santos',
        'email' => 'bruno@example.com',
        'login' => 'bruno',
        'senha' => password_hash('123456', PASSWORD_DEFAULT)
    ],

    4 => [
        'id' => 4,
        'nome' => 'Carlos Souza',
        'email' => 'carlos@example.com',
        'login' => 'carlos',
        'senha' => password_hash('123456', PASSWORD_DEFAULT)
    ],

    5 => [
        'id' => 5,
        'nome' => 'Mariana Oliveira',
        'email' => 'mariana@example.com',
        'login' => 'mariana',
        'senha' => password_hash('123456', PASSWORD_DEFAULT)
    ]
];

function respostaJson(
    Response $response,
    array $dados,
    int $status = 200
): Response {

    $response->getBody()->write(
        json_encode(
            $dados,
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        )
    );

    return $response
        ->withHeader(
            'Content-Type',
            'application/json; charset=utf-8'
        )
        ->withStatus($status);
}

function removerSenha(array $usuario): array
{
    unset($usuario['senha']);

    return $usuario;
}


/*
|--------------------------------------------------------------------------
| GET /status
|--------------------------------------------------------------------------
*/

$app->get('/status', function (
    Request $request,
    Response $response
): Response {

    return respostaJson($response, [
        'status' => 'online',
        'mensagem' => 'API de usuários funcionando corretamente'
    ]);
});


/*
|--------------------------------------------------------------------------
| GET /usuarios
|--------------------------------------------------------------------------
*/

$app->get('/usuarios', function (
    Request $request,
    Response $response
) use (&$usuarios): Response {

    $lista = [];

    foreach ($usuarios as $usuario) {
        $lista[] = removerSenha($usuario);
    }

    return respostaJson($response, [
        'usuarios' => $lista
    ]);
});


/*
|--------------------------------------------------------------------------
| GET /usuarios/{id}
|--------------------------------------------------------------------------
*/

$app->get('/usuarios/{id}', function (
    Request $request,
    Response $response,
    array $args
) use (&$usuarios): Response {

    $id = (int) $args['id'];

    if (!isset($usuarios[$id])) {

        return respostaJson(
            $response,
            [
                'erro' => 'Usuário não encontrado'
            ],
            404
        );
    }

    return respostaJson(
        $response,
        removerSenha($usuarios[$id])
    );
});


/*
|--------------------------------------------------------------------------
| POST /usuarios
|--------------------------------------------------------------------------
*/

$app->post('/usuarios', function (
    Request $request,
    Response $response
) use (&$usuarios): Response {

    $dados = $request->getParsedBody();

    if (!is_array($dados)) {

        return respostaJson(
            $response,
            [
                'erro' => 'Corpo da requisição inválido'
            ],
            400
        );
    }

    $camposObrigatorios = [
        'nome',
        'email',
        'login',
        'senha'
    ];

    foreach ($camposObrigatorios as $campo) {

        if (
            !isset($dados[$campo]) ||
            trim((string) $dados[$campo]) === ''
        ) {

            return respostaJson(
                $response,
                [
                    'erro' => "O campo '{$campo}' é obrigatório"
                ],
                400
            );
        }
    }

    foreach ($usuarios as $usuario) {

        if ($usuario['email'] === $dados['email']) {

            return respostaJson(
                $response,
                [
                    'erro' => 'E-mail já cadastrado'
                ],
                409
            );
        }

        if ($usuario['login'] === $dados['login']) {

            return respostaJson(
                $response,
                [
                    'erro' => 'Login já cadastrado'
                ],
                409
            );
        }
    }

    $id = empty($usuarios)
        ? 1
        : max(array_keys($usuarios)) + 1;

    $novoUsuario = [
        'id' => $id,
        'nome' => trim((string) $dados['nome']),
        'email' => trim((string) $dados['email']),
        'login' => trim((string) $dados['login']),
        'senha' => password_hash(
            (string) $dados['senha'],
            PASSWORD_DEFAULT
        )
    ];

    $usuarios[$id] = $novoUsuario;

    return respostaJson(
        $response,
        removerSenha($novoUsuario),
        201
    );
});


/*
|--------------------------------------------------------------------------
| PUT /usuarios/{id}
|--------------------------------------------------------------------------
*/

$app->put('/usuarios/{id}', function (
    Request $request,
    Response $response,
    array $args
) use (&$usuarios): Response {

    $id = (int) $args['id'];

    if (!isset($usuarios[$id])) {

        return respostaJson(
            $response,
            [
                'erro' => 'Usuário não encontrado'
            ],
            404
        );
    }

    $dados = $request->getParsedBody();

    if (!is_array($dados)) {

        return respostaJson(
            $response,
            [
                'erro' => 'Corpo da requisição inválido'
            ],
            400
        );
    }

    foreach (['nome', 'email', 'login'] as $campo) {

        if (
            isset($dados[$campo]) &&
            trim((string) $dados[$campo]) === ''
        ) {

            return respostaJson(
                $response,
                [
                    'erro' => "O campo '{$campo}' não pode ficar vazio"
                ],
                400
            );
        }
    }

    foreach ($usuarios as $outroId => $usuario) {

        if ($outroId === $id) {
            continue;
        }

        if (
            isset($dados['email']) &&
            $usuario['email'] === $dados['email']
        ) {

            return respostaJson(
                $response,
                [
                    'erro' => 'E-mail já cadastrado por outro usuário'
                ],
                409
            );
        }

        if (
            isset($dados['login']) &&
            $usuario['login'] === $dados['login']
        ) {

            return respostaJson(
                $response,
                [
                    'erro' => 'Login já cadastrado por outro usuário'
                ],
                409
            );
        }
    }

    if (isset($dados['nome'])) {
        $usuarios[$id]['nome'] =
            trim((string) $dados['nome']);
    }

    if (isset($dados['email'])) {
        $usuarios[$id]['email'] =
            trim((string) $dados['email']);
    }

    if (isset($dados['login'])) {
        $usuarios[$id]['login'] =
            trim((string) $dados['login']);
    }

    return respostaJson(
        $response,
        removerSenha($usuarios[$id])
    );
});


/*
|--------------------------------------------------------------------------
| DELETE /usuarios/{id}
|--------------------------------------------------------------------------
*/

$app->delete('/usuarios/{id}', function (
    Request $request,
    Response $response,
    array $args
) use (&$usuarios): Response {

    $id = (int) $args['id'];

    if (!isset($usuarios[$id])) {

        return respostaJson(
            $response,
            [
                'erro' => 'Usuário não encontrado'
            ],
            404
        );
    }

    unset($usuarios[$id]);

    return respostaJson(
        $response,
        [
            'mensagem' => 'Usuário excluído com sucesso'
        ]
    );
});


/*
|--------------------------------------------------------------------------
| POST /login
|--------------------------------------------------------------------------
*/

$app->post('/login', function (
    Request $request,
    Response $response
) use (&$usuarios): Response {

    $dados = $request->getParsedBody();

    if (
        !is_array($dados) ||
        !isset($dados['login']) ||
        !isset($dados['senha'])
    ) {

        return respostaJson(
            $response,
            [
                'erro' => 'Login e senha são obrigatórios'
            ],
            400
        );
    }

    foreach ($usuarios as $usuario) {

        if (
            $usuario['login'] === $dados['login'] &&
            password_verify(
                (string) $dados['senha'],
                $usuario['senha']
            )
        ) {

            return respostaJson(
                $response,
                [
                    'mensagem' =>
                        'Login realizado com sucesso',

                    'usuario' =>
                        removerSenha($usuario)
                ]
            );
        }
    }

    return respostaJson(
        $response,
        [
            'erro' => 'Login ou senha inválidos'
        ],
        401
    );
});


/*
|--------------------------------------------------------------------------
| PUT /usuarios/{id}/senha
|--------------------------------------------------------------------------
*/

$app->put('/usuarios/{id}/senha', function (
    Request $request,
    Response $response,
    array $args
) use (&$usuarios): Response {

    $id = (int) $args['id'];

    if (!isset($usuarios[$id])) {

        return respostaJson(
            $response,
            [
                'erro' => 'Usuário não encontrado'
            ],
            404
        );
    }

    $dados = $request->getParsedBody();

    if (
        !is_array($dados) ||
        !isset($dados['senhaAtual']) ||
        !isset($dados['novaSenha'])
    ) {

        return respostaJson(
            $response,
            [
                'erro' =>
                    'Senha atual e nova senha são obrigatórias'
            ],
            400
        );
    }

    if (
        !password_verify(
            (string) $dados['senhaAtual'],
            $usuarios[$id]['senha']
        )
    ) {

        return respostaJson(
            $response,
            [
                'erro' => 'Senha atual incorreta'
            ],
            401
        );
    }

    if (strlen((string) $dados['novaSenha']) < 6) {

        return respostaJson(
            $response,
            [
                'erro' =>
                    'A nova senha deve possuir pelo menos 6 caracteres'
            ],
            400
        );
    }

    $usuarios[$id]['senha'] =
        password_hash(
            (string) $dados['novaSenha'],
            PASSWORD_DEFAULT
        );

    return respostaJson(
        $response,
        [
            'mensagem' => 'Senha alterada com sucesso'
        ]
    );
});


$app->run();
