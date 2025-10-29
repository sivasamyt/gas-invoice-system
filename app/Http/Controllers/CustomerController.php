<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Customer::with(['addresses']);
        if ($search = $request->get('search')) {
            $query->where('first_name','like',"%$search%")
            ->orWhere('last_name','like',"%$search%")
            ->orWhere('email_address','like',"%$search%")
            ->orWhere('mobile_number','like',"%$search%");
        }
        return response()->json($query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
        'company_id' => 'required|exists:companies,id',
        'first_name' => 'required|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'email_address' => 'nullable|email',
        'mobile_number' => 'nullable|string|max:20',
        'send_docket_to' => 'required|in:billing,delivery'
        ]);


        $customer = Customer::create($data);


        if ($request->has('addresses')) {
            foreach ($request->addresses as $addr) {
                $addr['customer_id'] = $customer->id;
                Address::create($addr);
            }
        }


        return response()->json($customer->load('addresses'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json($customer->load('addresses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email_address' => 'nullable|email',
            'mobile_number' => 'nullable|string|max:20',
            'send_docket_to' => 'in:billing,delivery'
        ]);
        $customer->update($data);
        return response()->json($customer->load('addresses'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer->delete();
        return response()->json(['message' => 'Customer deleted']);
    }
}
