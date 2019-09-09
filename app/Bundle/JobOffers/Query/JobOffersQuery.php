<?php declare(strict_types=1);

namespace App\Bundle\JobOffers\Query;

use App\Bundle\JobOffers\Exception\GuzzleException;
use App\Bundle\JobOffers\Exception\GuzzleInvalidBodyTypeException;
use App\Bundle\JobOffers\Exception\GuzzleInvalidConnectionToAPIException;
use App\Bundle\JobOffers\Exception\GuzzleInvalidStatusCodeException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

class JobOffersQuery
{
    /** @var Client */
    private $clientGuzzle;
    /** @var string */
    private $url;
    /** @var int  */
    private static $successCode = 200;

    public function __construct(Client $clientGuzzle)
    {
        $this->url = env('API_V1_URL');
        $this->clientGuzzle = $clientGuzzle;
    }

    /**
     * @return string
     * @throws GuzzleException
     * @throws GuzzleInvalidBodyTypeException
     * @throws GuzzleInvalidConnectionToAPIException
     * @throws GuzzleInvalidStatusCodeException
     */
    public function getAll(): string
    {
        $result = $this->callToApi();

        $this->checkStatusCode($result);

        $body = $this->checkResponseBody($result);

        return $body;
    }

    /**
     * @return string
     * @throws GuzzleException
     * @throws GuzzleInvalidBodyTypeException
     * @throws GuzzleInvalidConnectionToAPIException
     * @throws GuzzleInvalidStatusCodeException
     */
    public function getStatus(): string
    {
        return $this->getAll();
    }
    /**
     * @return ResponseInterface
     * @throws GuzzleException
     * @throws GuzzleInvalidConnectionToAPIException
     */
    private function callToApi(): ResponseInterface
    {
        try {
            $result = $this->clientGuzzle->get($this->url);
        } catch (ClientException $e) {
            throw new GuzzleInvalidConnectionToAPIException();
        } catch (\Exception $e) {
            throw new GuzzleException();
        }
        return $result;
    }

    /**
     * @param ResponseInterface $result
     * @throws GuzzleInvalidStatusCodeException
     * @return int
     */
    private function checkStatusCode(ResponseInterface $result): int
    {
        if ($result->getStatusCode() !== self::$successCode) {
            throw new GuzzleInvalidStatusCodeException();
        }

        return $result->getStatusCode();
    }

    /**
     * @param ResponseInterface $result
     * @return string
     * @throws GuzzleInvalidBodyTypeException
     */
    private function checkResponseBody(ResponseInterface $result): string
    {
        $body = $result->getBody()->getContents();
        if (!is_string($body)) {
            throw new GuzzleInvalidBodyTypeException();
        }
        return $body;
    }
}
