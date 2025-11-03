<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CourseController
{
    function courses()
    {
        try {
            $courses = DB::table('courses')->get();

            return response()->json([
                'code' => 200,
                'message' => 'Courses fetched successfully.',
                'data' => $courses
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Internal server error.',
                'data' => $e->getMessage()
            ], 500);
        }
    }

public function courseDetails($slofuncrsi, Request $req)
{
    $courseId = $slofuncrsi;

    try {
        // 1️⃣ Fetch course info
        $course = DB::table('courses')->where('course_id', $courseId)->first();

        if (!$course) {
            return response()->json([
                'code' => 404,
                'message' => 'Course not found.',
                'data' => null
            ], 404);
        }

        // 2️⃣ Get instructor account IDs
        $accountIds = DB::table('instructor_courses')
            ->where('course_id', $courseId)
            ->pluck('account_id')
            ->toArray();

        // 3️⃣ Fetch instructor names from Auth Service (with caching)
        $instructors = [];
        if (!empty($accountIds)) {
            // Create a unique cache key for this combination of instructors
            $cacheKey = 'instructor_names_' . md5(json_encode($accountIds));

            // Try to get cached instructor data
            $instructors = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($accountIds) {
                $response = Http::post('https://auth.transformbd.com/api/account_name', [
                    'account_ids' => $accountIds
                ]);

                if ($response->successful()) {
                    return $response->json('data');
                }

                // Return empty array if API fails
                return [];
            });
        }

        // 4️⃣ Fetch modules ordered by `order` column
        $modules = DB::table('modules')
            ->where('course_id', $courseId)
            ->orderBy('order', 'asc')
            ->get();

        // 5️⃣ Return combined data
        return response()->json([
            'code' => 200,
            'message' => 'Course details fetched successfully.',
            'data' => [
                'course' => $course,
                'instructors' => $instructors,
                'modules' => $modules
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'code' => 500,
            'message' => 'Internal server error.',
            'data' => $e->getMessage()
        ], 500);
    }
}


}
