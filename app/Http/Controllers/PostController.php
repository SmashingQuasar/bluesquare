<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;

class PostController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function new(Request $request)
    {
        return Jetstream::inertia()->render($request, 'Post/New', [
            'sessions' => $this->sessions($request)->all(),
        ]);
        // return Inertia::render('Post/New');
    }
    public function sessions(Request $request)
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::table('sessions')
                    ->where('user_id', $request->user()->getKey())
                    ->orderBy('last_activity', 'desc')
                    ->get()
        )->map(function ($session) use ($request) {
            $agent = $this->createAgent($session);

            return (object) [
                'agent' => [
                    'is_desktop' => $agent->isDesktop(),
                    'platform' => $agent->platform(),
                    'browser' => $agent->browser(),
                ],
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === $request->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    public function create(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        if (empty($content['title']))
        {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Missing field `title`.'
                ]
            );
        }

        if (empty($content['content']))
        {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Missing field `content`.'
                ]
            );
        }

        $post = new Post();
        $post->setAttribute('title', $content['title']);
        $post->setAttribute('content', $content['content']);
        $post->save();

        return response()->json(
            [
                'success' => true,
                'message' => 'Success.'
            ]
        );
    }

    public function read(Request $request)
    {
        $id = $request->input('id');

        $post = Post::where('id', $id)->first();
        
        return response()->json(
            [
                'success' => true,
                'message' => 'Success.',
                'data' => [
                    'id' => $post->id,
                    'title' => $post->title,
                    'content' => $post->content,
                    'creation_date' => $post->created_at
                ]
            ]
        );
    }

    public function all(Request $request)
    {
        $posts = Post::all();

        $output = [];

        foreach ($posts as $post)
        {
            $output[] = [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'creation_date' => $post->created_at
            ];
        }

        return response()->json(
            [
                'success' => true,
                'message' => 'Success.',
                'data' => $output
            ]
        );
    }

}
