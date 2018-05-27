<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 */

namespace Mdojr\Autentique\Resource;

/**
 * This class allows account manipulation.
 */
final class Account extends AbstractResource
{
    /**
     * @var string api path for account info.
     */
    const PATH = 'pessoas/me.json';

    /**
     * Returns account information.
     * 
     * @return \stdClass account information or empty string.
     * 
     * @see https://autentique.docs.apiary.io/#reference/0/contas/informacoes-de-uma-conta.
     */
    public function info()
    {
        $result = $this->get(self::PATH);
        return $result;
    }
}
