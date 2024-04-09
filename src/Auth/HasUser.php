<?php

namespace Hyvor\Internal\Auth;

trait HasUser
{

    public function user() : AuthUser|null
    {
        if (!isset($this->user_id)) {
            return null;
        }
        $userId = $this->user_id;

        return AuthUser::fromId($userId);
    }

}