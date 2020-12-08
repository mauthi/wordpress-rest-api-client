<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class PostStatuses.
 */
class PostStatuses extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return 'statuses';
    }
}
