<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CommentResource;
use App\Jobs\CreateCommentJob;

class CommentController extends Controller
{

    public function store(Request $request, News $news)
    {

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        CreateCommentJob::dispatch($news, Auth::user(), $request->content);

        return response()->json([
            'message' => 'Your comment is being processed.'
        ], 202);
    }

    public function update(Request $request, Comment $comment)
    {

        if ($comment->user_id !== Auth::id()) {
            return response()->json(
                ['message' => 'Forbidden: You are not the owner of this comment.']
            , 403);
        }


        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $comment->update($validator->validated());
        
        $comment->load('user');

        return new CommentResource($comment);
    }


    public function destroy(Comment $comment)
    {

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden: You are not the owner of this comment.'], 403);
        }
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully',
            'data' => (new CommentResource($comment)),
        ]);
    }
}