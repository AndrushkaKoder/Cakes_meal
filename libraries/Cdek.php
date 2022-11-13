<?php

namespace libraries;

class Cdek
{

    private $account;

    private $password;

    private $testMode = false;

    private $authData = [];

    public function __construct($account, $password){

        $this->account = $account;

        $this->password = $password;

        $this->authData = json_decode($this->sendRequest('oauth/token?parameters'), true);

        if(empty($this->authData['access_token'])){

            throw new \Exception($this->authData['error']);

        }

    }

    public function getCities($parameters = []){

        return json_decode($this->sendRequest('location/cities', $parameters), true);

    }

    public function getRegoins($parameters = []){

        return json_decode($this->sendRequest('location/regions', $parameters), true);

    }

    public function getOffices($parameters = []){

        return json_decode($this->sendRequest('deliverypoints', $parameters), true);

    }

    public function getDeliveryPrices($parameters){

        if(!empty($parameters['tariff_code'])){

            $url = 'calculator/tariff';

        }else{

            $url = 'calculator/tarifflist';

        }

        return json_decode($this->sendRequest($url, $parameters, 'POST', 'JSON'), true);

    }

    protected function sendRequest($url, $parameters = [], $type = 'GET', $format = ''){

        $url = preg_replace('/^\s*\/+/', '', $url);

        $url = $this->testMode ? 'https://api.edu.cdek.ru/v2/' . $url : 'https://api.cdek.ru/v2/' . $url;

        $ch = curl_init();

        if(empty($this->authData['token_type'])){

            $headers = [
                'Content-Type: application/x-www-form-urlencoded',
            ];

            $parameters = [
                'grant_type' => 'client_credentials',
                'client_id' => $this->account,
                'client_secret' => $this->password
            ];

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));

        }else{

            $headers = [
                'Authorization: ' . $this->authData['token_type'] . ' ' . $this->authData['access_token']
            ];

            if($type === 'GET'){

                $parameters && $url .= '?' . http_build_query($parameters);

            }else{

                if(!$format){

                    $headers[] = 'Content-Type: application/x-www-form-urlencoded';

                    $parameters = http_build_query($parameters);

                }else{

                    $headers[] = 'Content-Type: application/json';

                    $parameters =  json_encode($parameters);

                }

                curl_setopt($ch, CURLOPT_POST, 1);

                curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);

            }

            //curl_setopt($ch, CURLOPT_HEADER, 1);

        }

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec($ch);

        curl_close($ch);

        return $output;

    }

}