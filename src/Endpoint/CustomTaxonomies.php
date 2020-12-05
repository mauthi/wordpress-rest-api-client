<?php

namespace Vnn\WpApiClient\Endpoint;

use InvalidArgumentException;

/**
 * Class Posts
 * @package Vnn\WpApiClient\Endpoint
 */
class CustomTaxonomies extends AbstractCustomTypesEndpoint
{
    public function delete(int $id, array $params = null) : void
    {
        $params['force'] = true;
        parent::delete($id, $params);
    }
}
