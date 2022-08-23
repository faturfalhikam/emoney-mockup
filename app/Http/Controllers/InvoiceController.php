<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    private $data;
    public function __construct()
    {
        $data = Storage::get('invoice.json');
        if (empty($data)) {
            $data = [];
        } else {
            $data = json_decode($data, true);
        }
        $this->data  = collect($data);
    }

    public function index()
    {
    }

    public function view(string $uid)
    {
    }

    public function check(string $uid)
    {
        $invoice = $this->data->firstWhere('id', $uid);

        if ($invoice['channel'] === 'SHOPEEPAY') {
            return $this->displayShopeepayLanding($invoice);
        }

        if (isset($invoice['payment']) && $invoice['payment']['status'] != 'unpaid') {
            return Inertia::render('Invoice', [
                'invoice' => $invoice
            ]);
        }

        $res = Http::withBasicAuth(Config::get('app.wpapi_key'), Config::get('app.wpsecret_key'))
                    ->withHeaders([
                        'Content-Type'  => 'application/json'
                    ])
                    ->post(Config::get('app.wpendpoint') . '/api/v3/payment/status', [
                        'ref_num' => $invoice['response']['ref_num'],
                    ]);

        if ($res->successful()) {
            $invoiceIndex = $this->data->search(function ($value, $key) use ($uid) {
                return $value['id'] === $uid;
            });

            $body = json_decode($res->body(), true);

            $invoice['payment'] = $body;
            $replaced = $this->data->replace([$invoiceIndex => $invoice]);
            Storage::put('invoice.json', json_encode($replaced->all()));

            return Inertia::render('Invoice', [
                'invoice' => $invoice
            ]);
        }

        dd('error');
    }

    private function displayShopeepayLanding($invoice)
    {
        return Inertia::render('ShopeepayLanding', [
            'invoice' => $invoice
        ]);
    }

    public function shopeepayRedirect()
    {
    }
}
