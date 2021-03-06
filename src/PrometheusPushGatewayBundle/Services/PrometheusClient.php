<?php

namespace Comsave\PrometheusPushGatewayBundle\Services;

use Comsave\PrometheusPushGatewayBundle\Model\PrometheusResponse;
use Comsave\PrometheusPushGatewayBundle\Model\PrometheusResponseDataResult;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use JMS\Serializer\Serializer;

// todo: move this out to a separate repo later
class PrometheusClient
{
    /** @var string */
    private $prometheusUrl;

    /** @var Serializer */
    private $jmsSerializer;

    /** @var ClientInterface */
    private $httpClient;

    /** @var string|null */
    private $username;

    /** @var string|null */
    private $password;

    /**
     * @param string $prometheusUrl
     * @param Serializer $jmsSerializer
     * @param ClientInterface $httpClient
     * @param null|string $username
     * @param null|string $password
     * @codeCoverageIgnore
     */
    public function __construct(
        string $prometheusUrl,
        Serializer $jmsSerializer,
        ClientInterface $httpClient,
        ?string $username = null,
        ?string $password = null
    ) {
        $this->prometheusUrl = $prometheusUrl;
        $this->jmsSerializer = $jmsSerializer;
        $this->httpClient = $httpClient;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param string $query
     * @return array|PrometheusResponseDataResult[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function query(string $query): array
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                sprintf('%s/api/v1/query', $this->prometheusUrl),
                $this->buildRequestOptions(
                    [
                        'form_params' => [
                            'query' => $query,
                        ],
//                        'content-type' => 'application/x-www-form-urlencoded',
                        // todo: include instance
                    ]
                )
            );
        }
        catch(ClientException $ex) {
            // todo:
            throw $ex;
        }

        /** @var PrometheusResponse $response */
        $response = $this->jmsSerializer->deserialize((string)$response->getBody(), PrometheusResponse::class, 'json');

        return $response->getData()->getResults();
    }

    public function requireLabels(array $labels): string
    {
        return implode(',', array_map(function(string $label) {
            return sprintf('%s=~".+"', $label);
        }, $labels));
    }

    private function buildRequestOptions(array $requestOptions): array
    {
        if ($this->username && $this->password) {
            $requestOptions['auth'] = [
                $this->username,
                $this->password,
            ];
        }

        return $requestOptions;
    }
}