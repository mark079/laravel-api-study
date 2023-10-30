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
            "payment_date" => "required_if:paid,1|date_format:Y-m-d H:i:s"
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
    public function update(Request $request, Invoice $invoice)
    {
        $paid = $request->all()['paid'];
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:C,D,P',
            'paid' => 'required|in:1,0',
            'value' => 'required|numeric|between:1,9999.99',
            'payment_date' => $paid ? 'required|date_format:Y-m-d H:i:s' : 'nullable'
        ]);

        if($validator->fails()) {
            return $this->error('Invalid Data', 422, $validator->errors());
        }
        
        $validated = $validator->validated();
        
        if(!$paid) {
            $validated['payment_date'] = NULL;
        }

        $updated = $invoice->update($validated);
        if($updated) {
            return $this->success('Invoice Updated', 200, new InvoiceResource($invoice));
        }
        
        return $this->error('Something went wrong', 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoice = Invoice::find($id);
        if($invoice) {
            $deleted = $invoice->delete();
            if($deleted) {
                return $this->success('Invoice Deleted', 200);
            }
            return $this->error('Something went wrong', 400);
        }
        return $this->error('Invoice Not Found', 404);
    }
}
