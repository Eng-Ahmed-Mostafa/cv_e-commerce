<?php

namespace App\Http\Controllers\Api\Admin;

use App\Interface\Api\Coupons\CouponInterface;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coupons\CouponRequest;
use App\Http\Resources\CouponResource;

class CouponController extends Controller
{
    use ApiResponseTrait;

    private CouponInterface $couponRepository;

    public function __construct(CouponInterface $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = $this->couponRepository->getAllPaginated(10);
        return $this->successResponse(CouponResource::collection($coupons), 'Coupons retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponRequest $request)
    {
        $data = $request->validated();

        try {
            $coupon = $this->couponRepository->create($data);
            return $this->successResponse(new CouponResource($coupon), 'Coupon created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create coupon', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $coupon = $this->couponRepository->findCoupon($id);
        if(!$coupon) {
            return $this->errorResponse('Coupon not found', 404);
        }
        return $this->successResponse(new CouponResource($coupon));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CouponRequest $request, string $id)
    {
        $data = $request->validated();

        $coupon = $this->couponRepository->findCoupon($id);

        if(!$coupon) {
            return $this->errorResponse('Coupon not found', 404);
        }

        try {
            $this->couponRepository->update($coupon, $data);
            return $this->successResponse(new CouponResource($coupon), 'Coupon updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update coupon', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = $this->couponRepository->findCoupon($id);

        if(!$coupon) {
            return $this->errorResponse('Coupon not found', 404);
        }

        try {
            $this->couponRepository->delete($coupon);
            return $this->successResponse(null, 'Coupon deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete coupon', 500, ['error' => $e->getMessage()]);
        }
    }

}
