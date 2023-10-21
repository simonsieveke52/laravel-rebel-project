<?php

namespace App\ResponseCache;

use Illuminate\Http\Request;
use Spatie\ResponseCache\Hasher\RequestHasher;
use Spatie\ResponseCache\CacheProfiles\CacheProfile;

class CacheHasher implements RequestHasher
{
    /**
     * @var CacheProfile
     */
    protected $cacheProfile;

    public function __construct(CacheProfile $cacheProfile)
    {
        $this->cacheProfile = $cacheProfile;
    }

    public function getHashFor(Request $request): string
    {
        return 'response-cache-'.md5(
            "{$request->getHost()}-{$request->path()}-{$request->getMethod()}/".$this->cacheProfile->useCacheNameSuffix($request)
        );
    }
}
