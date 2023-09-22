<?php

declare(strict_types=1);

namespace App\Domain\User\Actions;

use App\Actions\Fortify\PasswordValidationRules;
use App\Domain\Authentication\Actions\IsAllowed;
use App\Domain\User\User;
use Illuminate\Validation\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateUser implements CreatesNewUsers
{
    use AsAction;
    use PasswordValidationRules;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'string', 'max:255'],
            'password' => $this->passwordRules(),
            'name' => ['required', 'string', 'max:255']
        ];
    }

    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        if (!IsAllowed::run($request->email)) {
            $validator->errors()->add('email', 'Credentials not allowed');
        }
    }

    public function handle(array $data): User
    {
        return User::create($data);
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->validated());
    }

    public function create(array $input): User
    {
        return $this->handle($input);
    }

    public function jsonResponse(User $user): array
    {
        return [
            'message' => 'User created',
            'data' => [
                'user' => $user
            ]
        ];
    }
}