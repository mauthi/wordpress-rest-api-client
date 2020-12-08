<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class Categories.
 */
class Categories extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return 'categories';
    }
}
