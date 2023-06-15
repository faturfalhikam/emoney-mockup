<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
            Storage::put('invoice.json', json_encode($replaced->all(), true));

            return Inertia::render('Invoice', [
                'invoice' => $invoice
            ]);
        }

        dd('error');
    }

    public function delete(string $uid)
    {
        $invoice = $this->data->firstWhere('id', $uid);
        $key = $this->data->search(function ($value, $key) use ($uid) {
            return $value['id'] === $uid;
        });

        if (empty($invoice)) {
            return 'INVALID REF uid';
        }

        $skipped = $this->data->forget($key);
        $invoice['status'] = 'CANCEL';
        $merged = array_merge($skipped->all(), [$invoice]);

        Storage::put('invoice.json', json_encode($merged, true));
        return redirect()->route('shopeepay.redirect', $uid);
    }

    private function displayShopeepayLanding($invoice)
    {
        if ($invoice['status'] === 'CANCEL') {
            return redirect()->route('shopeepay.redirect', $invoice['id']);
        }

        if ($invoice['status'] === 'EXPIRED') {
            return redirect()->route('shopeepay.redirect', $invoice['id']);
        }

        return Inertia::render('ShopeepayLanding', [
            'invoice' => $invoice
        ]);
    }

    /**
     * Shopeepay redirect
     *
     * @param string $uid
     * @return void
     */
    public function shopeepayRedirect(string $uid)
    {
        $invoice = $this->data->firstWhere('id', $uid);

        return Inertia::render('ShopeepayLandingInvoice', [
            'invoice' => $invoice
        ]);
    }

    /**
     * Find invoice key
     *
     * @param string $key
     * @return void
     */
    private function findKeyByRefNum($key)
    {
        foreach ($this->data->toArray() as $k => $v) {
            if ($v['response']['ref_num'] === $key) {
                return $k;
            }
        }
        return null;
    }

    /**
     * handle apiv3 callback
     *
     * @param Request $request
     * @return void
     */
    public function shopeepayCallback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ref_num'       => 'required',
            'payment_ref'   => 'required',
            'channel'       => ['required', 'in:SPAY,OVO,SC,SPEEDCASH'],
            'amount'        => ['required', 'numeric'],
            'admin'         => ['required', 'numeric'],
            'admin_payee'   => ['required', 'in:merchant,customer'],
            'nett_amount'   => ['required', 'numeric'],
            'status'        => ['required', 'in:paid'],
        ]);

        if ($validator->fails()) {
            return 'INVALID PAYLOAD';
        }

        $data = $validator->validated();
        $invoice = $this->data->firstWhere('response.ref_num', $data['ref_num']);
        $key = $this->findKeyByRefNum($data['ref_num']);

        if (empty($invoice)) {
            return 'INVALID REF NUMBER';
        }

        if (
            $data['status'] === 'paid' &&
            intval($invoice['response']['amount']) === intval($data['amount'])
        ) {
            $skipped = $this->data->forget($key);
            $invoice['status'] = 'PAID';
            $merged = array_merge($skipped->all(), [$invoice]);
            Storage::put('invoice.json', json_encode($merged, true));
            return 'ACCEPTED';
        }

        return 'ERROR';
    }

    public function shopeepayStatus(string $uid)
    {
        $invoice = $this->data->firstWhere('id', $uid);

        if ($invoice['channel'] !== 'SHOPEEPAY') {
            return redirect()->back();
        }

        Http::withBasicAuth(Config::get('app.wpapi_key'), Config::get('app.wpsecret_key'))
                    ->withHeaders([
                        'Content-Type'  => 'application/json'
                    ])
                    ->post(Config::get('app.wpendpoint') . '/api/v3/payment/status', [
                        'ref_num' => $invoice['response']['ref_num'],
                        'payment_start_date' => Carbon::now('Asia/Jakarta')->subDay()->format('Ymd'),
                        'payment_end_date' => Carbon::now('Asia/Jakarta')->format('Ymd')
                    ]);


        return $this->displayShopeepayLanding($invoice);

    }
}
