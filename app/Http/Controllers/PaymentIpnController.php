<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
class PaymentIpnController
{
    public function handle(Request $request, SslCommerzService $ssl)
    {
        $payment = DB::table('payments')->where('tran_id', $request->tran_id)->first();

        if (!$payment || $payment->status === 'paid') {
            return response('Ignored');
        }

        $validation = $ssl->validate($request->val_id);

        if (
            $validation['status'] !== 'VALID' ||
            $validation['amount'] != $payment->amount
        ) {
            return response('Invalid', 400);
        }

        DB::transaction(function () use ($payment, $validation) {
            $payment->update([
                'status' => 'paid',
                'val_id' => $validation['val_id']
            ]);

            $payment->enrollment->update([
                'paid_at' => now()
            ]);
        });

        return response('OK');
    }
}
