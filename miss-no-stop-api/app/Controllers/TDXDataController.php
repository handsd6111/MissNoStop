<?php

namespace App\Controllers;

use App\Models\ORM\CityModel;
use App\Models\ORM\MetroRouteModel;
use App\Models\TDXAuth;
use Exception;
use \Config\Services as CS;

class TDXDataController extends TDXBaseController
{


    /**
     * 
     */
    public function getAndSetCities()
    {
        $accessToken = $this->getAccessToken();
        $url = "https://tdx.transportdata.tw/api/basic/v2/Basic/City?%24format=JSON";
        $result = $this->curlGet($url, $accessToken);

        foreach ($result as $value) {

            $saveData = [
                'C_id' => $value->CityCode,
                'C_name_TC' => $value->CityName,
                'C_name_EN' => $value->City
            ];

            $cityModel = new CityModel();
            $cityModel->save($saveData); //orm save data
        }

        return true;
    }

    public function getAndSetMetroRoute($railSystem, $countyCode)
    {
        $accessToken = $this->getAccessToken();
        // $url = "https://tdx.transportdata.tw/api/basic/v2/Rail/Metro/StationTimeTable/TRTC?format=JSON";
        $url = "https://tdx.transportdata.tw/api/basic/v2/Rail/Metro/Line/" . $railSystem . "?%24format=JSON";

        $result = $this->curlGet($url, $accessToken);
        // var_dump($result);
        foreach ($result as $value) {

            $saveData = [
                'MR_id' => $countyCode . '-' . $value->LineNo,
                'MR_name_TC' => isset($value->LineName->Zh_tw) ? $value->LineName->Zh_tw : "",
                'MR_name_EN' => isset($value->LineName->En) ? $value->LineName->En : "",
            ];
            // var_dump($saveData);
            $metroRouteModel = new MetroRouteModel();
            $metroRouteModel->save($saveData); //orm save data
        }
    }


    public function getAndSetMetroStation($railSystem, $countyCode)
    {
        $accessToken = $this->getAccessToken();
        // $url = "https://tdx.transportdata.tw/api/basic/v2/Rail/Metro/StationTimeTable/TRTC?format=JSON";
        $url = "https://tdx.transportdata.tw/api/basic/v2/Rail/Metro/Line/" . $railSystem . "?%24format=JSON";

        $result = $this->curlGet($url, $accessToken);

        foreach ($result as $value) {

            $saveData = [
                'MS_id' => $value->CountyCode,
                'C_name_TC' => $value->CountyName,
                'C_name_EN' => $value->County,
                'MS_city_id' => $countyCode,
            ];

            $cityModel = new CityModel();
            $cityModel->save($saveData); //orm save data
        }
    }
}
