<?php
// tests/ProdutosTest.php
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;
 
class ApiTest extends TestCase
{
    public function testPostCriaProduto(): void
    {
    require __DIR__ . '/../src/app.php'; // retorna $app sem chamar ->run()
        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/API')
            ->withHeader('Content-Type', 'application/json');
 
        $request->getBody()->write(json_encode(['nome' => 'Eric de Oliveira Cardoso']));
        $request->getBody()->rewind();
        $response = $app->handle($request);
 
        $this->assertEquals(201, $response->getStatusCode());
 
        $corpo = json_decode((string) $response->getBody(), true);
 
        $this->assertEquals('Eric de Oliveira Cardoso', $corpo['nome']);
 
    }
    public function testPostCriaProdutoSemNomeRetornaErro(): void
    {
        require __DIR__ . '/../src/app.php'; // retorna $app sem chamar ->run()
        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/API')
            ->withHeader('Content-Type', 'application/json');
 
        $request->getBody()->write(json_encode([]));
        $request->getBody()->rewind();
        $response = $app->handle($request);
 
        $this->assertEquals(400, $response->getStatusCode());
 
        $corpo = json_decode((string) $response->getBody(), true);
 
        $this->assertEquals('O campo nome é obrigatório', $corpo['error']);
 
    }
}