<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

public function courseDetails(Request $req)
{
    $courseId = $req->input('slofuncrsi');

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

        // 3️⃣ Fetch instructor names from Auth Service (no token)
        $instructors = [];
        if (!empty($accountIds)) {
            $response = Http::post('https://auth.transformbd.com/api/account_name', [
                'account_ids' => $accountIds
            ]);

            if ($response->successful()) {
                $instructors = $response->json('data');
            }
        }

        // 4️⃣ Return combined data
        return response()->json([
            'code' => 200,
            'message' => 'Course details fetched successfully.',
            'data' => [
                'course' => $course,
                'instructors' => $instructors
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
