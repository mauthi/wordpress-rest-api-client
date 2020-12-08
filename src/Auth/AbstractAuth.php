<?php

namespace Vnn\WpApiClient\Auth;

use Vnn\WpApiClient\WpClient;

/**
 * Class AbstractAuth.
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
