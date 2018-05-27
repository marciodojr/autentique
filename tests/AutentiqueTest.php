<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 * @license https://github.com/marciodojr/autentique/blob/master/LICENSE (MIT License)
 */

namespace Mdojr\Autentique;

use Mdojr\Autentique\Tests\TestCase;

class AutentiqueTest extends TestCase
{
    public function testCanCreateInstance()
    {
        $this->assertInstanceOf(Autentique::class, $this->getAutentiqueInstance());
    }

    public function testCanCreateAccount()
    {
        $autentique = $this->getAutentiqueInstance();
        $this->assertInstanceOf(Resource\Account::class, $autentique->account());
    }

    public function testCanCreateDocument()
    {
        $autentique = $this->getAutentiqueInstance();
        $this->assertInstanceOf(Resource\Document::class, $autentique->document());
    }

    public function testCanCreateFolder()
    {
        $autentique = $this->getAutentiqueInstance();
        $this->assertInstanceOf(Resource\Folder::class, $autentique->folder());
    }

    public function testCanCreateSignature()
    {
        $autentique = $this->getAutentiqueInstance();
        $this->assertInstanceOf(Resource\Signature::class, $autentique->signature());
    }

    private function getAutentiqueInstance()
    {
        return new Autentique($this->getClient());
    }
}
