<?php

namespace Inmobile\InmobileSDK;

class CurlClient
{
    protected ?string $baseUri = null;
    protected string $apiKey;

    public function __construct(string $apiKey, string $baseUri = null)
    {
        $this->baseUri = $baseUri;
        $this->apiKey = $apiKey;
    }

    public function request(string $method, string $url, ?array $data = null)
    {
        $curl = curl_init(
            sprintf('%s/%s', rtrim($this->baseUri, '/'), ltrim($url, '/'))
        );

        curl_setopt($curl, CURLOPT_HEADER, 0);
        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1);
        }
        if ($method === 'POST' && $data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        if ($method === 'PUT') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        }

        if ($method === 'PUT' && $data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        if ($method === 'DELETE') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $inmobileClientVersion = 'Inmobile PHP Client v4.0.1.24';
        curl_setopt($curl, CURLOPT_USERPWD, ':' . $this->apiKey);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json','X-InmobileClientVersion:'.$inmobileClientVersion]);
        curl_setopt($curl, CURLOPT_USERAGENT, $inmobileClientVersion);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($curl);

        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return [$response, $httpStatus];
    }
}
