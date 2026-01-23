<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SslCommerzService
{
    public function initiate(array $data)
    {
        return Http::asForm()
            ->post(config('sslcommerz.init_url'), $data)
            ->json();
    }

    public function validate(string $valId)
    {
        return Http::get(config('sslcommerz.validation_url'), [
            'val_id' => $valId,
            'store_id' => config('sslcommerz.store_id'),
            'store_passwd' => config('sslcommerz.store_password'),
            'format' => 'json'
        ])->json();
    }
}
