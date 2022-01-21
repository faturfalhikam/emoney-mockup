<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        $products = Config::get('product');

        $invoices = Storage::get('invoice.json');
        if (empty($invoices)) {
            $invoices = [];
        } else {
            $inv = collect(json_decode($invoices, true));
            $reversed = $inv->reverse();

            $reversedAll = [];
            foreach ($reversed->all() as $invoice) {
                $reversedAll[] = $invoice;
            }

            $invoices = [];
            for ($i=0; $i < 7; $i++) {
                if (isset($reversedAll[$i])) {
                    $invoices[$i] = $reversedAll[$i];
                }
            }
        }

        return Inertia::render('Landing', [
            'products' => $products,
            'invoices'  => $invoices
        ]);
    }
}
