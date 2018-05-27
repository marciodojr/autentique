<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 * @license https://github.com/marciodojr/autentique/blob/master/LICENSE (MIT License)
 */

namespace Mdojr\Autentique\Resource\Constants;

use Mdojr\Autentique\Tests\TestCase;

class DocumentTest extends TestCase
{
    public function testFuncaoConstants()
    {
        $this->assertSame(Document::FUNCAO_ASSINAR, 'assinar');
        $this->assertSame(Document::FUNCAO_ASSINAR_TESTEMUNHA, 'assinar_testemunha');
        $this->assertSame(Document::FUNCAO_ACUSAR_RECEBIMENTO, 'acusar_recebimento');
        $this->assertSame(Document::FUNCAO_RECONHECER, 'reconhecer');
        $this->assertSame(Document::FUNCAO_APROVAR, 'aprovar');
    }
}
