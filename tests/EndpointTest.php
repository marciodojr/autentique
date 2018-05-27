<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 */

namespace Mdojr\Autentique;

use Mdojr\Autentique\Tests\TestCase;

class EndpointTest extends TestCase
{

    public function testEndpoints()
    {
        $this->assertSame('https://sandbox.autentique.com.br', Endpoint::SANDBOX);
        $this->assertSame('https://api.autentique.com.br', Endpoint::PRODUCTION);
    }

}