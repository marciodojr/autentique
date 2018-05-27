<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 */

namespace Mdojr\Autentique\Resource;

use stdClass;

/**
 * This class allows folder manipulation.
 */
class Folder extends AbstractResource
{
    /**
     * @var string api path for folder manipulation.
     */
    const PATH = 'pastas';

    /**
     * List folders.
     * 
     * @param string $s search string.
     * 
     * @return \stdClass folder data list.
     * 
     * @see https://autentique.docs.apiary.io/#reference/0/pastas/lista-pastas.
     */
    public function list(string $s = '')
    {
        $result = $this->get(sprintf('%s.json?s=%s', self::PATH, $s));
        return $result;
    }

    /**
     * Retrieves information of folder.
     * 
     * @param string $uuid folder's $uuid.
     * 
     * @return \stdClass folder's data.
     * 
     * @see https://autentique.docs.apiary.io/#reference/0/pastas/resgata-informacoes-da-pasta
     */
    public function retrieve(string $uuid)
    {
        $result = $this->get(sprintf('%s/%s.json', self::PATH, $uuid));
        return $result;
    }

    /**
     * List documentos from a folder.
     * 
     * @param string $uuid folder $uuid.
     * @param string $s search string.
     * 
     * @return \stdClass documents of folder $uuid matched by $s.
     * 
     * @see https://autentique.docs.apiary.io/#reference/0/pastas/lista-documentos-da-pasta.
     */
    public function listDocuments(string $uuid, string $s = '')
    {
        $result = $this->get(sprintf('%s/%s/documentos.json?s=%s', self::PATH, $uuid, $s));
        return $result;
    }

    /**
     * Creates a folder.
     * 
     * @param string $nome new folder name.
     * 
     * @return \stdClass folder's data.
     * 
     * @see https://autentique.docs.apiary.io/#reference/0/assinatura/cria-pasta.
     */
    public function create(string $nome)
    {
        $result = $this->post(self::PATH . '.json', [
            'json' => [
                'nome' => $nome
            ]
        ]);
        return $result;
    }

    /**
     * Moves documents to a folder.
     * 
     * @param string $folderUuid folder $uuid.
     * @param array $docSignUuids documents uuid's array.
     * 
     * @return string empty string.
     * 
     * @see https://autentique.docs.apiary.io/#reference/0/pastas/move-documentos-para-pasta-(enviar-uuid-da-assinatura).
     */
    public function moveDocumentsTo(string $folderUuid, array $docSignUuids)
    {
        $result = $this->post(sprintf("%s/%s/mover.json", self::PATH, $folderUuid), [
            'json' => $docSignUuids
        ]);
        return $result;
    }

    /**
     * Updates a folder name.
     * 
     * @param string $uuid folder's uuid.
     * @param string $nome folder's name.
     * 
     * @return \stdClass folder data.
     * 
     * @see https://autentique.docs.apiary.io/#reference/0/pastas/atualiza-informacoes-da-pasta.
     */
    public function update(string $uuid, string $nome)
    {
        $result = $this->put(sprintf('%s/%s.json', self::PATH, $uuid), [
            'json' => [
                'nome' => $nome
            ]
        ]);
        return $result;
    }

    /**
     * Removes a folder and moves all files to "Todos".
     * 
     * @param string $uuid folder uuid.
     * 
     * @return string empty string.
     * 
     * @see https://autentique.docs.apiary.io/reference/0/pastas/remove-pasta-(os-documentos-que-estao-nela-serao-movidos-para-"todos"-mas-nao-serao-apagados)
     */
    public function deleteFolder(string $uuid)
    {
        $result = $this->delete(sprintf('%s/%s.json', self::PATH, $uuid));
        return $result;
    }
}
