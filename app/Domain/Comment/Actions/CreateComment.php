<?php

declare(strict_types=1);

namespace App\Domain\Comment\Actions;

use App\Domain\Comment\Comment;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateComment
{
    use AsAction;

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'resource_id' => ['required', 'exists:resources'],
            'value' => ['required', 'string']
        ];
    }

    public function handle(array $data): Comment
    {
        return Comment::create($data);
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(Comment $comment): array
    {
        return [
            'message' => 'Comment created',
            'data' => [
                'comment' => $comment
            ]
        ];
    }
}