<?php

namespace Vnn\WpApiClient\Auth;

use Vnn\WpApiClient\WpClient;

/**
 * Class AbstractAuth
 * @package Vnn\WpApiClient\Auth
 */
abstract class AbstractAuth
{
    protected WpClient $client;
    
    public function setClient(WpClient $client) : AuthInterface
    {
        $this->client = $client;

        return $this;
    }
}
