<?php

namespace Hyvor\Internal\Tests\Unit\Auth;

use Hyvor\Internal\Auth\AuthUser;
use Hyvor\Internal\Auth\HasUser;

class ModelWithHasUser
{
    use HasUser;
    public $user_id = 10;
}

it('has user', function () {
    $model = new ModelWithHasUser();
    $user = $model->user();
    expect($user)->toBeInstanceOf(AuthUser::class);
    expect($user->id)->toBe(10);
});

it('returns null if user_id is not set', function () {
    $model = new ModelWithHasUser();
    $model->user_id = null;
    expect($model->user())->toBeNull();
});