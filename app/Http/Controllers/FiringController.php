<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class FiringController
{
    public function create()
    {
        return view('firing');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Insert into courses table
            $course_id = DB::table('courses')->insertGetId([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'featured_video_url' => $request->input('featured_video_url'),
                'featured_image_url' => $request->input('featured_image_url'),
                'tagline' => $request->input('tagline'),
                'location' => $request->input('location'),
                'time' => $request->input('time'),
                'capacity' => $request->input('capacity'),
                'type' => $request->input('type'),
                'created_at' => now(),
                'updated_at' => now(),
            ], 'course_id');

            // Insert modules
            if ($request->has('modules')) {
                foreach ($request->modules as $module) {
                    DB::table('modules')->insert([
                        'course_id' => $course_id,
                        'title' => $module['title'],
                        'description' => $module['description'],
                        'module_order' => $module['module_order'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Insert instructors
            if ($request->has('instructors')) {
                foreach ($request->instructors as $account_id) {
                    DB::table('instructor_courses')->insert([
                        'account_id' => $account_id,
                        'course_id' => $course_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('courses.create')->with('success', 'Course created successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create course: ' . $e->getMessage());
        }
    }
}
