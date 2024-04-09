<?php

namespace Hyvor\Internal\InternalApi;

enum ComponentType : string
{
    case CORE = 'core';
    case TALK = 'talk';
    case BLOGS = 'blogs';


    public function getUrlOf(self $type) : string
    {

        $url = config('app.url');

    }

}