<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    public function sendPayment(User $user,Request $request);

    public function callBack(Request $request);
}