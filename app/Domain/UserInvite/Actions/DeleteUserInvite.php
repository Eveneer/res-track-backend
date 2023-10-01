<?php

declare(strict_types=1);

namespace App\Domain\UserInvite\Actions;

use App\Domain\UserInvite\UserInvite;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteUserInvite
{
    use AsAction;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:user_invites,email']
        ];
    }

    public function handle(array $data): bool
    {
        $invite = UserInvite::whereEmail($data['email'])->first();

        if ($invite->accepted) {
            return false;
        }

        return $invite->delete();
    }

    public function asController(ActionRequest $request): bool
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(bool $is_deleted): array
    {
        $message = $is_deleted ? 'Invite deleted' :'Invite could not be deleted';
        
        return [
            'message' => $message,
            'data' => [
                'message' => $message,
                'is_deleted' => $is_deleted
            ]
        ];
    }
}