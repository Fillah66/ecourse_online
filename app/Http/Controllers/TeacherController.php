<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeacherController extends Controller
{


    public function index()
    {
        //
        $teachers = Teacher::orderBy('id', 'desc')->get();

        return view('admin.teachers.index', [
            'teachers' => $teachers
        ]);

    }

    public function create()
    {
        //
        return view('admin.teachers.create');
    }

    public function store(StoreTeacherRequest $request)
    {
        //
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if(!$user){
            return back()->withErrors([
                'email' => 'Data Tidak Ditemukan'
            ]);
        }

        if($user->hasRole('teacher')){
            return back()->withErrors([
                'email' => 'Email Tersebut Sudah Menjadi Mentor'
            ]);
        }

        DB::transaction(function () use ($user, $validated) {
            
            $validated['user_id'] = $user->id;
            $validated['is_active'] = true;

            Teacher::create($validated);

            if ($user->hasRole('student')) {
                $user->removeRole('Student');
            }

            $user->assignRole('teacher');

        });

        return redirect()->route('admin.teachers.index');
    }

    public function destroy(Teacher $teacher)
    {
        //
        try {
            $teacher->delete();

            $user = \App\Models\User::find($teacher->user_id);
            $user->removeRole('teacher');
            $user->assignRole('student');

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'system_error' => ['System error!' . $e->getMessage()],
            ]);
            throw $error;
        }
    }
}
