<?php

namespace Hyvor\Helper\Auth;

enum AuthProviderEnum : string
{
    case HYVOR = 'hyvor';
    case OIDC = 'oidc';
    case FAKE = 'fake';

}