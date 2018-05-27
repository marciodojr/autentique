<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 * @license https://github.com/marciodojr/autentique/blob/master/LICENSE (MIT License)
 */

namespace Mdojr\Autentique\Resource;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use Mdojr\Autentique\Tests\TestCase;

class AccountTest extends TestCase
{
    public function testThrowInvalidEndpoint()
    {
        $this->expectException(InvalidArgumentException::class);
        $account = $this->getWrongAccount();
    }

    public function testCanCreateInstance()
    {
        $account = $this->getAccount();
        $this->assertInstanceOf(Account::class, $account);
    }

    public function testExtendsAbstractResource()
    {
        $this->assertInstanceOf(AbstractResource::class, $this->getAccount());
    }

    private function setUpCanGetInfo()
    {
        return new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__.'/../data/account/info-200.json'));
    }

    public function testCanGetInfo()
    {
        $account = $this->getAccount($this->setUpCanGetInfo());

        $data = $account->info();

        $this->assertNotSame($data->nome, null);
        $this->assertNotSame($data->empresa, null);
        $this->assertNotSame($data->email, null);
        $this->assertNotSame($data->cpf, null);
        $this->assertNotSame($data->nascimento, null);
        $this->assertNotSame($data->created, null);
        $this->assertNotSame($data->confirmed, null);
        $this->assertSame($data->creditos->uuid, null);
        $this->assertNotSame($data->creditos->plano, null);
        $this->assertNotSame($data->creditos->gasto, null);
        $this->assertNotSame($data->creditos->total, null);
        $this->assertNotSame($data->creditos->bonus, null);
        $this->assertNotSame($data->creditos->validade, null);
    }

    private function setUpGetInfoThrowsError401()
    {
        return new Response(401, ['Content-Type' => 'application/json'], file_get_contents(__DIR__.'/../data/account/info-401.json'));
    }

    public function testGetInfoThrowsError401()
    {
        $account = $this->getAccount($this->setUpGetInfoThrowsError401(), true);
        $this->expectInvalidTokenException();
        $data = $account->info();
    }

    private function getAccount(Response $mockedResponse = null, $useBadToken = false)
    {
        return new Account($this->getClient($mockedResponse, $useBadToken));
    }

    private function getWrongAccount()
    {
        return new Account(new Client([
            'base_uri' => 'invalid.endpoint.url',
        ]));
    }
}
