<?php

namespace App\Controllers;

use App\Models\TDXAuth;
use CodeIgniter\Controller;
use Config\CURLRequest;
use Exception;
use CodeIgniter\Model;
use \Config\Services as CS;

class TDXDataController extends BaseController
{
    public function getCities()
    {
        $url = "https://api.nlsc.gov.tw/other/ListCounty";

        $client = CS::curlrequest();
        $response = $client->request(
            'GET',
            $url
        );

        $nonCodingXML = $response->getXML(); // response取得的是未編碼前的XML
        $realXML = simplexml_load_string($nonCodingXML)->item0; // 將未編碼的XML轉成正常的XML
        return simplexml_load_string($realXML)->countyItem; //最後將XML轉成物件並回傳
    }

    public function getAuthObject()
    {
        $url = "https://tdx.transportdata.tw/auth/realms/TDXConnect/protocol/openid-connect/token";

        $postData = array(
            "grant_type" => "client_credentials",
            "client_id" => "s20313116-16db10ec-5006-4f44",
            "client_secret" => "2ead03cf-908c-4103-a2df-38afe764314f"
        );

        $client = CS::curlrequest();
        $response = $client->request(
            'POST',
            $url,
            [
                'headers' => [
                    'content-type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => $postData
            ]
        );
        $result = json_decode(json_decode($response->getJSON()));
        TDXAuth::setAuthObject($result);
        return TDXAuth::getAcessToken();
    }

    public function getMetroStation()
    {
        // $accessToken = "";
        $accessToken = $this->getAuthObject();
        // $url = "https://tdx.transportdata.tw/api/basic/v2/Rail/Metro/StationTimeTable/TRTC?format=JSON";
        $url = "https://tdx.transportdata.tw/api/basic/v2/Rail/Metro/StationTimeTable/KRTC?%24top=30&%24format=JSON";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = [
            'accept: application/json',
            'authorization: Bearer ' . $accessToken,
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
}
