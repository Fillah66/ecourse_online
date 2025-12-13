<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseVideoRequest;
use App\Models\Course;
use App\Models\CourseVideo;
use Illuminate\Support\Facades\DB;

class CourseVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course)
    {
        //
        return view('admin.course_videos.create', compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseVideoRequest $request, Course $course)
    {

            $image = $request->file('path_video');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/materi'), $imageName);

            CourseVideo::create([
                'course_id' => $course->id,
                'name' => $request->name,
                'path_video' => $imageName,
            ]);

        return redirect()->route('admin.courses.show', $course->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseVideo $courseVideo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource. 
     */
    public function edit(CourseVideo $courseVideo)
    {
        //
        return view('admin.course_videos.edit', compact('courseVideo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCourseVideoRequest $request, CourseVideo $courseVideo)
    {
        //
        DB::transaction(function () use ($request, $courseVideo) {
            
            $validated = $request->validated();
            if($request->hasFile('path_video')) {
                $image = $request->file('path_video');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/materi'), $imageName);
                $validated['path_video'] = $imageName;
            }
            $courseVideo->update($validated);
        });

        return redirect()->route('admin.courses.show', $courseVideo->course_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseVideo $courseVideo)
    {
        //
        DB::beginTransaction();

        try{
            $courseVideo->delete();
            DB::commit();
            
            return redirect()->route('admin.courses.show', $courseVideo->course_id);
        } catch (\Exception $e){
            DB::rollBack();
             return redirect()->route('admin.courses.show', $courseVideo->course_id)->with('error', 'terjadinya sebuah error');
        }
    }
}
