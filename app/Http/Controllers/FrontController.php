<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\MateriProgress;
use Illuminate\Support\Facades\DB;
use App\Models\SubscribeTransaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSubscribeTransactionRequest;
use App\Models\Certificate;
use App\Models\CourseVideo;

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
        $courseId = $course;

        if (Auth::user()->hasActiveSubscriptionForCourse($courseId)) {
            return redirect()->route('front.index');
        }
        $course = Course::where('slug', $course)->first();
        return view('front.checkout', compact('course'));
    }

    public function checkout_store(StoreSubscribeTransactionRequest $request, $course)
    {
        $user = Auth::user();
        $courseId = $course;
        if (Auth::user()->hasActiveSubscriptionForCourse($courseId)) {
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

        // 1. cek subscription
        $isSubscribed = SubscribeTransaction::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('is_paid', true)
            ->exists();

        if (! $isSubscribed) {
            return view('front.pricing', compact('course'));
        }

        // 2. ambil semua video terurut
        $videos = $course->course_videos()->orderBy('id')->get();

        // 3. video aktif
        $video = $videos->firstWhere('id', $courseVideoId);
        if (! $video) abort(404);

        // 4. video sebelumnya
        $previousVideo = $videos->where('id', '<', $video->id)->last();

        // 5. cegah loncat materi
        if ($previousVideo) {
            $isCompleted = MateriProgress::where('user_id', $user->id)
                ->where('course_video_id', $previousVideo->id)
                ->where('is_completed', true)
                ->exists();

            if (! $isCompleted) {
                return redirect()->route('front.learning', [
                    $course,
                    'courseVideoId' => $previousVideo->id
                ])->with('error', 'Selesaikan materi sebelumnya terlebih dahulu');
            }
        }

        return view('front.learning', compact('course', 'video', 'videos'));
    }

    public function completeMateri(CourseVideo $video)
    {
        $user = Auth::user();
        MateriProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $video->course_id,
                'course_video_id' => $video->id,
            ],
            [
                'is_completed' => true,
            ]
        );

        // cek apakah course selesai
        $total = $video->course->course_videos()->count();

        $completed = MateriProgress::where('user_id', $user->id)
            ->where('course_id', $video->course_id)
            ->where('is_completed', true)
            ->count();

        if ($total === $completed) {
            Certificate::firstOrCreate([
                'user_id' => $user->id,
                'course_id' => $video->course_id,
            ]);
        }

        return back()->with('success', 'Materi selesai');
    }
}
