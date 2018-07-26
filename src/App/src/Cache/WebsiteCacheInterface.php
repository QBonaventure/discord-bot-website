<?php

declare(strict_types=1);

namespace App\Cache;

interface WebsiteCacheInterface
{
    
    public function setLoginRedirectUrl($state, $redirectUrl) : bool;
    
    public function getLoginRedirectUrl($state) : string;
    
}
