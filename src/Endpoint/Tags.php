<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class Tags.
 */
class Tags extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return 'tags';
    }
}
