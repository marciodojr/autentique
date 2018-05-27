<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 */

namespace Mdojr\Autentique\Resource;

use Mdojr\Autentique\Tests\TestCase;
use GuzzleHttp\Psr7\Response;

class SignatureTest extends TestCase
{
    public function testCanCreateInstance()
    {
        $sign = $this->getSignature();
        $this->assertInstanceOf(Signature::class, $sign);
    }

    public function testExtendsAbstractResource()
    {
        $this->assertInstanceOf(AbstractResource::class, $this->getSignature());
    }

    private function setUpCanRetrieve()
    {
        return new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/../data/signature/retrieve-200.json'));
    }

    public function testCanRetrieve()
    {
        $signToken = $this->getSignatureToken();
        $sign = $this->getSignature($this->setUpCanRetrieve());
        $signWithData = $sign->retrieve($signToken);
        $this->assertNotEmpty($signWithData->destinatario);
    }

    private function setUpCanAccept()
    {
        return new Response(200, ['Content-Type' => 'text/html'], file_get_contents(__DIR__ . '/../data/signature/accept-200.html'));
    }

    public function testCanAccept()
    {
        $signToken = $this->getSignatureToken();
        $sign = $this->getSignature($this->setUpCanAccept());
        $result = $sign->accept($signToken);
        $this->assertSame($result, '');
    }

    private function setUpCanReject()
    {
        return new Response(200, ['Content-Type' => 'text/html'], file_get_contents(__DIR__ . '/../data/signature/reject-200.html'));
    }

    public function testCanReject()
    {
        $signToken = $this->getSignatureToken();
        $motivo = 'Não estou de acordo com as cláusulas.';
        $sign = $this->getSignature($this->setUpCanReject());
        $result = $sign->reject($signToken, $motivo);
        $this->assertSame($result, '');
    }

    private function getSignature(Response $mockedResponse = null, $useBadToken = false)
    {
        return new Signature($this->getClient($mockedResponse, $useBadToken));
    }

    public function getSignatureToken()
    {
        return 'random.signature.token';
    }

}
