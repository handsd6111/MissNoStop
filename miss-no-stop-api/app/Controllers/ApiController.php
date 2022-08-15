<?php

namespace App\Controllers;

use App\Models\MetroModel;
use Exception;

class ApiController extends BaseController
{
    function __construct()
    {
        // $this->metroModel = new MetroModel();
    }

    /**
     * 通用：取得所有縣市資料
     * @return array 縣市資料陣列
     */
    function get_cities()
    {
        try
        {
            // 如果資料查詢失敗則回傳錯誤訊息
            if (!$query = $this->metroModel->get_cities())
            {
                $data = [
                    "database" => "Failed to query from the database."
                ];
                return $this->send_response($data, 500, "Database error");
            }

            // 查詢成功
            return $this->send_response($query->get());
        }
        catch (Exception $e)
        {
            log_message("critical", $e->getMessage());
            return $this->send_response([], 500, "Exception error");
        }
    }

    /**
     * 捷運：取得指定縣市的所有路線
     * @param string $cityId 縣市代碼
     * @return array 路線資料陣列
     */
    function get_metro_routes($cityId)
    {
        try
        {
            // 設定 GET 資料驗證格式
            $vData = [
                "cityId" => $cityId
            ];
            $vRules = [
                "cityId" => "exact_length[5]"
            ];

            // 如果 GET 資料驗證失敗則回傳錯誤訊息
            if (!$this->validateData($vData, $vRules))
            {
                return $this->send_response((array)$this->validator->getErrors(), 400, "Validation error");
            }

            // 查詢成功
            return $this->send_response($this->metroModel->get_routes($cityId)->get());
        }
        catch (Exception $e)
        {
            log_message("critical", $e->getMessage());
            return $this->send_response([], 500, "Exception error");
        }
    }

    /**
     * 捷運：取得指定縣市及路線的所有車站
     * @param string $cityId 縣市代碼
     * @param string $routeId 路線代碼
     * @return array 車站資料陣列
     */
    function get_metro_stations($cityId, $routeId)
    {
        try
        {
            // 設定 GET 資料驗證格式
            $vData = [
                "cityId"  => $cityId,
                "routeId" => $routeId
            ];
            $vRules = [
                "cityId"  => "exact_length[5]",
                "routeId" => "numeric"
            ];

            // 如果 GET 資料驗證失敗則回傳錯誤訊息
            if (!$this->validateData($vData, $vRules))
            {
                return $this->send_response((array)$this->validator->getErrors(), 400, "Validation error");
            }
            
            // 查詢成功
            return $this->send_response($this->metroModel->get_stations($cityId, $routeId)->get());
        }
        catch (Exception $e)
        {
            log_message("critical", $e->getMessage());
            return $this->send_response([], 500, "Exception error");
        }
    }

    /**
     * 捷運：取得指定縣市、車站及終點車站方向的時刻表
     * @param string $cityId 縣市代碼
     * @param string $stationId 車站代碼
     * @param string $endStationId 終點車站代碼（用於表示運行方向）
     * @return array 時刻表資料陣列
     */
    function get_metro_arrivals($cityId, $stationId, $endStationId)
    {
        try
        {
            // 設定 GET 資料驗證格式
            $vData = [
                "cityId"       => $cityId,
                "stationId"    => $stationId,
                "endStationId" => $endStationId
            ];
            $vRules = [
                "cityId"       => "exact_length[5]",
                "stationId"    => "alpha_numeric_punct",
                "endStationId" => "alpha_numeric_punct"
            ];
            
            // 如果 GET 資料驗證失敗則回傳錯誤訊息
            if (!$this->validateData($vData, $vRules))
            {
                return $this->send_response((array)$this->validator->getErrors(), 400, "Validation error");
            }

            // 查詢成功
            return $this->metroModel->get_arrivals($cityId, $stationId, $endStationId)->get();
        }
        catch (Exception $e)
        {
            log_message("critical", $e->getMessage());
            return $this->send_response([], 500, "Exception error");
        }
    }
}
