<?php

namespace Vnn\WpApiClient\Auth;

use Psr\Http\Message\RequestInterface;

/**
 * Interface AuthInterface.
 */
interface AuthInterface
{
    /**
     * @param RequestInterface $request
     * @return mixed
     */
    public function addCredentials(RequestInterface $request);
}
