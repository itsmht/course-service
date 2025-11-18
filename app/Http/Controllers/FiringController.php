<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // <-- FIX 4: Import the Str facade

class FiringController
{
    public function create()
    {
        return view('firing'); // Make sure this view name matches your file
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        
        $image_path = null; // <-- FIX 2: Initialize variable as null

        // <-- FIX 1: Check for 'featured_image'
        if ($request->hasFile('featured_image')) 
        {
            $image = $request->file('featured_image');
            
            // <-- FIX 3: Use $request->title for the slug
            $imageName = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            
            $image->move(public_path('course_images'), $imageName);
            $image_path = url("course_images/$imageName");
        }

        try {
            // Insert into courses table
            $course_id = DB::table('courses')->insertGetId([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'featured_video_url' => $request->input('featured_video_url'),
                'featured_image_url' => $image_path, // <-- Now this is safe
                'tagline' => $request->input('tagline'),
                'location' => $request->input('location'),
                'time' => $request->input('time'),
                'capacity' => $request->input('capacity'),
                'type' => $request->input('type'),
                'created_at' => now(),
                'updated_at' => now(),
                'preview_homepage' => $request->preview_homepage,
                'other_information' => $request->other_information,
            ], 'course_id'); // Assuming 'course_id' is your primary key

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
                    // Skip if no instructor was selected
                    if (empty($account_id)) {
                        continue;
                    }
                    DB::table('instructor_courses')->insert([
                        'account_id' => $account_id,
                        'course_id' => $course_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            // Assuming your route is named 'courses.create'
            return redirect()->route('courses.create')->with('success', 'Course created successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            // It's helpful to log the error for debugging
            \Log::error('Course creation failed: ' . $e->getMessage()); 
            return back()->with('error', 'Failed to create course: ' . $e->getMessage());
        }
    }
}