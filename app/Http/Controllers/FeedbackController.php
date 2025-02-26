<?php
namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request, $eventId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        Feedback::create([
            'event_id' => $eventId,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Feedback enviado com sucesso!');
    }

    public function show($eventId)
    {
        $event = Event::findOrFail($eventId);
        $feedbacks = Feedback::where('event_id', $eventId)->with('user')->get();

        return view('events.feedbacks', compact('event', 'feedbacks'));
    }
}
