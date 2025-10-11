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
}
