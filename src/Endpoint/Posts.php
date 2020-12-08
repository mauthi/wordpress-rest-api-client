<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class Posts.
 */
class Posts extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return 'posts';
    }
}
