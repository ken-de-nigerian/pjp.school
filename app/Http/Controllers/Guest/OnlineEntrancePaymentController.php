<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Entrance;
use App\Models\Setting;
use App\Support\Coercion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use KenDeNigerian\PayZephyr\Exceptions\ChargeException;
use KenDeNigerian\PayZephyr\Exceptions\DriverNotFoundException;
use KenDeNigerian\PayZephyr\Exceptions\InvalidConfigurationException;
use KenDeNigerian\PayZephyr\Exceptions\ProviderException;
use KenDeNigerian\PayZephyr\Facades\Payment;

final class OnlineEntrancePaymentController extends Controller
{
    public function show(Entrance $entrance): View|RedirectResponse
    {
        if ($entrance->payment_status === 1) {
            return redirect()
                ->route('apply_online')
                ->with('error', 'This application and payment have already been verified.');
        }

        $settings = Setting::getCached();

        return view('guest.pages.pay-online', [
            'title' => 'Pay Online',
            'entrance' => $entrance,
            'settings' => $settings,
        ]);
    }

    /**
     * @throws ProviderException
     * @throws ChargeException
     * @throws InvalidConfigurationException
     */
    public function store(Entrance $entrance, Request $request): RedirectResponse
    {
        $v = Coercion::stringKeyedMap($request->validate([
            'email' => 'required|email',
            'amount' => 'required|numeric',
        ]));

        return Payment::amount(Coercion::float($v['amount'] ?? 0))
            ->email(Coercion::string($v['email'] ?? ''))
            ->metadata(['order_id' => $entrance->uniqueID])
            ->callback(route('verify_payment'))
            ->redirect();
    }

    /**
     * @throws ProviderException
     * @throws DriverNotFoundException
     */
    public function verify(Request $request): RedirectResponse
    {
        $verification = Payment::verify(Coercion::string($request->input('reference')));

        if ($verification->isSuccessful()) {
            Entrance::query()->where('uniqueID', $verification->metadata['order_id'])
                ->update(['payment_mode' => 'Online', 'payment_status' => 1]);

            return redirect()
                ->route('apply_online')
                ->with('success', 'Payment verified! Your application has been submitted successfully.');
        }

        return redirect()
            ->route('apply_online')
            ->with('error', 'Payment verification failed. Please try again or contact the administrator if the issue persists.');
    }
}
