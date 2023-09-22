<?php

declare(strict_types=1);

namespace App\Domain\Comment\Actions;

use App\Domain\Comment\Comment;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteComment
{
    use AsAction;

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:comments,id'],
            'user_id' => ['required', 'exists:users,id'],
            'resource_id' => ['required', 'exists:resources'],
            'value' => ['required', 'string']
        ];
    }

    public function authorize(ActionRequest $request): Response
    {
        if (auth()->user()->id !== $request->user_id) {
            return Response::deny('You do not have permission to delete this comment');
        }

        return Response::allow();
    }

    public function handle(array $data): bool
    {
        $comment = Comment::find($data['id']);

        return $comment->delete();
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(bool $is_deleted): array
    {
        $message = $is_deleted ? 'Comment deleted' :'Comment could not be deleted';
        
        return [
            'message' => $message,
            'data' => [
                'comment' => $message
            ]
        ];
    }
}