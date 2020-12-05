<?php

namespace Vnn\WpApiClient\Endpoint;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Vnn\WpApiClient\WpClient;

/**
 * Class AbstractWpEndpoint
 * @package Vnn\WpApiClient\Endpoint
 */
abstract class AbstractWpEndpoint
{
    /**
     * @var WpClient
     */
    protected $client;

    protected bool $withCredentials = true;

    protected ?int $pages;

    /**
     * Users constructor.
     * @param WpClient $client
     */
    public function __construct(WpClient $client)
    {
        $this->client = $client;
    }

    abstract protected function getEndpoint();

    /**
     * @param int $id
     * @param array $params - parameters that can be passed to GET
     *        e.g. for tags: https://developer.wordpress.org/rest-api/reference/tags/#arguments
     */
    public function get(?int $id = null, array $params = null) : array
    {
        $uri = $this->getEndpoint();
        $uri .= (is_null($id) ? '' : '/' . $id);
        $uri .= (is_null($params) ? '' : '?' . http_build_query($params));

        $request = new Request('GET', $uri);
        $response = $this->client->send($request);

        return $this->getResponse($response, $id);
    }

    public function delete(int $id, array $params = null) : void
    {
        $uri = $this->getEndpoint();
        $uri .= '/' . $id;
        $uri .= (is_null($params) ? '' : '?' . http_build_query($params));

        $request = new Request('DELETE', $uri);
        $response = $this->client->send($request);

        if (isset($params['force']) && $params['force'] && !$this->getResponseKey($response, "deleted", false)) {
            throw new RuntimeException("Delete not successfull for id {$id} / endpoint: {$this->getEndpoint()}");
        }
    }

    public function getAll(array $params = []) : array
    {
        $result = [];

        if (isset($params['page'])) {
            throw new RuntimeException('You are not allowed to set page for getAll requests');
        }
        $params['page'] = 1;

        if (!isset($params['per_page'])) {
            $params['per_page'] = 100;
        }

        $data = $this->get(null, $params);
        if (is_int($this->pages) && $this->pages > 1) {
            do {
                $params['page']++;
                array_push($data, $this->get(null, $params));
            } while ($params['page'] < $this->pages);
        }

        return $data;
    }

    /**
     * @param array $data
     * @return array
     * @throws \RuntimeException
     */
    public function save(array $data)
    {
        $url = $this->getEndpoint();
        $id = null;

        if (isset($data['id'])) {
            $id = $id;
            $url .= '/' . $data['id'];
            unset($data['id']);
        }

        $json = json_encode($data);
        $request = new Request('POST', $url, [
            'Content-Type' => 'application/json'
        ], $json);

        $response = $this->client->send($request, $this->withCredentials);
        return $this->getResponse($response, $id);
    }

    public function updateOrCreate(?int $id, array $data) : array
    {
        if (null !== $id) {
            $data['id'] = $id;
        }

        return $this->save($data);
    }

    public function update(int $id, array $data) : array
    {
        $data['id'] = $id;

        return $this->save($data);
    }

    /**
     * @throws \RuntimeException
     */
    private function getResponse(ResponseInterface $response, ?int $id = null) : array
    {
        if (!$response->hasHeader('Content-Type')
            || substr($response->getHeader('Content-Type')[0], 0, 16) !== 'application/json') {
            throw new RuntimeException('Unexpected response');
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (null !== $id && $id !== $data['id']) {
            throw new RuntimeException('ID not in response or wrong');
        }

        $this->pages = null;
        if ($response->hasHeader('X-WP-TotalPages')) {
            $this->pages = $response->getHeader('X-WP-TotalPages')[0];
        }

        return $data;
    }

    /**
     * @return bool|mixed
     */
    private function getResponseKey(ResponseInterface $response, string $key, bool $throwException = true, $defaultValue = false)
    {
        if (!$response->hasHeader('Content-Type')
            || substr($response->getHeader('Content-Type')[0], 0, 16) !== 'application/json') {
            throw new RuntimeException('Unexpected response');
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data[$key])) {
            if ($throwException) {
                throw new RuntimeException("Key {$key} not in response!");
            }
            return $defaultValue;
        }

        return $data[$key];
    }
}
