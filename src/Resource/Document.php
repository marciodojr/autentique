<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 * @license https://github.com/marciodojr/autentique/blob/master/LICENSE (MIT License)
 */

namespace Mdojr\Autentique\Resource;

/**
 * This class allows document manipulation.
 */
class Document extends AbstractResource
{
    /**
     * @var string api path for document manipulation
     */
    const PATH = 'documentos';

    /**
     * List documents that are not in a folder.
     *
     * @param int    $page    page counter
     * @param int    $counter number of items in the page
     * @param string $s       search string
     *
     * @return \stdClass document list
     *
     * @see https://autentique.docs.apiary.io/#reference/0/documentos/lista-todos-documentos-que-nao-estao-numa-pasta.
     */
    public function listNotInFolder(int $page, int $count, string $s = '')
    {
        $result = $this->get(sprintf('%s.json?page=%s&count=%s&s=%s', self::PATH, $page, $count, $s));

        return $result;
    }

    /**
     * Retrieves a document with uuid $uuid.
     *
     * @param string $uuid document uuid
     *
     * @return \stdClass document data
     *
     * @see https://autentique.docs.apiary.io/#reference/0/documentos/resgata-informacoes-sobre-documento-especifico.
     */
    public function retrieve(string $uuid)
    {
        $result = $this->get(sprintf('%s/%s.json', self::PATH, $uuid));

        return $result;
    }

    /**
     * List received documents that are not in a folder.
     *
     * @param int    $page    page counter
     * @param int    $counter number of items in the page
     * @param string $s       search string
     *
     * @return \stdClass received document list
     *
     * @see https://autentique.docs.apiary.io/#reference/0/documentos/lista-apenas-documentos-recebidos-que-nao-estao-numa-pasta.
     */
    public function listReceivedNotInFolder(int $page, int $count, string $s = '')
    {
        $result = $this->get(sprintf('%s/recebidos.json?page=%s&count=%s&s=%s', self::PATH, $page, $count, $s));

        return $result;
    }

    /**
     * List sent documents.
     *
     * @param int    $page    page counter
     * @param int    $counter number of items in the page
     * @param string $s       search string
     *
     * @return \stdClass list of documents sent
     *
     * @see https://autentique.docs.apiary.io/#reference/0/documentos/lista-apenas-documentos-enviados.
     */
    public function listOnlySent(int $page, int $count, string $s = '')
    {
        $result = $this->get(sprintf('%s/enviados.json?page=%s&count=%s&s=%s', self::PATH, $page, $count, $s));

        return $result;
    }

    /**
     * Creates a new document.
     *
     * @param string      $nome               document name
     * @param array       $partes             document signatories
     * @param resource    $arquivo            document file
     * @param bool        $rejeitavel         is rejectable
     * @param string|null $mensagem           document message
     * @param bool|null   $lembreteAssinatura sign reminder
     * @param bool|null   $lembreteVencimento expiration date reminder
     * @param string|null $frequencia         reminder sending
     * @param int|null    $diasVencimento     days left to due date
     * @param string|null $dataVencimento     due date
     * @param string|null $qrcode             QRcode type (B: bottom, L: left, R: right)
     *
     * @return \stdClass new document info
     *
     * @see https://autentique.docs.apiary.io/#reference/0/documentos/cria-um-novo-documento.
     */
    public function create(
        string $nome,
        array $partes,
        $arquivo,
        bool $rejeitavel,
        string $mensagem = null,
        bool $lembreteAssinatura = null,
        bool $lembreteVencimento = null,
        string $frequencia = null,
        int $diasVencimento = null,
        string $dataVencimento = null,
        string $qrcode = null
    ) {
        $data = [
            'nome'       => $nome,
            'partes'     => $partes,
            'arquivo'    => $arquivo,
            'rejeitavel' => $rejeitavel,
        ];

        if (!is_null($mensagem)) {
            $data['mensagem'] = $mensagem;
        }

        if (!is_null($lembreteAssinatura)) {
            $data['lembrete_assinatura'] = $lembreteAssinatura;
        }

        if (!is_null($lembreteVencimento)) {
            $data['lembrete_vencimento'] = $lembreteVencimento;
        }

        if (!is_null($diasVencimento)) {
            $data['dias_vencimento'] = $diasVencimento;
        }

        if (!is_null($frequencia)) {
            $data['frequencia'] = $frequencia;
        }

        if (!is_null($dataVencimento)) {
            $data['data_vencimento'] = $dataVencimento;
        }

        if (!is_null($qrcode)) {
            $data['rodape'] = $qrcode;
        }

        $payload = $this->buildCreatePayload($data);

        $result = $this->post(self::PATH.'.json', [
            'multipart' => $payload,
        ]);

        return $result;
    }

    /**
     * Resend signature email to signatories.
     *
     * @param string $uuid document uuid
     *
     * @return string an empty string
     *
     * @see https://autentique.docs.apiary.io/#reference/0/documentos/reenvia-email-de-assinatura-para-os-signatarios-que-nao-assinara/rejeitaram.
     */
    public function resendNotSignedOrRejected(string $uuid)
    {
        $result = $this->post(sprintf('%s/%s/reenviar.json', self::PATH, $uuid));

        return $result;
    }

    /**
     * Deletes a document with uuid $uuid.
     *
     * @param string $uuid document uuid
     *
     * @return \stdClass object with a message key (this is different from api documentation)
     *
     * @see https://autentique.docs.apiary.io/#reference/0/documentos/remove-documento-especifico.
     */
    public function deleteDocument(string $uuid)
    {
        $result = $this->delete(sprintf('%s/%s.json', self::PATH, $uuid));

        return $result;
    }

    /**
     * Build the payload for creating a new document.
     *
     * @param array $data creation data
     *
     * @return array guzzle multipart array
     *
     * @see http://docs.guzzlephp.org/en/stable/request-options.html#multipart.
     */
    private function buildCreatePayload(array $data)
    {
        $payload = [];

        foreach ($data['partes'] as $idx => $value) {
            $payload[] = [
                'name'     => 'partes[' . $idx . '][funcao]',
                'contents' => $value['funcao'] ?? null,
            ];

            $typeValueSigner = 'nome';
            if (array_key_exists('email', $value)) {
                $typeValueSigner = 'email';
            }

            $payload[] = [
                'name'     => "partes[" . $idx . "][{$typeValueSigner}]",
                'contents' => $value[$typeValueSigner] ?? null,
            ];
        }

        unset($data['partes']);

        foreach ($data as $key => $value) {
            if ($value === null) {
                continue;
            }

            if ($value === false) {
                $value = 'false';
            }

            $payload[] = [
                'name'     => $key,
                'contents' => $value,
            ];
        }

        return $payload;
    }
}
