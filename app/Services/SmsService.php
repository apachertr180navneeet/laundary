<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $MSG91_API_KEY;
    protected $MSG91_SENDER_ID;
    protected $MSG91_route;
    protected $MSG91_COUNTRY;

    public function __construct()
    {
        $this->MSG91_API_KEY = config('services.msg91.api_key');
        $this->MSG91_SENDER_ID = config('services.msg91.sender_id');
        $this->MSG91_route = config('services.msg91.route');
        $this->MSG91_COUNTRY = config('services.msg91.country');
    }

    public function sendSms($mobile, $templateId, $variables)
    {
        $authKey = $this->MSG91_API_KEY;
        $senderId = $this->MSG91_SENDER_ID;
        $route = $this->MSG91_route;
        $country = $this->MSG91_COUNTRY;

        $url = "https://api.msg91.com/api/v5/flow/";

        $payload = array(
            'flow_id' => $templateId,
            'sender' => $senderId,
            'recipients' => array(
                array(
                    'mobiles' => $country . $mobile,
                    'name' => $variables['name'],
                    'ordernumber' => $variables['ordernumber']
                )
            )
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "authkey: $authKey",
            "Content-Type: application/json"
        ));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            Log::error('MSG91 SMS cURL error: ' . curl_error($ch));
        } else {
            Log::info('MSG91 SMS Response: ' . $response);
        }

        curl_close($ch);
    }
}