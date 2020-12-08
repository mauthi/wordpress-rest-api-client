<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class Comments.
 */
class Comments extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return 'comments';
    }
}
