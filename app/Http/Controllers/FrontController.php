<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscribeTransactionRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\SubscribeTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    //
    public function index()
    {

        $courses = Course::with(['category', 'teacher', 'students'])->orderByDesc('id')->get();

        return view('front.index', compact('courses'));
    }

    public function details(Course $course)
    {

        return view('front.details', compact('course'));
    }

    public function category(Category $category)
    {
        $courses = $category->courses()->get();
        return view('front.category', compact('category', 'courses'));
    }

    public function pricing()
    {
        if (Auth::user()->hasActiveSubscription()) {
            return redirect()->route('front.index');
        }
        return view('front.pricing');
    }

    public function checkout($course)
    {
        if (Auth::user()->hasActiveSubscription()) {
            return redirect()->route('front.index');
        }
        $course = Course::where('slug', $course)->first();
        return view('front.checkout', compact('course'));
    }

    public function checkout_store(StoreSubscribeTransactionRequest $request, $course)
    {
        $user = Auth::user();

        if (Auth::user()->hasActiveSubscription()) {
            return redirect()->route('front.index');
        }
        $courses = Course::where('slug', $course)->first();

        DB::transaction(function () use ($request, $user, $courses) {

            $validated = $request->validated();

            if ($request->hasFile('proof')) {
                $proofPath = $request->file('proof')->store('proofs', 'public');
                $validated['proof'] = $proofPath;
            }
            $coursess = Course::where('slug', $courses->slug)->first();
            $validated['user_id'] = $user->id;
            $validated['course_id'] = $coursess->id;
            $validated['is_paid'] = false;
            $validated['total_amount'] = $coursess->price;


            $transaction = SubscribeTransaction::create($validated);
        });

        return redirect()->route('dashboard');
    }

    public function learning(Course $course, $courseVideoId)
    {
        $user = Auth::user();
        $video = $course->course_videos->firstWhere('id', $courseVideoId);
        $isSubscribed = SubscribeTransaction::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('is_paid', true) // jika ada status
            ->exists();

        if (! $isSubscribed) {
            return view('front.pricing', compact('course', 'video'));
        }

        return view('front.learning', compact('course', 'video'));
    }
}
