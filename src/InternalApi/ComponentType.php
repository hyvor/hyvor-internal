<?php

namespace Hyvor\Internal\InternalApi;

enum ComponentType : string
{
    case CORE = 'core';
    case TALK = 'talk';
    case BLOGS = 'blogs';

    public static function fromConfig() : self
    {
        $config = config('hyvor-internal.component');
        return self::from($config);
    }

    public function getCoreUrl() : string
    {
        $currentUrl = config('app.url');

        if ($this === ComponentType::CORE) {
            return $currentUrl;
        } else {

            $protocol = strval(parse_url($currentUrl, PHP_URL_SCHEME)) . '://';
            $host = strval(parse_url($currentUrl, PHP_URL_HOST));

            $componentSubdomain = $this->value;
            $coreHost = preg_replace('/^' . $componentSubdomain . '\./', '', $host);

            return $protocol . $coreHost;
        }
    }

    public function getUrlOf(self $type) : string
    {

        $coreUrl = $this->getCoreUrl();

        if ($type === self::CORE) {
            return $coreUrl;
        } else {

            $subdomain = $type->value;

            $coreHost = parse_url($coreUrl, PHP_URL_HOST);
            $protocol = parse_url($coreUrl, PHP_URL_SCHEME) . '://';

            return $protocol . $subdomain . '.' . $coreHost;

        }

    }

}