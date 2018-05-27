<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 * @license https://github.com/marciodojr/autentique/blob/master/LICENSE (MIT License)
 */

namespace Mdojr\Autentique\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use Exception;
use Mdojr\Autentique\Endpoint;

abstract class TestCase extends BaseTestCase
{
    const MOCK_TOKEN = 'good.mock.token.1234567890';
    const BAD_TOKEN = 'bad.mock.token.0987654321';

    protected function getClient(Response $mockedResponse = null, $useBadToken = false)
    {
        $sandboxToken = getenv('AUTENTIQUE_TOKEN');
        $token = self::MOCK_TOKEN;

        if($useBadToken) {
            $token = $useBadToken;
        } else if($sandboxToken) {
            $token = $sandboxToken;
        }

        $config = [
            'base_uri' => Endpoint::SANDBOX,
            'headers' => [
                'X-Autntiq-Api' => $token,
            ]
        ];

        if(!$sandboxToken && $mockedResponse) {
            $config['handler'] = MockHandler::createWithMiddleware([$mockedResponse]);
        }

        return new Client($config);
    }

    protected function expectInvalidTokenException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(401);
        $this->expectExceptionMessage('Você deve estar logado para acessar esta página.');
    }
}