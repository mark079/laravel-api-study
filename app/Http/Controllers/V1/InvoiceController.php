<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\InvoiceResource;
use App\Models\Invoice;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return InvoiceResource::collection(Invoice::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user_id" => "required|exists:users,id",
            "type" => "required|in:C,D,P",
            "paid" => "required|in:0,1",
            "value" => "required|numeric|between:1,9999.99",
            "payment_date" => "required_if:paid,1|date"
        ]);

        if ($validator->fails()) {
            return $this->error('Invalid Data', 422, $validator->errors());
        }

        $created = Invoice::create($validator->validated());

        if ($created) {
            return $this->success('Registered Data', 200, new InvoiceResource($created));
        }

        return $this->error('Something went wrong', 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
