<?php

namespace Vnn\WpApiClient\Endpoint;

use InvalidArgumentException;

/**
 * Class Posts.
 */
class CustomTaxonomies extends AbstractCustomTypesEndpoint
{
    public function delete(int $id, array $params = null) : void
    {
        $params['force'] = true;
        parent::delete($id, $params);
    }
}
