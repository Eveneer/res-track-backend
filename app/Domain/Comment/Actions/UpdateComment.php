<?php

declare(strict_types=1);

namespace App\Domain\Comment\Actions;

use App\Domain\Comment\Comment;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateComment
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
            return Response::deny('You do not have permission to edit this comment');
        }

        return Response::allow();
    }

    public function handle(array $data): Comment
    {
        $comment = Comment::find($data['id']);
        $comment->fill($data);
        $comment->save();

        return $comment;
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(Comment $comment): array
    {
        return [
            'message' => 'Comment updated',
            'data' => [
                'comment' => $comment
            ]
        ];
    }
}