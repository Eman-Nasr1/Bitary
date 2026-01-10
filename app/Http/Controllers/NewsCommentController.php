<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NewsComment;
use Illuminate\Http\Request;

class NewsCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = NewsComment::with(['news', 'user']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by news_id
        if ($request->has('news_id') && $request->news_id != '') {
            $query->where('news_id', $request->news_id);
        }

        // Search by comment text or user name
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('comment', 'LIKE', "%{$search}%")
                  ->orWhere('user_name', 'LIKE', "%{$search}%");
            });
        }

        $comments = $query->orderBy('id', 'DESC')->paginate(15);

        return view('dashboard.news-comments.index', [
            'comments' => $comments,
        ]);
    }

    /**
     * Approve a comment
     */
    public function approve($id)
    {
        $comment = NewsComment::findOrFail($id);
        $comment->update(['status' => 'approved']);

        return redirect()->back()
            ->with('success', 'Comment approved successfully!');
    }

    /**
     * Reject a comment
     */
    public function reject($id)
    {
        $comment = NewsComment::findOrFail($id);
        $comment->update(['status' => 'rejected']);

        return redirect()->back()
            ->with('success', 'Comment rejected successfully!');
    }

    /**
     * Hide a comment
     */
    public function hide($id)
    {
        $comment = NewsComment::findOrFail($id);
        $comment->update(['status' => 'hidden']);

        return redirect()->back()
            ->with('success', 'Comment hidden successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $comment = NewsComment::findOrFail($id);
        $comment->delete();

        return redirect()->back()
            ->with('success', 'Comment deleted successfully!');
    }
}
