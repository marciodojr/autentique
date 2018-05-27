<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 */

namespace Mdojr\Autentique;

use GuzzleHttp\Client;

use Mdojr\Autentique\Resource\Folder;
use Mdojr\Autentique\Resource\Document;
use Mdojr\Autentique\Resource\Account;
use Mdojr\Autentique\Resource\Signature;

/**
 * This class helps to create Folders, Documents, Accounts and Signatures
 */
class Autentique
{
    /**
     * @var \GuzzleHttp\Client http request client.
     */
    private $client;

    /**
     * Initializes the Autentique object with a configured guzzle client.
     * 
     * Here's an example of creating an Autentique object:
     *
     *     $client = new \GuzzleHttp\Client([
     *          'base_uri' => 'https://sandbox.autentique.com.br', // sandbox endpoint or production endpoint
     *          'headers' => [
     *              'X-Autntiq-Api' => $token // sandbox token or production token
     *          ]
     *     ]);
     * 
     *     $autentique = \Mdojr\Autentique\Autentique($client);
     * 
     * @param \GuzzleHttp\Client $client Guzzle http client.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Creates a \Mdojr\Autentique\Resource\Folder object.
     * 
     * @see Mdojr\Autentique\Resource\Folder for a list of available operations.
     */
    public function folder(): Folder
    {
        return new Folder($this->client);
    }

    /**
     * Creates a Mdojr\Autentique\Resource\Document object.
     * 
     * @see Mdojr\Autentique\Resource\Document for a list of available operations.
     */
    public function document(): Document
    {
        return new Document($this->client);
    }

    /**
     * Creates an \Mdojr\Autentique\Resource\Account object.
     * 
     * @see \Mdojr\Autentique\Resource\Account for a list of available operations.
     */
    public function account(): Account
    {
        return new Account($this->client);
    }

    /**
     * Creates a \Mdojr\Autentique\Resource\Signature object.
     * 
     * @see \Mdojr\Autentique\Resource\Signature for a list of available operations.
     */
    public function signature(): Signature
    {
        return new Signature($this->client);
    }
}
