<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
class CheckoutController
{
    public function course($slofuncrsi, Request $req)
    {
        $course = DB::table('courses')->where('course_id', $slofuncrsi)->first();
        return response()->json([
            'course_id' => $course->course_id,
            'title' => $course->title,
            'description' => $course->description,
            'price' => (float) $course->price
        ]);
    }
    public function preview(Request $request)
    {
        $course = DB::table('courses')->where('course_id', $request->course_id)->first();
        $price = (float) $course->price;
        $discount = 0;
        $promo = null;

        if ($request->promo_code) {
            $promo = DB::table('promo_codes')
                ->where('code', $request->promo_code)
                ->where('status', 1)
                ->firstOrFail();

            if ($promo->discount_type === 'percentage') {
                $discount = ($price * $promo->discount_value) / 100;
            } else {
                $discount = $promo->discount_value;
            }
        }

        return response()->json([
            'original_price' => $price,
            'discount' => round($discount, 2),
            'final_price' => max($price - $discount, 0),
            'promo_id' => $promo?->promo_id
        ]);
    }
    public function initiate(Request $request, SslCommerzService $ssl)
    {
        $course = DB::table('courses')->where('course_id', $request->course_id)->first();
        $res = Http::post('https://auth.transformbd.com/api/account_name', [
                    'account_ids' => $request->account_id
                ]);
        if ($res->failed()) {
                    return $res->json('data');
                }
        // Recalculate again (never trust frontend)
        $price = (float) $course->price;
        $discount = 0;

        if ($request->promo_id) {
            $promo = PromoCode::findOrFail($request->promo_id);
            $discount = $promo->discount_type === 'percentage'
                ? ($price * $promo->discount_value) / 100
                : $promo->discount_value;
        }

        $finalPrice = max($price - $discount, 0);
        $tranId = 'TRX_' . uniqid();

        $enrollment = Enrollment::create([
            'account_id' => $request->account_id,
            'course_id' => $course->course_id,
            'promo_id' => $request->promo_id,
            'final_price' => $finalPrice
        ]);

        Payment::create([
            'tran_id' => $tranId,
            'enrollment_id' => $enrollment->enrollment_id,
            'amount' => $finalPrice
        ]);

        $response = $ssl->initiate([
            'store_id' => config('sslcommerz.store_id'),
            'store_passwd' => config('sslcommerz.store_password'),
            'total_amount' => $finalPrice,
            'currency' => 'BDT',
            'tran_id' => $tranId,
            'success_url' => url('/payment/success'),
            'fail_url' => url('/payment/fail'),
            'cancel_url' => url('/payment/fail'),
            'ipn_url' => url('/api/payment/ipn'),
            'cus_name' => $res->name,
            'cus_email' => 'no@email.com',
        ]);

        return response()->json([
            'tran_id' => $tranId,
            'gateway_url' => $response['GatewayPageURL']
        ]);
    }
    public function status($tranId)
    {
        return response()->json([
            'status' => DB::table('payments')->where('tran_id', $tranId)->value('status')
        ]);
    }
}
