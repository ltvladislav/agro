<?php



namespace App\Models\Tables;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TablesGenerator
{
    public static function getCount($config) {
        $sql = static::_getCountQuery($config);
        return DB::select($sql)[0]->count;
    }
    public static function getData($config) {
        $sql = static::_getSqlQuery($config);
        return DB::select($sql);
    }





    public static function _getSqlConditionType($conditionType) {
        $conditionType = (int)$conditionType;

        switch($conditionType) {
            case Enums::ConditionType["Equal"]:
                return "=";
            case Enums::ConditionType["NotEqual"]:
                return "<>";
            case Enums::ConditionType["More"]:
                return ">";
            case Enums::ConditionType["MoreEqual"]:
                return ">=";
            case Enums::ConditionType["Less"]:
                return "<";
            case Enums::ConditionType["LessEqual"]:
                return "<=";
            default:
                return "=";
        }
    }
    public static function _getSqlExpressionType($expressionType) {
        $expressionType = (int)$expressionType;
        return $expressionType == Enums::ExpressionType["AND"] ?
            "AND" : "OR";
    }
    public static function _getSqlOrderDirection($orderDirection) {
        $orderDirection = (int)$orderDirection;
        return $orderDirection == Enums::OrderDirection["DESC"] ?
            "DESC" : "ASC";
    }
    public static function _getSqlColumns($columns) {
        $columnSql = "";
        foreach ($columns as $key => $value) {
            if (!(isset($value["visible"]) && $value["visible"] == "false")) {
                $columnSql .= $key . ", ";
            }
        }
        $columnSql = substr($columnSql, 0, -2);
        return $columnSql;
    }
    public static function _getSqlFilter($filter) {
        $expression = static::_getSqlExpressionType($filter["expressionType"]);
        $sql = "";
        foreach ($filter["collection"] as $key => $value) {
            $sql .= $value["columnName"] . " " . static::_getSqlConditionType($value["conditionType"]) . " " . $value["columnValue"];
            $sql .= " " . $expression . " ";
        }
        $sql = substr($sql, 0, -(strlen($expression) + 2));
        return " where " . (strlen($sql) > 0 ? $sql : "1");
    }
    public static function _getSqlOrders($columns) {
        $orders = [];
        foreach ($columns as $key => $value) {
            if (isset($value["orderDirection"])) {
                array_push($orders, [
                    "orderDirection" => $value["orderDirection"],
                    "orderPosition" => $value["orderPosition"],
                    "column" => $key
                ]);
            }
        }
        if (count($orders) == 0) {
            return "";
        }
        $orders = collect($orders)->sortBy('orderPosition')->all();
        $sql = " ORDER BY ";
        for($i = 0; $i < count($orders); $i++) {
            $sql .= $orders[$i]["column"] . " " . static::_getSqlOrderDirection($orders[$i]["orderDirection"]) . ", ";
        }
        $sql = substr($sql, 0, -2);
        return $sql;
    }
    public static function _getSqlLimit($limit) {
        return " LIMIT " . $limit["startPosition"] . ", " . $limit["rowCount"];
    }

    public static function _getSqlQuery($config) {
        if (isset($config["query"])) {
            return $config["query"];
        }

        if (isset($config["count"]) && $config["count"] == true) {
            return "SELECT COUNT(*) count FROM " . $config["schemaName"];
        }


        $columnSql = isset($config["columns"]) ? static::_getSqlColumns($config["columns"]) : "*";
        $filterSql = isset($config["filter"]) ? static::_getSqlFilter($config["filter"]) : "";
        $orderSql = isset($config["columns"]) ? static::_getSqlOrders($config["columns"]) : "";
        $limitSql = isset($config["startPosition"]) && isset($config["rowCount"]) ? static::_getSqlLimit($config) : "";

        $sql = "SELECT " . $columnSql . " FROM " . $config["schemaName"] . $filterSql . $orderSql . $limitSql;

        return $sql;
    }
    public static function _getCountQuery($config) {
        $filterSql = isset($config["filter"]) ? static::_getSqlFilter($config["filter"]) : "";
        return "SELECT COUNT(*) count FROM " . $config["schemaName"] . $filterSql;
    }


}
