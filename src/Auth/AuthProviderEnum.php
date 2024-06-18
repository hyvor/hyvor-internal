<?php

namespace Hyvor\Internal\Auth;

enum AuthProviderEnum : string
{
    case HYVOR = 'hyvor';
    case FAKE = 'fake';

}