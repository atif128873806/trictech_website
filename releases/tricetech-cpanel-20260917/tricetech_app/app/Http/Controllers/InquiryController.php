<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInquiryRequest;
use App\Mail\ArchitectureInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class InquiryController extends Controller
{
    public function __invoke(StoreInquiryRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['email', 'interest', 'message']);
        $fingerprint = 'inquiry:'.hash('sha256', mb_strtolower($data['email']).'|'.($data['interest'] ?? '').'|'.$data['message']);

        if (Cache::has($fingerprint)) {
            return to_route('home', ['submitted' => 1])
                ->with('inquiry_success', 'Your architecture request has already been received.');
        }

        try {
            // Every configured mailbox receives its own copy of the notification.
            Mail::to(config('tricetech.inquiry_recipients'))->send(new ArchitectureInquiry($data));
            Cache::put($fingerprint, true, now()->addMinutes(10));
        } catch (Throwable $exception) {
            Log::error('Inquiry delivery failed.', [
                'exception' => $exception::class,
                'request_id' => $request->header('X-Request-ID'),
            ]);

            return to_route('home', ['delivery' => 'failed'], 303)
                ->withInput($request->safe()->only(['email', 'interest', 'message']))
                ->with('inquiry_error', 'Delivery could not complete. Your details are still here; please try again or contact us directly.');
        }

        return to_route('home', ['submitted' => 1], 303)
            ->with('inquiry_success', 'Your architecture request has been submitted.');
    }
}
