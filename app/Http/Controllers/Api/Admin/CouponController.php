<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::get();
        return $this->successResponse(CouponResource::collection($coupons), 'Coupons retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|unique:coupons,code',
                'type' => 'required|in:fixed,percent',
                'value' => 'required|numeric|min:0',
                'cart_value' => 'required|numeric|min:0',
                'expiry_date' => 'required|date|after_or_equal:today'
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed',422,$e->errors());
        }
        
    

        $coupon = Coupon::create([
            'code' => $request->code,
            'type' => $request->type,
            'value' => $request->value,
            'cart_value' => $request->cart_value,
            'expiry_date' => $request->expiry_date
        ]);

        return $this->successResponse(new CouponResource($coupon), 'Coupon created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $coupon = Coupon::find($id);
        if(!$coupon) {
            return $this->errorResponse('Coupon not found', 404);
        }
        return $this->successResponse(new CouponResource($coupon));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'code' => 'required|unique:coupons,code,'. $id,
                'type' => 'required|in:fixed,percent',
                'value' => 'required|numeric|min:0',
                'cart_value' => 'required|numeric|min:0',
                'expiry_date' => 'required|date|after_or_equal:today'
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed',422,$e->errors());
        }


        $coupon = Coupon::find($id);

        if(!$coupon) {
            return $this->errorResponse('Coupon not found', 404);
        }

        $coupon->update([
            'code' => $request->code,
            'type' => $request->type,
            'value' => $request->value,
            'cart_value' => $request->cart_value,
            'expiry_date' => $request->expiry_date
        ]);

        return $this->successResponse(new CouponResource($coupon),'Coupon updated success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = Coupon::find($id);

        if(!$coupon) {
            return $this->errorResponse('Coupon not found', 404);
        }

        $coupon->delete();

        return $this->successResponse(null,'Coupon deleted success');
    }
}
