<?php

namespace App\Console\Commands;

use App\Product;
use Illuminate\Console\Command;
use \BillbeeDe\BillbeeAPI\Client;

class SyncBillbeeProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:billbee_products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sync all products from Billbee';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client(env('BILLBEE_USERNAME'), env('BILLBEE_PASSWORD'), env('BILLBEE_API_KEY'));

        $totalPages = 1;

        for ($page = 1; $page <= $totalPages; $page++) {
            $response = $client->getProducts($page = $page, $pageSize = 1000);
            $totalPages = $response->paging['TotalPages'];

            foreach ($response->data as $product) {
                $barcode = $product->ean;
                $label_text = '';

                foreach ($product->customFields as $customField) {
                    if ($customField->definitionId == 624 && $customField->value) {
                        $barcode = $customField->value;
                    }
                    if ($customField->definitionId == 625 && $customField->value) {
                        $label_text = $customField->value;
                    }
                }

                Product::updateOrCreate(
                    ['sku'=> $product->sku],
                    [
                        'name' => empty($product->invoiceText) ? $product->title[0]->text : $product->invoiceText[0]->text,
                        'barcode'=> $barcode,
                        'sku'=> $product->sku,
                        'image'=> empty($product->images) ? '' : $product->images[0]->url,
                        'lable_text'=> $label_text,
                        'billbee_id'=> $product->id,
                        'price'=> $product->price,
                        'unit_cost'=> $product->costPrice
                    ]
                );
            }
        }
    }
}
