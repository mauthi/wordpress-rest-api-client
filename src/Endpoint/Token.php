<?php

namespace Vnn\WpApiClient\Endpoint;

use Vnn\WpApiClient\WpClient;

/**
 * Class Users.
 */
class Token extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    public function __construct(WpClient $client)
    {
        $this->withCredentials = false;
        parent::__construct($client);
    }

    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return 'token';
    }
}
