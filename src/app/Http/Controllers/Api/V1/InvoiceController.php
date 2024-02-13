<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\InvoicesFilter;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Requests\V1\BulkStoreInvoiceRequest;
use App\Models\Invoice;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\InvoiceResource;
use App\Http\Resources\V1\InvoiceCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new InvoicesFilter();
        $queryItems = $filter->transform($request); // [['column', 'operator', 'value']]

        if (count($queryItems) == 0) { 
            return new InvoiceCollection(Invoice::paginate());
        } else {
            $invoices = Invoice::where($queryItems)->paginate();
            return new InvoiceCollection($invoices->appends($request->query()));
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        //
    }

    public function bulkStore(BulkStoreInvoiceRequest $request)
    {
        // Wraps the entire request payload into a Laravel collection for easier manipulation.
        // `collect` creates a new collection instance from the request's input data.
        $bulk = collect($request->all())->map(function ($arr, $key) {
            // For each element (represented as `$arr`) in the collection, 
            // `Arr::except` is used to remove specific keys: 'customerId', 'billedDate', 'paidDate'.
            // This operation is performed for every item in the collection,
            // effectively filtering out these keys from each item.
            return Arr::except($arr, ['customerId', 'billedDate', 'paidDate']);
        });
    
        // Inserts the filtered collection of items into the `invoices` table.
        // The `->toArray()` method converts the collection back into a plain PHP array,
        // which is the expected format for the `insert` method on the Invoice model.
        // This operation performs a bulk insert based on the transformed data.
        Invoice::insert($bulk->toArray());
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
