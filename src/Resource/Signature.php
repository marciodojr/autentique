<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 */

namespace Mdojr\Autentique\Resource;

/**
 * This class allows signature manipulation.
 */
class Signature extends AbstractResource
{
    /**
     * @var string api path for signature manipulation.
     */
    const PATH = 'assinatura';

    /**
     * Retrieves a signature.
     * 
     * @param string $token document signature token.
     * 
     * @return \stdClass signature data.
     * 
     * @see https://autentique.docs.apiary.io/#reference/0/assinatura/resgata-informacoes-sobre-a-assinatura.
     */
    public function retrieve(string $token)
    {
        $result = $this->get(sprintf('%s/%s.json', self::PATH, $token));
        return $result;
    }

    /**
     * Signs a document.
     * 
     * @param string document $token signature token.
     * 
     * @return \stdClass signature data.
     * 
     * @see https://autentique.docs.apiary.io/#reference/0/assinatura/signatario-aceita-o-documento-(assina,-reconhece,-visualiza-etc).
     */
    public function accept(string $token)
    {
        $result = $this->post(sprintf('%s/%s/aceitar.json', self::PATH, $token));
        return $result;
    }

    /**
     * Rejects a document if possible.
     * 
     * @param string $token document signature token.
     * @param string $motivo reason to reject.
     * 
     * @return \stdClass signature data.
     * 
     * @see https://autentique.docs.apiary.io/#reference/0/assinatura/signatario-rejeita-um-documento-(quando-possivel)
     */
    public function reject(string $token, string $motivo)
    {
        $result = $this->post(sprintf('%s/%s/rejeitar.json', self::PATH, $token), [
            'json' => [
                'motivo' => $motivo
            ]
        ]);
        return $result;
    }

}
