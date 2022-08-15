<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAllTables extends Migration
{
    function __construct()
    {
        $this->forge = \Config\Database::forge();
    }

    // 建立資料表並賦予屬性
    function up()
    {
        // 縣市資料表
        $citiesField = [
            "C_id" => [
                "type"       => "VARCHAR",
                "constraint" => 3
            ],
            "C_name_TC" => [
                "type"       => "VARCHAR",
                "constraint" => 3
            ],
            "C_name_EN" => [
                "type"       => "VARCHAR",
                "constraint" => 20
            ]
        ];
        $this->forge->addField($citiesField);
        $this->forge->addPrimaryKey("C_id");
        $this->forge->createTable("cities", true);
        unset($citiesField);

        // 以下是鐵路資料表

        // 鐵路車次
        $railwayTrainsField = [
            "RT_id" => [
                "type"       => "VARCHAR",
                "constraint" => 4
            ],
            "RT_departure_date" => [
                "type"       => "DATETIME"
            ],
            "RT_type" => [
                "type"       => "TINYINT",
                "constraint" => 1
            ]
        ];
        $this->forge->addField($railwayTrainsField);
        $this->forge->addPrimaryKey("RT_id");
        $this->forge->createTable("railway_trains", true);
        unset($railwayTrainsField);

        // 鐵路車站
        $railwayStationsField = [
            "RS_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "RS_name_TC" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ],
            "RS_name_EN" => [
                "type"       => "VARCHAR",
                "constraint" => 35
            ],
            "RS_city_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "RS_longitude" => [
                "type"       => "FLOAT"
            ],
            "RS_latitude" => [
                "type"       => "FLOAT"
            ]
        ];
        $this->forge->addField($railwayStationsField);
        $this->forge->addPrimaryKey("RS_id");
        $this->forge->addForeignKey("RS_city_id", "cities", "C_id", "CASCADE", "CASCADE");
        $this->forge->createTable("railway_stations", true);
        unset($railwayStationsField);

        // 鐵路路線
        $railwayRoutesField = [
            "RR_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "RR_name_TC" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ],
            "RR_name_EN" => [
                "type"       => "VARCHAR",
                "constraint" => 35
            ],
        ];
        $this->forge->addField($railwayRoutesField);
        $this->forge->addPrimaryKey("RR_id");
        $this->forge->createTable("railway_routes", true);
        unset($railwayRoutesField);

        // 鐵路到站時間
        $railwayArrivalsField = [
            "RA_train_id" => [
                "type"       => "VARCHAR",
                "constraint" => 4
            ],
            "RA_station_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "RA_arrival_time" => [
                "type"       => "TIME"
            ]
        ];
        $this->forge->addField($railwayArrivalsField);
        $this->forge->addPrimaryKey(["RA_train_id", "RA_station_id"]);
        $this->forge->addForeignKey("RA_train_id", "railway_trains", "RT_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("RA_station_id", "railway_stations", "RS_id", "CASCADE", "CASCADE");
        $this->forge->createTable("railway_arrivals", true);
        unset($railwayArrivalsField);

        // 鐵路路線與車站關係
        $railwayRouteStations = [
            "RRS_station_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "RRS_route_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ]
        ];
        $this->forge->addField($railwayRouteStations);
        $this->forge->addPrimaryKey(["RRS_station_id", "RRS_route_id"]);
        $this->forge->addForeignKey("RRS_station_id", "railway_stations", "RS_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("RRS_route_id", "railway_routes", "RR_id", "CASCADE", "CASCADE");
        $this->forge->createTable("railway_route_stations", true);
        unset($railwayRouteStations);

        // 以上是鐵路資料表
        // 以下是捷運資料表

        // 捷運車站
        $metroStationsField = [
            "MS_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "MS_city_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "MS_name_TC" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ],
            "MS_name_EN" => [
                "type"       => "VARCHAR",
                "constraint" => 35
            ],
            "MS_longitude" => [
                "type"       => "FLOAT"
            ],
            "MS_latitude" => [
                "type"       => "FLOAT"
            ]
        ];
        $this->forge->addField($metroStationsField);
        $this->forge->addPrimaryKey(["MS_id", "MS_city_id"]);
        $this->forge->addForeignKey("MS_city_id", "cities", "C_id", "CASCADE", "CASCADE");
        $this->forge->createTable("metro_stations", true);
        unset($metroStationsField);

        // 捷運路線
        $metroRoutesField = [
            "MR_id" => [
                "type"       => "VARCHAR",
                "constraint" => 2
            ],
            "MR_city_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "MR_name_TC" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ],
            "MR_name_EN" => [
                "type"       => "VARCHAR",
                "constraint" => 35
            ]
        ];
        $this->forge->addField($metroRoutesField);
        $this->forge->addPrimaryKey("MR_id");
        $this->forge->addForeignKey("MR_city_id", "cities", "C_id", "CASCADE", "CASCADE");
        $this->forge->createTable("metro_routes", true);
        unset($metroRoutesField);

        // 捷運站間運行時間
        $metroDurationsField = [
            "MD_station_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "MD_end_station_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "MD_city_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "MD_duration" => [
                "type"       => "TINYINT"
            ]
        ];
        $this->forge->addField($metroDurationsField);
        $this->forge->addPrimaryKey(["MD_station_id", "MD_end_station_id"]);
        $this->forge->addForeignKey("MD_station_id", "metro_stations", "MS_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("MD_end_station_id", "metro_stations", "MS_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("MD_city_id", "cities", "C_id", "CASCADE", "CASCADE");
        $this->forge->createTable("metro_durations", true);
        unset($metroDurationsField);

        // 捷運到站時間
        $metroArrivalsField = [
            "MA_station_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "MA_end_station_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "MA_city_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "MA_sequence" => [
                "type"       => "TINYINT"
            ],
            "MA_arrival_time" => [
                "type"       => "TIME" 
            ],
            "MA_departure_time" => [
                "type"       => "TIME" 
            ]
        ];
        $this->forge->addField($metroArrivalsField);
        $this->forge->addPrimaryKey(["MA_station_id", "MA_end_station_id"]);
        $this->forge->addForeignKey("MA_station_id", "metro_stations", "MS_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("MA_end_station_id", "metro_stations", "MS_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("MA_city_id", "cities", "C_id", "CASCADE", "CASCADE");
        $this->forge->createTable("metro_arrivals", true);
        unset($metroArrivalsField);
        
        // 捷運路線與車站關係
        $metroRouteStationsField = [
            "MRS_station_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "MRS_route_id" => [
                "type"       => "VARCHAR",
                "constraint" => 2
            ],
            "MRS_city_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ]
        ];
        $this->forge->addField($metroRouteStationsField);
        $this->forge->addPrimaryKey(["MRS_station_id", "MRS_route_id"]);
        $this->forge->addForeignKey("MRS_station_id", "metro_stations", "MS_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("MRS_route_id", "metro_routes", "MR_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("MRS_city_id", "cities", "C_id", "CASCADE", "CASCADE");
        $this->forge->createTable("metro_route_stations", true);
        unset($metroRouteStationsField);

        // 以上是捷運公車資料表
        // 以下是公車資料表

        // 公車車站
        $busStationsField = [
            "BS_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "BS_city_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "BS_name_TC" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ],
            "BS_name_EN" => [
                "type"       => "VARCHAR",
                "constraint" => 35
            ],
            "BS_city_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "BS_longitude" => [
                "type"       => "FLOAT"
            ],
            "BS_latitude" => [
                "type"       => "FLOAT"
            ]
        ];
        $this->forge->addField($busStationsField);
        $this->forge->addPrimaryKey("BS_id");
        $this->forge->addForeignKey("BS_city_id", "cities", "C_id", "CASCADE", "CASCADE");
        $this->forge->createTable("bus_stations", true);
        unset($busStationsField);

        // 公車路線
        $busRoutesField = [
            "BR_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "BR_city_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "BR_name_TC" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ],
            "BR_name_EN" => [
                "type"       => "VARCHAR",
                "constraint" => 35
            ]
        ];
        $this->forge->addField($busRoutesField);
        $this->forge->addPrimaryKey("BR_id");
        $this->forge->addForeignKey("BR_city_id", "cities", "C_id", "CASCADE", "CASCADE");
        $this->forge->createTable("bus_routes", true);
        unset($busRoutesField);

        // 公車路線與車站關係
        $busRouteStationsField = [
            "BRS_station_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "BRS_route_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "BRS_city_id" => [
                "type"       => "VARCHAR",
                "constraint" => 5
            ],
            "BRS_sequence" => [
                "type"       => "TINYINT"
            ],
            "BRS_direction" => [
                "type"       => "BOOLEAN"
            ]
        ];
        $this->forge->addField($busRouteStationsField);
        $this->forge->addPrimaryKey(["BRS_station_id", "BRS_route_id"]);
        $this->forge->addForeignKey("BRS_station_id", "bus_stations", "BS_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("BRS_route_id", "bus_routes", "BR_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("BRS_city_id", "cities", "C_id", "CASCADE", "CASCADE");
        $this->forge->createTable("bus_route_stations", true);
        unset($busRouteStationsField);

        // 以上是公車資料表

        // 資料表版本
        $tableVersionsField = [
            "table_name" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ],
            "status" => [
                "type"       => "TINYINT",
                "constraint" => 1
            ],
            "update_time" => [
                "type"       => "DATETIME"
            ],
            "version" => [
                "type"       => "INT"
            ]
        ];
        $this->forge->addField($tableVersionsField);
        $this->forge->addPrimaryKey("table_name");
        $this->forge->createTable("table_versions", true);
        unset($tableVersionsField);
    }

    // 刪除所有資料表
    function down()
    {
        // $this->forge->dropForeignKey("railway_stations", "RS_city_id");
        // $this->forge->dropForeignKey("railway_arrivals", "RA_train_id");
        // $this->forge->dropForeignKey("railway_arrivals", "RA_station_id");
        // $this->forge->dropForeignKey("railway_route_stations", "RRS_station_id");
        // $this->forge->dropForeignKey("railway_route_stations", "RRS_route_id");
        // $this->forge->dropForeignKey("metro_durations", "MD_station_id");
        // $this->forge->dropForeignKey("metro_durations", "MD_end_station_id");
        // $this->forge->dropForeignKey("metro_arrivals", "MA_station_id");
        // $this->forge->dropForeignKey("metro_arrivals", "MA_end_station_id");
        // $this->forge->dropForeignKey("metro_route_stations", "MRS_station_id");
        // $this->forge->dropForeignKey("metro_route_stations", "MRS_route_id");
        // $this->forge->dropForeignKey("bus_route_stations", "BRS_station_id");
        // $this->forge->dropForeignKey("bus_route_stations", "BRS_route_id");

        $this->forge->dropTable('railway_trains', true, true);
        $this->forge->dropTable('railway_arrivals', true, true);
        $this->forge->dropTable('railway_stations', true, true);
        $this->forge->dropTable('railway_route_stations', true, true);
        $this->forge->dropTable('railway_routes', true, true);
        $this->forge->dropTable('metro_stations', true, true);
        $this->forge->dropTable('metro_durations', true, true);
        $this->forge->dropTable('metro_arrivals', true, true);
        $this->forge->dropTable('metro_route_stations', true, true);
        $this->forge->dropTable('metro_routes', true, true);
        $this->forge->dropTable('bus_stations', true, true);
        $this->forge->dropTable('bus_route_stations', true, true);
        $this->forge->dropTable('bus_routes', true, true);
        $this->forge->dropTable('cities', true, true);
        $this->forge->dropTable('table_versions', true, true);
    }
}
