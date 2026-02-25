<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentReviewRequest;
use App\Models\Payment;
use App\Services\Payments\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminPaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    public function index(): View
    {
        // Admin only: easiest check via policy
        $this->authorize('viewAny', Payment::class);

        $user = auth()->user();
        if ($user->role !== 'admin') abort(403);

        $payments = Payment::query()
            ->with(['student', 'enrollment.course', 'reviewer'])
            ->latest()
            ->paginate(30);

        return view('admin.payments.index', [
            'payments' => $payments,
            'enums' => [
                'status' => ['pending', 'approved', 'rejected'],
            ],
        ]);
    }

    public function show(Payment $payment): View
    {
        $this->authorize('view', $payment);

        $user = auth()->user();
        if ($user->role !== 'admin') abort(403);

        $payment->load(['student', 'enrollment.course', 'reviewer']);

        return view('admin.payments.show', [
            'payment' => $payment,
            'enums' => [
                'status' => ['pending', 'approved', 'rejected'],
            ],
            'routes' => [
                'review' => route('admin.payments.review', $payment),
            ],
        ]);
    }

    public function review(Payment $payment, PaymentReviewRequest $request): RedirectResponse
    {
        $user = auth()->user();
        if ($user->role !== 'admin') abort(403);

        $this->authorize('review', $payment);

        $data = $request->validated();

        $this->paymentService->review(
            payment: $payment,
            admin: $user,
            status: $data['status'],
            adminNote: $data['admin_note'] ?? null
        );

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with('success', 'پرداخت با موفقیت بررسی شد.');
    }
}