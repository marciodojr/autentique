<?php
/**
 * @author Márcio Dias <marciojr91@gmail.com>
 * @license https://github.com/marciodojr/autentique/blob/master/LICENSE (MIT License)
 */

namespace Mdojr\Autentique\Resource;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use InvalidArgumentException;
use Mdojr\Autentique\Endpoint;

/**
 * This abstract class simplify resource implementations.
 */
abstract class AbstractResource
{
    /**
     * @var \GuzzleHttp\Client Guzzle request client.
     */
    protected $client;

    /**
     * Initializes a autentique resource with a configured guzzle client.
     *
     * @param \GuzzleHttp\Client $client Guzzle http client.
     *
     * @throws \InvalidArgumentException if a wrong endpoint was provided
     */
    public function __construct(Client $client)
    {
        $baseUri = $client->getConfig('base_uri');
        if (!in_array($baseUri, [
            Endpoint::SANDBOX,
            Endpoint::PRODUCTION,
        ])) {
            throw new InvalidArgumentException(sprintf('endpoint \'%s\' não é válido', $baseUri));
        }

        $this->client = $client;
    }

    /**
     * Uses \GuzzleHttp\Client to communicate with autentique api.
     *
     * @param string $path    request path.
     * @param string $method  request method (GET, POST, PUT or DELETE).
     * @param array  $payload request data to send.
     *
     * @throws \Exception if a 4xx or 5xx http response code is returned.
     *
     * @return \stdClass|string response data from autentique api.
     */
    private function httpRequest(string $path, $method = 'GET', $payload = [])
    {
        try {
            $response = $this->client->request($method, $path, $payload);
            $code = $response->getStatusCode();
            $responseContentType = $response->getHeaderLine('Content-Type');
            $body = $response->getBody();

            if (false !== strpos($responseContentType, 'application/json')) {
                return json_decode($body);
            }

            return $body->getContents();
        } catch (ClientException $ce) {
            $response = $ce->getResponse();
            $body = json_decode($response->getBody(), true);
            $message = $body ? json_encode($body) : 'Erro inesperado';

            throw new Exception($message, $response->getStatusCode());
        }
    }

    /**
     * Protected wrapper for a 'GET' request.
     *
     * @param string $path request path.
     *
     * @return \stdClass|string response data from autentique api.
     */
    protected function get(string $path)
    {
        return $this->httpRequest($path);
    }

    /**
     * Wrapper for a 'POST' request.
     *
     * @param string $path    request path.
     * @param array  $payload request payload.
     *
     * @return \stdClass|string response data from autentique api.
     */
    protected function post(string $path, array $payload = [])
    {
        return $this->httpRequest($path, 'POST', $payload);
    }

    /**
     * Wrapper for a 'PUT' request.
     *
     * @param string $path    request path.
     * @param array  $payload request payload.
     *
     * @return \stdClass|string response data from autentique api.
     */
    protected function put(string $path, array $payload = [])
    {
        return $this->httpRequest($path, 'PUT', $payload);
    }

    /**
     * Wrapper for a 'DELETE' request.
     *
     * @param string $path request path.
     *
     * @return \stdClass|string response data from autentique api.
     */
    protected function delete(string $path)
    {
        return $this->httpRequest($path, 'DELETE');
    }
}
