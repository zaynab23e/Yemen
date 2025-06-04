<?php


namespace App\Services;

use App\Interfaces\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymobPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    /**
     * Create a new class instance.
     */
    protected $api_key;
    protected $integrations_id;

    public function __construct()
    {
        $this->base_url = env("PAYMOB_BASE_URL");
        $this->api_key = env("PAYMOB_API_KEY");
        $this->header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $this->integrations_id = [4952690, 4948887];
    }


    protected function generateToken()
    {
        $response = $this->buildRequest('POST', '/api/auth/tokens', [
            'username' =>'01097292131',
            'password' => 'Zaynabmohamed1$',
            'api_key' => $this->api_key
        ]);
        return $response->getData(true)['data']['token'];
    }

    public function sendPayment($user,Request $request):array
    {
        $order = $user->orders()->latest()->first();
        $this->header['Authorization'] = 'Bearer ' . $this->generateToken();
        
        $data = $request->all();
        
        
        $data['api_source'] = "INVOICE";
        $data['integrations'] = $this->integrations_id;
        $data['amount_cents'] = ($order->final_price * 100);
        $data['shipping_data']['first_name'] = $user->name;
        $data['shipping_data']['email'] = $user->email;


        $response = $this->buildRequest('POST', '/api/ecommerce/orders', $data);
        
        if ($response->getData(true)['success']) {


            return ['success' => true, 'url' => $response->getData(true)['data']['url']];
        }

        return ['success' => false, 'url' => route('payment.failed')];
    }

    public function callBack(Request $request): bool
    {
        $response = $request->all();
        Storage::put('paymob_response.json', json_encode($request->all()));

        if (isset($response['success']) && $response['success'] === 'true') {

            return true;
        }
        return false;

    }


}