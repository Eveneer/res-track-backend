<?php

declare(strict_types=1);

namespace App\Domain\UserInvite\Actions;

use App\Domain\UserInvite\UserInvite;
use Lorisleiva\Actions\Concerns\AsAction;

class CanRegister
{
    use AsAction;


    public function handle(string $email): bool
    {
        return UserInvite::whereEmail($email)
            ->whereAccepted(false)
            ->exists();
    }
}