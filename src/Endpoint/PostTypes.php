<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class PostTypes.
 */
class PostTypes extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return 'types';
    }
}
