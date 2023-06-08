<?php

namespace App\Http\Controllers\Dana;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index()
    {
        return Inertia::render('Checkout');
    }

    public function check(string $slug)
    {
        $product = Config::get('product.' . $slug);
        return Inertia::render('Product', [
            'product' => $product,
            'slug'      => $slug
        ]);
    }

    public function pay(string $slug)
    {
        $product = Config::get('product.' . $slug);
        return Inertia::render('Pay', [
            'product' => $product,
            'slug' => $slug,
            'error' => ''
        ]);
    }

    public function payDo(Request $request, $slug)
    {
        $product = Config::get('product.' . $slug);
        $phone = $request->phone;

        $res = Http::withBasicAuth(Config::get('app.wpapi_key'), Config::get('app.wpsecret_key'))
                    ->withHeaders([
                        'Content-Type'  => 'application/json'
                    ])
                    ->post(Config::get('app.wpendpoint') . '/api/v3/payment/emoney', [
                        'id' => $phone,
                        'channel' => 'OVO',
                        'amount' => $product['price']
                    ]);

        if ($res->failed()) {
            return Inertia::render('Pay', [
                'product' => $product,
                'slug' => $slug,
                'error' => json_decode($res->body(), true)
            ]);
        }

        if ($res->successful()) {
            $existing = Storage::get('invoice.json');
            if (empty($existing)) {
                $existing = [];
            } else {
                $existing = json_decode($existing, true);
            }

            $body = json_decode($res->body(), true);
            $invoice = [
                'id'        => Str::uuid()->toString(),
                'phone'     => $phone,
                'product'   => $product,
                'response'  => $body
            ];

            $data = array_merge($existing, [$invoice]);
            Storage::put('invoice.json', json_encode($data));

            return redirect()->route('invoice.check', $invoice['id']);
        }

        dd('Unknown error');
    }

    /**
     * Pay using shopeepay
     *
     * @param Request $request
     * @param string $slug
     * @return void
     */
    public function payShopee(Request $request, $slug)
    {
        $product = Config::get('product.' . $slug);
        $phone = '081231231231';
        $uuid = Str::uuid()->toString();

        $res = Http::withBasicAuth(Config::get('app.wpapi_key'), Config::get('app.wpsecret_key'))
                    ->withHeaders([
                        'Content-Type'  => 'application/json',
                    ])
                    ->post(Config::get('app.wpendpoint') . '/api/v3/payment/emoney', [
                        'id'            => $phone,
                        'channel'       => 'SPAY',
                        'amount'        => $product['price'],
                        'callback_url'  =>route('shopeepay.callback'),
                        'redirect_url'  => route('shopeepay.redirect', $uuid)
                    ]);

        if ($res->failed()) {
            return Inertia::render('Pay', [
                'product' => $product,
                'slug' => $slug,
                'error' => json_decode($res->body(), true)
            ]);
        }

        if ($res->successful()) {
            $existing = Storage::get('invoice.json');
            if (empty($existing)) {
                $existing = [];
            } else {
                $existing = json_decode($existing, true);
            }

            $body = json_decode($res->body(), true);
            $invoice = [
                'status'    => 'PENDING',
                'channel'   => 'SHOPEEPAY',
                'id'        => $uuid,
                'phone'     => $phone,
                'product'   => $product,
                'response'  => $body
            ];

            $data = array_merge($existing, [$invoice]);
            Storage::put('invoice.json', json_encode($data));

            return redirect()->route('invoice.check', $invoice['id']);
        }
    }

    /**
     * Pay using speedcash
     *
     * @param Request $request
     * @param string $slug
     * @return void
     */
    public function paySpeedcash(Request $request, $slug)
    {
        $product = Config::get('product.' . $slug);
        $phone = '081231231231';
        $uuid = Str::uuid()->toString();

        $res = Http::withBasicAuth(Config::get('app.wpapi_key'), Config::get('app.wpsecret_key'))
                    ->withHeaders([
                        'Content-Type'  => 'application/json',
                    ])
                    ->post(Config::get('app.wpendpoint') . '/api/v3/payment/emoney', [
                        'id'            => $phone,
                        'channel'       => 'SC',
                        'amount'        => $product['price'],
                        'callback_url'  =>route('shopeepay.callback'),
                        'redirect_url'  => route('shopeepay.redirect', $uuid)
                    ]);
        dd($res->body());

        if ($res->failed()) {
            return Inertia::render('Pay', [
                'product' => $product,
                'slug' => $slug,
                'error' => json_decode($res->body(), true)
            ]);
        }


        if ($res->successful()) {
            $existing = Storage::get('invoice.json');
            if (empty($existing)) {
                $existing = [];
            } else {
                $existing = json_decode($existing, true);
            }

            $body = json_decode($res->body(), true);
            $invoice = [
                'status'    => 'PENDING',
                'channel'   => 'SPEEDCASH',
                'id'        => $uuid,
                'phone'     => $phone,
                'product'   => $product,
                'response'  => $body
            ];

            $data = array_merge($existing, [$invoice]);
            Storage::put('invoice.json', json_encode($data));

            return redirect()->route('invoice.check', $invoice['id']);
        }
    }

    public function payOvo(Request $request, $slug)
    {
    }
}
