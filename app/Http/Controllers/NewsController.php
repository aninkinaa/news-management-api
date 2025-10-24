<?php

namespace App\Http\Controllers;

use App\Events\NewsCreated;
use App\Events\NewsDeleted;
use App\Events\NewsUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;

class NewsController extends Controller
{
    protected $newsRepository;

    public function __construct(NewsRepositoryInterface $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }
    
    public function index(Request $request)
    {
        $filters = [
            'user_id' => $request->get('user_id'),
            'keyword' => $request->get('keyword'),
        ];

        $news = $this->newsRepository->allPaginated(10, ['user'], $filters);

        $usedFilters = array_filter($filters);

        if (!empty($usedFilters) && $news->isEmpty()) {
            
            return response()->json([
                'message' => 'No news found.'
            ], 404);
        }

        return NewsResource::collection($news);
    }


    public function store(StoreNewsRequest $request)
    {
        $data = $request->validated();

        $data['image']   = $request->file('image')->store('news', 'public');
        $data['user_id'] = Auth::id();


        $news = $this->newsRepository->create($data);

        event(new NewsCreated($news));

        $news->load('user');

        return (new NewsResource($news))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id)
    {
        $news = $this->newsRepository->findWithComments($id);

        if (! $news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        return new NewsResource($news);
    }

    public function update(UpdateNewsRequest $request, int $id)
    {
        $data = $request->validated();

        $news = $this->newsRepository->findById($id);

        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        if (!$data){
            return response()->json(['message' => 'No data provided for update'], 400);
        }

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $updatedNews = $this->newsRepository->update($id, $data);

        event(new NewsUpdated($updatedNews));

        $updatedNews->load(['user', 'comments']);

        return new NewsResource($updatedNews);
    }

    public function destroy(int $id)
    {
        $news = $this->newsRepository->findById($id);

        if (! $news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        $deleted = $this->newsRepository->delete($id);

        if (! $deleted) {
            return response()->json(['message' => 'Failed to delete news'], 500);
        }

        event(new NewsDeleted($news));

        return response()->json(['message' => 'News deleted successfully']);
    }
}
