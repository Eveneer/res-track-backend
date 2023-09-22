<?php

declare(strict_types=1);

namespace App\Domain\Authentication\Actions;

use App\Domain\User\User;
use App\Domain\UserInvite\Actions\CanRegister;
use Lorisleiva\Actions\Concerns\AsAction;

class IsAllowed
{
    use AsAction;

    public function handle(string $email): bool
    {
        return CanRegister::run($email) || User::whereEmail($email)->exists();
    }
}