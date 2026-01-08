<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PodcastEpisode;
use App\Models\Podcast;
use App\Models\Instructor;
use App\Http\Requests\StoreEpisodeRequest;
use App\Http\Requests\UpdateEpisodeRequest;
use App\Traits\HandlesImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EpisodeController extends Controller
{
    use HandlesImages;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PodcastEpisode::with(['podcast', 'instructor']);

        // Filter by podcast
        if ($request->has('podcast_id') && $request->podcast_id != '') {
            $query->where('podcast_id', $request->podcast_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by title
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title_ar', 'LIKE', "%{$search}%")
                  ->orWhere('title_en', 'LIKE', "%{$search}%");
            });
        }

        $episodes = $query->orderBy('id', 'DESC')->paginate(10);
        $podcasts = Podcast::all();

        return view('dashboard.episodes.index', [
            'episodes' => $episodes,
            'podcasts' => $podcasts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $podcasts = Podcast::all();
        $instructors = Instructor::all();
        $podcastId = $request->get('podcast_id');

        return view('dashboard.episodes.create', [
            'podcasts' => $podcasts,
            'instructors' => $instructors,
            'podcastId' => $podcastId,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEpisodeRequest $request)
    {
        $data = $request->validated();

        // Handle thumbnail image upload
        if ($request->hasFile('thumbnail_image') && $request->file('thumbnail_image')->isValid()) {
            $data['thumbnail_image'] = $this->uploadImage($request->file('thumbnail_image'), 'episodes');
        }

        // Handle audio file upload
        if ($request->hasFile('audio_file') && $request->file('audio_file')->isValid()) {
            $data['audio_file'] = $request->file('audio_file')->store('episodes/audio', 'public');
        }

        $episode = PodcastEpisode::create($data);

        return redirect()->route('dashboard.podcasts.show', $episode->podcast_id)
            ->with('success', 'Episode created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $episode = PodcastEpisode::with(['podcast', 'instructor'])->findOrFail($id);
        return view('dashboard.episodes.show', compact('episode'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $episode = PodcastEpisode::findOrFail($id);
        $podcasts = Podcast::all();
        $instructors = Instructor::all();

        return view('dashboard.episodes.edit', [
            'episode' => $episode,
            'podcasts' => $podcasts,
            'instructors' => $instructors,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEpisodeRequest $request, string $id)
    {
        $episode = PodcastEpisode::findOrFail($id);
        $data = $request->validated();

        // Handle thumbnail image update
        if ($request->hasFile('thumbnail_image') && $request->file('thumbnail_image')->isValid()) {
            $data['thumbnail_image'] = $this->updateImage($episode->thumbnail_image, $request->file('thumbnail_image'), 'episodes');
        } else {
            $data['thumbnail_image'] = $episode->thumbnail_image;
        }

        // Handle audio file update
        if ($request->hasFile('audio_file') && $request->file('audio_file')->isValid()) {
            // Delete old audio file if exists
            if ($episode->audio_file && Storage::disk('public')->exists($episode->audio_file)) {
                Storage::disk('public')->delete($episode->audio_file);
            }
            $data['audio_file'] = $request->file('audio_file')->store('episodes/audio', 'public');
        } else {
            $data['audio_file'] = $episode->audio_file;
        }

        $episode->update($data);

        return redirect()->route('dashboard.podcasts.show', $episode->podcast_id)
            ->with('success', 'Episode updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $episode = PodcastEpisode::findOrFail($id);
        $podcastId = $episode->podcast_id;

        // Delete thumbnail image if exists
        if ($episode->thumbnail_image) {
            Storage::disk('public')->delete($episode->thumbnail_image);
        }

        // Delete audio file if exists
        if ($episode->audio_file) {
            Storage::disk('public')->delete($episode->audio_file);
        }

        $episode->delete();

        return redirect()->route('dashboard.podcasts.show', $podcastId)
            ->with('success', 'Episode deleted successfully!');
    }
}
