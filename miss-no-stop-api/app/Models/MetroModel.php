<?php

namespace App\Models;

use Exception;

class MetroModel extends BaseModel
{
    /**
     * 建立模型時建立連線
     * @return void 不回傳值
     */
    function __construct()
    {
        parent::connect();
    }

    /**
     * 取得指定縣市所有路線的查詢類別（未執行 Query）
     * @param string $cityId 縣市代碼
     * @return mixed 查詢類別
     */
    function get_routes($cityId)
    {
        try
        {
            $condition = [
                "MS_city_id" => $cityId
            ];
            return $this->db->table("metro_routes")
                            ->join("metro_route_stations", "MR_id = MRS_route_id")
                            ->join("metro_stations", "MRS_station_id = MS_id")
                            ->select("MR_id, MR_name_TC, MR_name_EN")
                            ->where($condition)
                            ->limit(1)
                            ->orderBy("MR_id");
        }
        catch (Exception $e)
        {
            log_message("critical", $e->getMessage());
            throw $e;
        }
    }

    /**
     * 取得指定縣市及路線上所有車站的查詢類別（未執行 Query）
     * @param string $cityId 縣市代碼
     * @param string $routeId 路線代碼
     * @return mixed 查詢類別
     */
    function get_stations($cityId, $routeId)
    {
        try
        {
            $condition = [
                "MS_city_id"   => $cityId,
                "MRS_route_id" => $routeId
            ];
            return $this->db->table("metro_stations")
                            ->join("metro_route_stations", "MS_id = MRS_station_id")
                            ->select("MS_id, MS_name_TC, MS_name_EN, MS_longitude, MS_latitude")
                            ->where($condition)
                            ->orderBy("MS_id");
        }
        catch (Exception $e)
        {
            log_message("critical", $e->getMessage());
            throw $e;
        }
    }
    /**
     * 取得指定縣市、車站及終點站方向的查詢類別（未執行 Query）
     * @param string $stationId 車站代碼
     * @return mixed 查詢類別
     */
    function get_arrivals($cityId, $stationId, $endStationId)
    {
        try
        {
            $condition = [
                "MS_city_id"        => $cityId,
                "MA_station_id"     => $stationId,
                "MA_end_station_id" => $endStationId
            ];
            return $this->db->table("metro_arrivals")
                            ->join("metro_stations", "MA_station_id = MS_id")
                            ->select("MA_sequence, MA_arrival_time, MA_departure_time")
                            ->where($condition)
                            ->orderBy("MA_sequence");
        }
        catch (Exception $e)
        {
            log_message("critical", $e->getMessage());
            throw $e;
        }
    }
}
