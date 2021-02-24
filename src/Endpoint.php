<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 * @license https://github.com/marciodojr/autentique/blob/master/LICENSE (MIT License)
 */

namespace Mdojr\Autentique;

/**
 * This class holds endpoint constants for sandbox and production environments.
 */
class Endpoint
{
    /**
     * @var string Sandbox endpoint.
     * This Endpoint was deprecated in v2 from API.
     */
    //const SANDBOX = 'https://sandbox.autentique.com.br';

    /**
     * @var string Production endpoint.
     */
    const PRODUCTION = 'https://api.autentique.com.br';
}
