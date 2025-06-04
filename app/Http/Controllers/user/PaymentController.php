<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Interfaces\PaymentGatewayInterface;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentGatewayInterface $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {

        $this->paymentGateway = $paymentGateway;
    }


    public function paymentProcess(User $user,Request $request)
    {

        return $this->paymentGateway->sendPayment($user,$request);
    }

    public function callBack(Request $request): \Illuminate\Http\RedirectResponse
    {
        $response = $this->paymentGateway->callBack($request);
        if ($response) {

            return redirect()->route('payment.success');
        }
        return redirect()->route('payment.failed');
    }



    public function success()
    {

        return response()->json(['message' => 'Payment Done!']);
    }
    public function failed()
    {

        return response()->json(['message' => 'Payment Failed!']);
    }
}