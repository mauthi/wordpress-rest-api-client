<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class Users.
 */
class Users extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return 'users';
    }
}
