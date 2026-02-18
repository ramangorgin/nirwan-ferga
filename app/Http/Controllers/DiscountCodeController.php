<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiscountCodeStoreRequest;
use App\Http\Requests\DiscountCodeUpdateRequest;
use App\Http\Requests\DiscountCodeValidateRequest;
use App\Models\DiscountCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function __construct()
    {
        // Uses DiscountCodePolicy (your before() grants admin/teacher full access)
        $this->authorizeResource(DiscountCode::class, 'discount_code');
    }

    /**
     * GET /discount-codes
     * List discount codes (admin/teacher only via policy).
     */
    public function index(Request $request): JsonResponse
    {
        $query = DiscountCode::query()->latest();

        if ($request->filled('code')) {
            $query->where('code', strtoupper(trim((string) $request->input('code'))));
        }

        if ($request->filled('active')) {
            $query->where('active', filter_var($request->input('active'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->input('user_id'));
        }

        $perPage = (int) ($request->input('per_page', 15));
        $perPage = max(1, min($perPage, 100));

        return response()->json([
            'data' => $query->paginate($perPage),
        ]);
    }

    /**
     * POST /discount-codes
     * Create a discount code (admin/teacher only via policy).
     */
    public function store(DiscountCodeStoreRequest $request): JsonResponse
    {
        $discountCode = DiscountCode::create($request->validated());

        return response()->json([
            'message' => 'کد تخفیف با موفقیت ایجاد شد',
            'data' => $discountCode,
        ], 201);
    }

    /**
     * GET /discount-codes/{discount_code}
     * Show a discount code (admin/teacher only via policy).
     */
    public function show(DiscountCode $discount_code): JsonResponse
    {
        return response()->json([
            'data' => $discount_code,
        ]);
    }

    /**
     * PATCH/PUT /discount-codes/{discount_code}
     * Update a discount code (admin/teacher only via policy).
     */
    public function update(DiscountCodeUpdateRequest $request, DiscountCode $discount_code): JsonResponse
    {
        $discount_code->update($request->validated());

        return response()->json([
            'message' => 'کد تخفیف با موفقیت به‌روزرسانی شد',
            'data' => $discount_code->fresh(),
        ]);
    }

    /**
     * DELETE /discount-codes/{discount_code}
     * Delete a discount code (admin/teacher only via policy).
     */
    public function destroy(DiscountCode $discount_code): JsonResponse
    {
        $discount_code->delete();

        return response()->json([
            'message' => 'کد تخفیف با موفقیت حذف شد',
        ]);
    }

    /**
     * POST /discount-codes/validate
     *
     * Optional endpoint (useful for internal flows / admin tools).
     * If you truly only validate inside Enrollment, you can delete this method + route.
     *
     * NOTE: This endpoint intentionally does not apply the code to an enrollment.
     * It only validates and returns basic info.
     */
    public function validateCode(DiscountCodeValidateRequest $request): JsonResponse
    {
        // DiscountCodeValidateRequest already checks:
        // - code exists
        // - user authenticated
        // - canBeUsedBy(user)
        $code = strtoupper(trim((string) $request->input('code')));

        $discountCode = DiscountCode::query()
            ->where('code', $code)
            ->firstOrFail();

        return response()->json([
            'message' => 'کد تخفیف معتبر است',
            'data' => [
                'id' => $discountCode->id,
                'code' => $discountCode->code,
                'percentage' => $discountCode->percentage,
                'max_uses' => $discountCode->max_uses,
                'remaining_uses' => $discountCode->remainingUses(),
                'expires_at' => $discountCode->expires_at,
                'active' => $discountCode->active,
                'restricted' => $discountCode->isRestricted(),
            ],
        ]);
    }

    /**
     * Map controller methods to policy abilities when using authorizeResource.
     * (Because route parameter is `discount_code` not `discountCode`.)
     */
    protected function resourceAbilityMap(): array
    {
        return [
            'index' => 'viewAny',
            'show' => 'view',
            'store' => 'create',
            'update' => 'update',
            'destroy' => 'delete',
        ];
    }
}
