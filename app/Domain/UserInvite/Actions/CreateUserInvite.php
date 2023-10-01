<?php

declare(strict_types=1);

namespace App\Domain\UserInvite\Actions;

use App\Domain\UserInvite\UserInvite;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateUserInvite
{
    use AsAction;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email']
        ];
    }

    public function handle(array $data): UserInvite
    {
        return UserInvite::create($data);
    }

    public function asController(ActionRequest $request): UserInvite
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(UserInvite $invite): array
    {
        return [
            'message' => 'Invite created',
            'data' => [
                'invite' => $invite
            ]
        ];
    }
}