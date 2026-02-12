<?php
/**
 * Copyright 2021 Kumar Puspesh
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright notice, 
 *    this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright notice, 
 *    this list of conditions and the following disclaimer in the documentation 
 *    and/or other materials provided with the distribution.
 * 3. Neither the name of the copyright holder nor the names of its contributors 
 *    may be used to endorse or promote products derived from this software without 
 *    specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND 
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE 
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR 
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES 
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; 
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON 
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT 
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS 
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
 */

class DCreateOrderRequest {
    public $order_ref_id;
    public $amount;
    public $currency = "IDR";
    public $is_payment_link = false;
}

class DCreateOrderResponse {
    public $id;
    public $order_ref_id;
    public $amount;
    public $currency = "IDR";
    public $access_token;
}

class Durianpay {
    private static $instance;
    private $secretKey;
    private $baseUrl = "https://api.durianpay.id/v1";

    private function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @param string $secretKey
     * @return Durianpay
     */
    public static function create($secretKey) {
        if (self::$instance == null) {
            self::$instance = new Durianpay($secretKey);
        }
        return self::$instance;
    }

    public function createOrder(DCreateOrderRequest $request) {

        return $this->request("POST", $this->baseUrl . "/orders", json_encode($request));
    }

	/**
     * protected function requestLog($method, $url, $payload) {
     *   file_put_contents("/dev/stdout", sprintf("%s\t%s ~> %s\n", $method, $url, $payload));
     * }
	*/

    /**
     * @param string $method
     * @param string $url
     * @param string $payload
     * @param array $headers
     */

    private function _request($method, $url, $payload = "", $headers = []) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);

        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload );
        }
        
        if (count($headers) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        // $this->requestLog($method, $url, (string)$payload);
        $output = curl_exec($ch);
        curl_close($ch);
        // $this->requestLog($method, $url, (string)$output);
        return $output;
    }

    protected function request($method, $url, $payload = "", $headers = []) {
        $authorizationValue = base64_encode(sprintf("%s%s", $this->secretKey, ":"));
        $baseHeaders = [
            "Content-Type: application/json",
            sprintf("Authorization: BASIC %s", $authorizationValue),
        ];
        $headers = array_merge($headers, $baseHeaders);
        return $this->_request($method, $url, $payload, $headers);
    }

    
}