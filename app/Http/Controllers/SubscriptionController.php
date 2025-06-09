<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Helpers\MpesaHelper;

class SubscriptionController extends Controller
{
    public function show()
    {
        return view('subscribe');
    }

    public function process(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')->with('error', 'Please login to subscribe.');
        }

        $plan = $request->input('plan');
        $phone = preg_replace('/^0/', '254', $request->input('phone'));

        if ($plan === 'trial') {
            $user->is_subscribed = true;
            $user->subscription_type = 'trial';
            $user->save();
            return redirect('/dashboard')->with('success', 'Trial activated!');
        }

        $amount = match ($plan) {
            'month_1' => 1,
            'month_2' => 180,
            default => 0,
        };

        $reference = 'NCK-' . $user->id;
        $response = MpesaHelper::stkPush($phone, $amount, $reference);

        if (isset($response['CheckoutRequestID'])) {
            $user->mpesa_checkout_id = $response['CheckoutRequestID'];
            $user->save();
            return view('wait');
        }

        return back()->with('error', 'Unable to initiate payment.');
    }

    public function success()
    {
        return view('dashboard');
    }

    public function handleCallback(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        Log::info('M-Pesa callback received', $data); // ✅ Log for debugging

        $checkoutId = $data['Body']['stkCallback']['CheckoutRequestID'] ?? null;
        $resultCode = $data['Body']['stkCallback']['ResultCode'] ?? null;

        if ($checkoutId && $resultCode === 0) {
            $user = User::where('mpesa_checkout_id', $checkoutId)->first();
            if ($user) {
                $user->is_subscribed = true;
                $user->subscription_type = 'paid';
                $user->save();

                Log::info("User ID {$user->id} subscription updated via callback.");
            } else {
                Log::warning("No user found for CheckoutRequestID: {$checkoutId}");
            }
        } else {
            Log::warning("STK callback received with ResultCode != 0 or missing data.");
        }

        return response()->json(['status' => 'Callback processed'], 200);
    }
}
