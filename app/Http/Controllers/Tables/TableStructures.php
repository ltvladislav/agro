<?php

namespace App\Http\Controllers\Tables;

use App\Models\Tables\Enums;

class TableStructures
{

    public static function tabl1() {
        return [
            "schemaName" => "tabl1",
            "columns" => [
                "T0010_1" => [
                    "caption" => "Посівна площа/насадж., га",
                    "dataValueType" => Enums::DataValueType["Double"]
                ],
                "T0010_2" => [
                    "caption" => "Вироб. прод, ц",
                    "dataValueType" => Enums::DataValueType["Double"]
                ],
                "T0010_3" => [
                    "caption" => "Вироб. собів, тис. грн",
                    "dataValueType" => Enums::DataValueType["Double"]
                ],
                "T0010_4" => [
                    "caption" => "Реаліз, фіз. масі, ц",
                    "dataValueType" => Enums::DataValueType["Double"]
                ],
                "T0010_5" => [
                    "caption" => "Вироб. собів. реал. прод., тис.грн",
                    "dataValueType" => Enums::DataValueType["Double"]
                ],
                "T0010_6" => [
                    "caption" => "Повна собів, тис.грн",
                    "dataValueType" => Enums::DataValueType["Double"]
                ],
                "T0010_7" => [
                    "caption" => "Чистий дохід, тис.грн",
                    "dataValueType" => Enums::DataValueType["Double"]
                ]
            ]
        ];
    }
}
