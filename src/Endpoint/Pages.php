<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class Pages.
 */
class Pages extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return 'pages';
    }
}
