<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 * @license https://github.com/marciodojr/autentique/blob/master/LICENSE (MIT License)
 */

namespace Mdojr\Autentique\Tests;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Mdojr\Autentique\Endpoint;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    const MOCK_TOKEN = 'good.mock.token.1234567890';
    const BAD_TOKEN = 'bad.mock.token.0987654321';

    protected function getClient(Response $mockedResponse = null, $useBadToken = false)
    {
        $sandboxToken = getenv('AUTENTIQUE_TOKEN');
        $endpoint = getenv('AUTENTIQUE_ENDPOINT');
        $token = self::MOCK_TOKEN;

        if ($useBadToken) {
            $token = $useBadToken;
        } elseif ($sandboxToken) {
            $token = $sandboxToken;
        }

        $config = [
            'base_uri' => $endpoint ? $endpoint : Endpoint::SANDBOX,
            'headers' => [
                'X-Autntiq-Api' => $token,
            ],
        ];

        if (!$sandboxToken && $mockedResponse) {
            $config['handler'] = MockHandler::createWithMiddleware([$mockedResponse]);
        }

        return new Client($config);
    }

    protected function expectInvalidTokenException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(401);
    }
}
