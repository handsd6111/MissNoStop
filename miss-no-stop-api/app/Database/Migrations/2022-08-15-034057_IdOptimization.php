<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class IdOptimization extends Migration
{
    function __construct()
    {
        $this->forge = \Config\Database::forge();
    }

    public function up()
    {
        $this->forge->dropTable("metro_route_stations", true. true);
        $this->forge->dropTable("metro_arrivals", true. true);
        $this->forge->dropTable("metro_durations", true. true);
        $this->forge->dropTable("metro_routes", true. true);
        $this->forge->dropTable("metro_stations", true. true);

        // 捷運車站
        $fields = [
            "MS_id" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ],
            "MS_name_TC" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ],
            "MS_name_EN" => [
                "type"       => "VARCHAR",
                "constraint" => 35
            ],
            "MS_city_id" => [
                "type"       => "VARCHAR",
                "constraint" => 3
            ],
            "MS_longitude" => [
                "type"       => "FLOAT"
            ],
            "MS_latitude" => [
                "type"       => "FLOAT"
            ]
        ];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey("MS_id");
        $this->forge->addForeignKey("MS_city_id", "cities", "C_id", "CASCADE", "CASCADE");
        $this->forge->createTable("metro_stations", true);

        // 捷運路線
        $fields = [
            "MR_id" => [
                "type"       => "VARCHAR",
                "constraint" => 10
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
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey("MR_id");
        $this->forge->createTable("metro_routes", true);

        // 捷運站間運行時間
        $fields = [
            "MD_station_id" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ],
            "MD_end_station_id" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ],
            "MD_duration" => [
                "type"       => "TINYINT"
            ]
        ];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey(["MD_station_id", "MD_end_station_id"]);
        $this->forge->addForeignKey("MD_station_id", "metro_stations", "MS_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("MD_end_station_id", "metro_stations", "MS_id", "CASCADE", "CASCADE");
        $this->forge->createTable("metro_durations", true);

        // 捷運到站時間
        $fields = [
            "MA_station_id" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ],
            "MA_end_station_id" => [
                "type"       => "VARCHAR",
                "constraint" => 10
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
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey(["MA_station_id", "MA_end_station_id", "MA_sequence"]);
        $this->forge->addForeignKey("MA_station_id", "metro_stations", "MS_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("MA_end_station_id", "metro_stations", "MS_id", "CASCADE", "CASCADE");
        $this->forge->createTable("metro_arrivals", true);
        
        // 捷運路線與車站關係
        $fields = [
            "MRS_station_id" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ],
            "MRS_route_id" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ]
        ];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey(["MRS_station_id", "MRS_route_id"]);
        $this->forge->addForeignKey("MRS_station_id", "metro_stations", "MS_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("MRS_route_id", "metro_routes", "MR_id", "CASCADE", "CASCADE");
        $this->forge->createTable("metro_route_stations", true);

        // 公車車站
        $fields = [
            "BS_id" => [
                "type" => "VARCHAR",
                "constraint" => 10
            ]
        ];
        $this->forge->modifyColumn("bus_stations", $fields);

        // 公車路線
        $this->forge->dropTable('bus_routes', true, true);
        $fields = [
            "BR_id" => [
                "type"       => "VARCHAR",
                "constraint" => 10
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
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey("BR_id");
        $this->forge->createTable("bus_routes", true);

        // 公車車站與路線關係
        $this->forge->dropTable('bus_route_stations', true, true);
        $fields = [
            "BRS_station_id" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ],
            "BRS_route_id" => [
                "type"       => "VARCHAR",
                "constraint" => 10
            ],
            "BRS_sequence" => [
                "type"       => "TINYINT"
            ],
            "BRS_direction" => [
                "type"       => "BOOLEAN"
            ]
        ];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey(["BRS_station_id", "BRS_route_id"]);
        $this->forge->addForeignKey("BRS_station_id", "bus_stations", "BS_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("BRS_route_id", "bus_routes", "BR_id", "CASCADE", "CASCADE");
        $this->forge->createTable("bus_route_stations", true);
        
    }

    public function down()
    {
        $this->forge->dropTable("metro_route_stations", true. true);
        $this->forge->dropTable("metro_durations", true. true);
        $this->forge->dropTable("metro_arrivals", true. true);
        $this->forge->dropTable("metro_stations", true. true);
        $this->forge->dropTable("metro_routes", true. true);
        $this->forge->dropTable('bus_route_stations', true, true);
    }
}
