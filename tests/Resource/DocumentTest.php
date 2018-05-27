<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 * @license https://github.com/marciodojr/autentique/blob/master/LICENSE (MIT License)
 */

namespace Mdojr\Autentique\Resource;

use Exception;
use GuzzleHttp\Psr7\Response;
use Mdojr\Autentique\Tests\TestCase;

class DocumentTest extends TestCase
{
    public function testCanCreateInstance()
    {
        $document = $this->getDocument();
        $this->assertInstanceOf(Document::class, $document);
    }

    public function testExtendsAbstractResource()
    {
        $this->assertInstanceOf(AbstractResource::class, $this->getDocument());
    }

    private function setUpCanListNotInFolder()
    {
        return new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__.'/../data/document/list-not-in-folder-200.json'));
    }

    public function testCanListNotInFolder()
    {
        $document = $this->getDocument($this->setUpCanListNotInFolder());
        $page = 1;
        $count = 20;
        $result = $document->listNotInFolder($page, $count);
        $this->assertTrue(is_array($result->data));
    }

    private function setUpListNotInFolderThrowsError401()
    {
        return new Response(401, ['Content-Type' => 'application/json'], file_get_contents(__DIR__.'/../data/document/list-not-in-folder-401.json'));
    }

    public function testListNotInFolderThrowsError401()
    {
        $document = $this->getDocument($this->setUpListNotInFolderThrowsError401(), true);
        $page = 1;
        $count = 20;
        $this->expectInvalidTokenException();
        $document->listNotInFolder($page, $count);
    }

    private function setUpCanRetrieveDocument()
    {
        return new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__.'/../data/document/retrieve-200.json'));
    }

    public function testCanRetrieveDocument()
    {
        $docUuid = $this->getDocumentUuid();
        $document = $this->getDocument($this->setUpCanRetrieveDocument());
        $result = $document->retrieve($docUuid);
        $this->assertSame($result->documento->uuid, $docUuid);
    }

    private function setUpRetrieveDocumentThrowsError401()
    {
        return new Response(401, ['Content-Type' => 'application/json'], file_get_contents(__DIR__.'/../data/document/retrieve-401.json'));
    }

    public function testRetrieveDocumentThrowsError401()
    {
        $docUuid = $this->getDocumentUuid();
        $document = $this->getDocument($this->setUpRetrieveDocumentThrowsError401(), true);
        $this->expectInvalidTokenException();
        $document->retrieve($docUuid);
    }

    private function setUpRetrieveDocumentThrowsError404()
    {
        return new Response(404, ['Content-Type' => 'text/html'], file_get_contents(__DIR__.'/../data/document/retrieve-404.html'));
    }

    public function testRetrieveDocumentThrowsError404()
    {
        $docUuid = 'invalid.uuid';
        $document = $this->getDocument($this->setUpRetrieveDocumentThrowsError404());
        $this->expectException(Exception::class);
        $this->expectExceptionCode(404);
        $document->retrieve($docUuid);
    }

    private function setUpCanListReceivedDocsNotInFolder()
    {
        return new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__.'/../data/document/can-list-received-not-in-folder-200.json'));
    }

    public function testCanListReceivedDocsNotInFolder()
    {
        $document = $this->getDocument($this->setUpCanListReceivedDocsNotInFolder());
        $page = 1;
        $count = 20;
        $result = $document->listReceivedNotInFolder($page, $count);
        $this->assertTrue(is_array($result->data));
    }

    private function setUpListReceivedDocsNotInFolderThrowsError401()
    {
        return new Response(401, ['Content-Type' => 'application/json'], file_get_contents(__DIR__.'/../data/document/can-list-received-not-in-folder-401.json'));
    }

    public function testListReceivedDocsNotInFolderThrowsError401()
    {
        $document = $this->getDocument($this->setUpListReceivedDocsNotInFolderThrowsError401(), true);
        $page = 1;
        $count = 20;
        $this->expectInvalidTokenException();
        $document->listReceivedNotInFolder($page, $count);
    }

    private function setUpCanListOnlySent()
    {
        return new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__.'/../data/document/can-list-only-sent-200.json'));
    }

    public function testCanListOnlySent()
    {
        $document = $this->getDocument($this->setUpCanListOnlySent());
        $page = 1;
        $count = 20;
        $result = $document->listOnlySent($page, $count);
        $this->assertTrue(is_array($result->data));
    }

    private function setUpListOnlySentThrowsError401()
    {
        return new Response(401, ['Content-Type' => 'application/json'], file_get_contents(__DIR__.'/../data/document/can-list-only-sent-401.json'));
    }

    public function testListOnlySentThrowsError401()
    {
        $document = $this->getDocument($this->setUpListOnlySentThrowsError401(), true);
        $page = 1;
        $count = 20;
        $this->expectInvalidTokenException();
        $document->listOnlySent($page, $count);
    }

    private function setUpCanCreateDocument()
    {
        return new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__.'/../data/document/create-200.json'));
    }

    public function testCanCreateDocument()
    {
        $document = $this->getDocument($this->setUpCanCreateDocument());

        $docData = $this->getDocData();

        $data = $document->create(
            $docData[0], // nome
            $docData[1], // partes
            $docData[2], // arquivo
            $docData[3]  // rejeitavel
        );

        $this->assertNotEmpty($data->uuid);
        $this->assertNotEmpty($data->nome);
    }

    public function setUpCreateDocumentThrowsError401()
    {
        return new Response(401, ['Content-Type' => 'application/json'], file_get_contents(__DIR__.'/../data/document/create-401.json'));
    }

    public function testCreateDocumentThrowsError401()
    {
        $document = $this->getDocument($this->setUpCreateDocumentThrowsError401(), true);
        $docData = $this->getDocData();
        $this->expectInvalidTokenException();
        $document->create(
            $docData[0], // nome
            $docData[1], // partes
            $docData[2], // arquivo
            $docData[3]  // rejeitavel
        );
    }

    private function setUpCanResendNotSignedOrRejected()
    {
        return new Response(200, ['Content-Type' => 'text/html'], file_get_contents(__DIR__.'/../data/document/can-resend-not-signed-or-rejected-200.html'));
    }

    public function testCanResendNotSignedOrRejected()
    {
        $uuid = $this->getDocumentUuid();
        $document = $this->getDocument($this->setUpCanResendNotSignedOrRejected());
        $result = $document->resendNotSignedOrRejected($uuid);
        $this->assertSame($result, '');
    }

    private function setUpResendNotSignedOrRejectedThrowsError401()
    {
        return new Response(401, ['Content-Type' => 'application/json'], file_get_contents(__DIR__.'/../data/document/can-resend-not-signed-or-rejected-401.json'));
    }

    public function testResendNotSignedOrRejectedThrowsError401()
    {
        $uuid = $this->getDocumentUuid();
        $document = $this->getDocument($this->setUpResendNotSignedOrRejectedThrowsError401(), true);
        $this->expectInvalidTokenException();
        $document->resendNotSignedOrRejected($uuid);
    }

    private function setUpResendNotSignedOrRejectedThrowsError404()
    {
        return new Response(404, ['Content-Type' => 'text/html'], file_get_contents(__DIR__.'/../data/document/can-resend-not-signed-or-rejected-404.html'));
    }

    public function testResendNotSignedOrRejectedThrowsError404()
    {
        $uuid = 'invalid.uuid';
        $document = $this->getDocument($this->setUpResendNotSignedOrRejectedThrowsError404());
        $this->expectException(Exception::class);
        $this->expectExceptionCode(404);
        $document->resendNotSignedOrRejected($uuid);
    }

    private function setUpCanDeleteDocument()
    {
        return new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__.'/../data/document/delete-200.json'));
    }

    public function testCanDeleteDocument()
    {
        $uuid = $this->getDocumentUuid();
        $document = $this->getDocument($this->setUpCanDeleteDocument());
        $result = $document->deleteDocument($uuid);
        $this->assertSame($result->message, 'Documento removido com sucesso.');
    }

    private function setUpDeleteDocumentThrowsError401()
    {
        return new Response(401, ['Content-Type' => 'application/json'], file_get_contents(__DIR__.'/../data/document/delete-401.json'));
    }

    public function testCanDeleteDocumentThrowsError401()
    {
        $uuid = $this->getDocumentUuid();
        $document = $this->getDocument($this->setUpDeleteDocumentThrowsError401(), true);
        $this->expectInvalidTokenException();
        $document->deleteDocument($uuid);
    }

    private function setUpDeleteDocumentThrowsError404()
    {
        return new Response(404, ['Content-Type' => 'text/html'], file_get_contents(__DIR__.'/../data/document/delete-404.html'));
    }

    public function testCanDeleteDocumentThrowsError404()
    {
        $uuid = 'invalid.uuid';
        $document = $this->getDocument($this->setUpDeleteDocumentThrowsError404());
        $this->expectException(Exception::class);
        $this->expectExceptionCode(404);
        $document->deleteDocument($uuid);
    }

    private function getDocument(Response $mockedResponse = null, $useBadToken = false)
    {
        return new Document($this->getClient($mockedResponse, $useBadToken));
    }

    private function getDocumentUuid()
    {
        $data = $this->getDocData();

        return $this->getDocument($this->setUpCanCreateDocument())->create(
            $data[0], // nome
            $data[1], // partes
            $data[2], // arquivo
            $data[3]  // rejeitavel
        )->uuid;
    }

    public function getDocData()
    {
        $nome = 'Contrato de Emprestimo P2P';
        $partes = [
            [
                'email'  => 'testando@fakeemail.com',
                'funcao' => Constants\Document::FUNCAO_ASSINAR,
            ],
        ];
        $arquivo = fopen(__DIR__.'/../data/test-document.txt', 'r');
        $rejeitavel = false;

        return [$nome, $partes, $arquivo, $rejeitavel];
    }
}
