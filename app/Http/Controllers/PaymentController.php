<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentStoreRequest;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Services\Payments\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Student list of their payments.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Payment::class);

        $user = auth()->user();

        $payments = Payment::query()
            ->where('student_id', $user->id)
            ->with(['enrollment.course'])
            ->latest()
            ->paginate(20);

        return view('payments.index', [
            'payments' => $payments,
            'enums' => [
                'status' => ['pending', 'approved', 'rejected'],
            ],
        ]);
    }

    /**
     * Show upload form (optional) or use enrollment page.
     * If you don't want a separate page, skip this method and use a form inside enrollment/course page.
     */
    public function create(Enrollment $enrollment): View
    {
        $this->authorize('create', [Payment::class, $enrollment]);

        return view('payments.create', [
            'enrollment' => $enrollment->load('course'),
            'routes' => [
                'store' => route('payments.store'),
            ],
        ]);
    }

    /**
     * Student uploads payment receipt.
     */
    public function store(PaymentStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $enrollment = Enrollment::query()->findOrFail((int) $data['enrollment_id']);
        $this->authorize('create', [Payment::class, $enrollment]);

        $payment = $this->paymentService->createForEnrollment(
            enrollment: $enrollment,
            student: auth()->user(),
            amount: (int) $data['amount'],
            screenshot: $request->file('screenshot')
        );

        return redirect()
            ->route('payments.index')
            ->with('success', 'پرداخت با موفقیت ارسال شد و در انتظار بررسی است.');
    }
}