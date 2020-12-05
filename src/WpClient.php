<?php

namespace Vnn\WpApiClient;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Vnn\WpApiClient\Auth\AuthInterface;
use Vnn\WpApiClient\Auth\WpJWTAuth;
use Vnn\WpApiClient\Endpoint;
use Vnn\WpApiClient\Endpoint\CustomPosts;
use Vnn\WpApiClient\Endpoint\CustomTaxonomies;
use Vnn\WpApiClient\Http\ClientInterface;
use Vnn\WpApiClient\Http\GuzzleAdapter;

/**
 * Class WpClient
 * @package Vnn\WpApiClient
 *
 * @method Endpoint\Categories categories()
 * @method Endpoint\Comments comments()
 * @method Endpoint\Media media()
 * @method Endpoint\Pages pages()
 * @method Endpoint\Posts posts()
 * @method Endpoint\PostStatuses postStatuses()
 * @method Endpoint\PostTypes postTypes()
 * @method Endpoint\Tags tags()
 * @method Endpoint\Users users()
 */
class WpClient
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var AuthInterface
     */
    private $credentials;

    /**
     * @var string
     */
    private $wordpressUrl;

    /**
     * @var array
     */
    private $endPoints = [];

    /**
     * WpClient constructor.
     */
    public function __construct()
    {
        $wordpressUrl = config('wordpress.url');

        if (!$wordpressUrl) {
            throw new InvalidArgumentException('You need to set WP_REST_API_URL in config');
        }

        $this->httpClient = new GuzzleAdapter();
        $this->wordpressUrl = $wordpressUrl;

        $authClass = config("wordpress.authClass");
        $this->setCredentials((new $authClass())->setClient($this));
    }

    /**
     * @param $wordpressUrl
     */
    public function setWordpressUrl($wordpressUrl)
    {
        $this->wordpressUrl = $wordpressUrl;
    }

    /**
     * @param AuthInterface $auth
     */
    public function setCredentials(AuthInterface $auth)
    {
        $this->credentials = $auth;
    }

    /**
     * @param $endpoint
     * @param array $args
     * @return Endpoint\AbstractWpEndpoint
     */
    public function __call($endpoint, array $args)
    {
        if (!isset($this->endPoints[$endpoint])) {
            $class = 'Vnn\WpApiClient\Endpoint\\' . ucfirst($endpoint);
            if (class_exists($class)) {
                $this->endPoints[$endpoint] = new $class($this);
            } elseif (in_array($endpoint, config('wordpress.customPostTypes'))) {
                $class = new CustomPosts($this);
                $class->setSlug($endpoint);
                $this->endPoints[$endpoint] = $class;
            } elseif (in_array($endpoint, config('wordpress.customTaxonomies'))) {
                $class = new CustomTaxonomies($this);
                $class->setSlug($endpoint);
                $this->endPoints[$endpoint] = $class;
            } else {
                throw new RuntimeException('Endpoint "' . $endpoint . '" does not exist"');
            }
        }

        return $this->endPoints[$endpoint];
    }

    public function send(RequestInterface $request, bool $withCredentials = true) : ResponseInterface
    {
        if ($this->credentials && true === $withCredentials) {
            $request = $this->credentials->addCredentials($request);
        }

        $request = $request->withUri(
            $this->httpClient->makeUri($this->wordpressUrl . '/wp-json/wp/v2/' . $request->getUri())
        );

        return $this->httpClient->send($request);
    }
}
