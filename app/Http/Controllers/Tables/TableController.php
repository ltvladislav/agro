<?php

namespace App\Http\Controllers\Tables;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Posts\Category;
use App\Models\Posts\Post;
use Illuminate\View\View;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Facades\Voyager;
use App\Models\Tables\TablesGenerator;

class TableController extends \App\Http\Controllers\Controller
{

    public function getStructure(Request $request) {
        $schemaName = $request["schemaName"];

        if ($schemaName == "tabl1") {

            return response()->json([
                'result' => TableStructures::tabl1(),
                'success' => true
            ]);
        }

        return response()->json([
            'ErrorMessage' => "Invalid table",
            'success' => false
        ]);
    }

    public function getData(Request $request) {

        if (isset($request["count"]) && $request["count"] == true) {
            return response()->json([
                'count' => TablesGenerator::getCount($request->all()),
                'success' => true
            ]);
        }
        return response()->json([
            'collection' => TablesGenerator::getData($request->all()),
            'count' => TablesGenerator::getCount($request->all()),
            'success' => true
        ]);
    }


}
