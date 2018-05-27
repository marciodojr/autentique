<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 */

namespace Mdojr\Autentique\Resource;

use Mdojr\Autentique\Tests\TestCase;
use GuzzleHttp\Psr7\Response;

class FolderTest extends TestCase
{
    public function testCanCreateInstance()
    {
        $folder = $this->getFolder();
        $this->assertInstanceOf(Folder::class, $folder);
    }

    public function testExtendsAbstractResource()
    {
        $this->assertInstanceOf(AbstractResource::class, $this->getFolder());
    }

    public function setUpCanListFolders()
    {
        return new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/../data/folder/list-200.json'));
    }

    public function testCanListFolders()
    {
        $folder = $this->getFolder($this->setUpCanListFolders());
        $s = "fol";
        $result = $folder->list($s);
        $this->assertTrue(is_array($result->data));
    }

    public function setUpCanRetrieveFolder()
    {
        return new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/../data/folder/retrieve-200.json'));
    }

    public function testCanRetrieveFolder()
    {
        $uuid = $this->getFolderUuid();
        $folder = $this->getFolder($this->setUpCanRetrieveFolder());
        $data = $folder->retrieve($uuid);

        $this->assertEquals($data->uuid, $uuid);
        $this->assertNotSame($data->nome, null);
        $this->assertNotSame($data->slug, null);
        $this->assertNotSame($data->created, null);
    }

    public function setUpCanListDocuments()
    {
        return new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/../data/folder/list-doc-200.json'));
    }

    public function testCanListDocuments()
    {
        $uuid = $this->getFolderUuid();
        $folder = $this->getFolder($this->setUpCanListDocuments());
        $s = 'test';
        $result = $folder->listDocuments($uuid, $s);
        $this->assertTrue(is_array($result->data));
    }

    public function setUpCanCreateFolder()
    {
        return new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/../data/folder/create-200.json'));
    }

    public function testCanCreateFolder()
    {
        $folderName = "Empresa XYZ";
        $folder = $this->getFolder($this->setUpCanCreateFolder());
        $data = $folder->create($folderName);

        $this->assertNotSame($data->uuid, null);
        $this->assertSame($data->nome, $folderName);
        $this->assertNotSame($data->slug, null);
        $this->assertNotSame($data->created, null);
    }

    private function setUpCanMoveDocumentsToFolder()
    {
        return new Response(200, ['Content-Type' => 'text/html'], file_get_contents(__DIR__ . '/../data/folder/move-200.html'));
    }

    public function testCanMoveDocumentsToFolder()
    {
        $uuid = $this->getFolderUuid();
        $folder = $this->getFolder($this->setUpCanMoveDocumentsToFolder());
        $docSignUuids = [
            '963f27ea66ae849f8536b08e5c80ab56da4a2a3c'
        ];
        $result = $folder->moveDocumentsTo($uuid, $docSignUuids);
        $this->assertSame($result, '');
    }

    private function setUpCanUpdateFolderName()
    {
        return new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/../data/folder/update-200.json'));
    }

    public function testCanUpdateFolderName()
    {
        $folderName = 'Empresa XY';
        $uuid = $this->getFolderUuid();
        $folder = $this->getFolder($this->setUpCanUpdateFolderName());
        $data = $folder->update($uuid, $folderName);
        $this->assertSame($data->nome, $folderName);
    }

    private function setUpCanDeleteFolder()
    {
        return new Response(200, ['Content-Type' => 'text/html'], file_get_contents(__DIR__ . '/../data/folder/delete-200.html'));
    }

    public function testCanDeleteFolder()
    {
        $uuid = $this->getFolderUuid();
        $folder = $this->getFolder($this->setUpCanDeleteFolder());
        $result = $folder->deleteFolder($uuid);
        $this->assertSame($result, '');
    }

    private function getFolder(Response $mockedResponse = null, $useBadToken = false)
    {
        return new Folder($this->getClient($mockedResponse, $useBadToken));
    }

    private function getFolderUuid()
    {
        return $this->getFolder($this->setUpCanCreateFolder())->create('testing...')->uuid;
    }
}
