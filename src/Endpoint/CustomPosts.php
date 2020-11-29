<?php

namespace Vnn\WpApiClient\Endpoint;

use InvalidArgumentException;

/**
 * Class Posts
 * @package Vnn\WpApiClient\Endpoint
 */
class CustomPosts extends AbstractWpEndpoint
{
    private string $slug;

    public function setSlug($slug) : void
    {
        $this->slug = $slug;
    }

    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        if (!$this->slug) {
            throw new InvalidArgumentException("No slug set for CustomPosts class.");
        }

        return $this->slug;
    }
}
