<?php
namespace common\models;

use PDO;
use PDOException;
use Exception;

class Model
{
    public $tb_nm;
    protected $table_column_list;
    public $sql_from;
    public $sql_where;
    public $sql_group;
    public $sql_order;
    public $sql_limit;
    public $exec_parameter;

    public function init()
    {
        $this->sql_group = "";
        $this->sql_where = "";
        $this->sql_order = "";
        $this->sql_limit = "";
        $this->exec_parameter = [];
    }

    public function __construct($tb_nm = "", $pdo = "")
    {
        try {
            $this->tb_nm = $tb_nm;
            $this->sql_from = $this->tb_nm;
            $this->init();

            if (!empty($pdo)) {
                $this->pdo = $pdo;
            } else {
                $db_type = _DBTYPE;
                $db_host = _DBHOST;
                $db_name = _DBNAME;
                $db_user = _DBUSER;
                $db_password = _DBPASSWORD;

                $dsn = "{$db_type}:host={$db_host};dbname={$db_name};charset=utf8";
                $this->pdo = new PDO($dsn, $db_user, $db_password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
                $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $this->pdo->setAttribute(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION
                );
            }
        } catch (Exception $e) {
            debug_log(static::class, __METHOD__, "009", [
                "error" => $e->getMessage(),
            ]);
            return "009";
        }
    }

    private function createTable()
    {
        // 사용 안함 > 테스트 메서드
        $column_list = "";
        foreach ($this->table_column_list as $column) {
            if (!empty($column_list)) {
                $column_list .= " , ";
            }
            $column_list .= " {$column->name} {$column->type} {$column->etc} comment '{$column->comment}' ";
        }

        $sql = " create table if not exists  {$this->tb_nm} ( {$column_list} ) ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $cnt = $stmt->rowCount();
    }
    /*
    private function alterTable()
    {
        $db_name = _DBNAME;
        foreach ($this->table_column_list as $column) {
            $sql = " select count(*) as cnt from information_schema.columns where table_schema = '{$db_name}' and table_name = '{$this->tb_nm}' and column_name='{$column->name}' ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row["cnt"] > 0) {
                echo $column->name;
                $sql = " ALTER TABLE {$this->tb_nm} modify COLUMN {$column->name} {$column->type} comment '{$column->comment}' {$column->etc} ";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "asd";
            } else {
                echo $column->name;
                $sql = " ALTER TABLE {$this->tb_nm} ADD COLUMN {$column->name} {$column->type} comment '{$column->comment}' {$column->etc} ";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch();
            }
        }
    }
    */

    private function existingColumns($value = [])
    {
        $sql =
            "SELECT column_name FROM information_schema.columns WHERE table_schema = '" .
            _DBNAME .
            "' AND table_name = '{$this->tb_nm}' ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($value as $k => $v) {
            if (!in_array($k, $row)) {
                unset($value[$k]);
            }
        }
        return $value;
    }

    //---------------------------
    private function setWhereThen($col, $then, $value)
    {
        try {
            switch ($then) {
                case "ge":
                    $op = ">=";
                    break;
                case "gt":
                    $op = ">";
                    break;
                case "le":
                    $op = "<=";
                    break;
                case "lt":
                    $op = "<";
                    break;
                case "ne":
                    $op = "!=";
                    break;
                default:
                    $op = "=";
            }
            $this->sql_where .= " and {$col} {$op} :exec_{$col}{$then} ";
            $this->exec_parameter["exec_{$col}{$then}"] = $value;
        } catch (Exception $e) {
            throw new Exception("Model setWhereThen Error");
        }
    }

    private function setWhereIn($col, $then, $value)
    {
        try {
            switch ($then) {
                case "all":
                    $op = "in";
                    break;
                default:
                    $op = "not in";
            }
            $dataList = explode(",", $value);
            $dataList = array_filter($dataList);
            $exec_col = "";
            $exec_value = [];
            foreach ($dataList as $key => $data) {
                if (!empty($exec_col)) {
                    $exec_col .= ",";
                }
                $exec_col .= ":exec_{$col}{$key}";
                $exec_value[":exec_{$col}{$key}"] = $data;
            }
            $this->sql_where .= " and {$col} {$op} ( $exec_col ) ";
            $this->exec_parameter = array_merge(
                $this->exec_parameter,
                $exec_value
            );
            $this->sql_order =
                " order by field($col, '" .
                implode("','", $exec_value) .
                "')  ";
        } catch (Exception $e) {
            throw new Exception("Model setWhereIn Error");
        }
    }

    private function setWhereLike($col, $then, $value)
    {
        try {
            switch ($then) {
                case "left":
                    $value = "%{$value}";
                    break;
                case "right":
                    $value = "{$value}%";
                    break;
                default:
                    $value = "%{$value}%";
            }
            $this->sql_where .= " and {$col} like :exec{$col}{$then} ";
            $this->exec_parameter["exec{$col}{$then}"] = $value;
        } catch (Exception $e) {
            throw new Exception("Model setWhereLike");
        }
    }

    private function setWhereNull($col, $then, $value)
    {
        try {
            switch ($then) {
                case "not":
                    $this->sql_where .= " and ( {$col} is not null and {$col} != '' ) ";
                    break;
                default:
                    $this->sql_where .= " and ( {$col} is null or {$col} = '' ) ";
            }
        } catch (Exception $e) {
            throw new Exception("Model setWhereNull");
        }
    }

    private function setWhere($key, $value)
    {
        try {
            if (strpos($key, "__then__")) {
                $arr = explode("__then__", $key);
                $this->setWhereThen($arr[0], $arr[1], $value);
            } elseif (strpos($key, "__in__")) {
                $arr = explode("__in__", $key);
                $this->setWhereIn($arr[0], $arr[1], $value);
            } elseif (strpos($key, "__like__")) {
                $arr = explode("__like__", $key);
                $this->setWhereLike($arr[0], $arr[1], $value);
            } elseif (strpos($key, "__null__")) {
                $arr = explode("__null__", $key);
                $this->setWhereNull($arr[0], $arr[1], $value);
            } else {
                $this->sql_where .= " and {$key} = :exec_{$key} ";
                $this->exec_parameter["exec_{$key}"] = $value;
            }
        } catch (Exception $e) {
            throw new Exception("Model setWhere");
        }
    }

    private function execSelect(
        $col = "*",
        $search = [],
        $fetchAll = false,
        $type = "assoc"
    ) {
        try {
            $fetch = PDO::FETCH_ASSOC;
            if ($type == "group") {
                $fetch = PDO::FETCH_GROUP | PDO::FETCH_ASSOC;
            } elseif ($type == "column") {
                $fetch = PDO::FETCH_COLUMN | PDO::FETCH_GROUP;
            }
            $this->init();

            foreach ($search as $k => $v) {
                if ($k == "col") {
                    $order_col = $v;
                } elseif ($k == "colby") {
                    $order_colby = $v;
                } elseif ($k == "rpp") {
                    $limit_rpp = $v;
                } elseif ($k == "page") {
                    $limit_page = $v;
                } elseif ($k == "group") {
                    $this->sql_group = " group by {$v} ";
                } elseif ($k == "sql") {
                    $this->sql_where .= " and ({$v}) ";
                } else {
                    $this->setWhere($k, $v);
                }
            }
            if (!empty($order_col)) {
                if (empty($order_colby)) {
                    $order_colby = "asc";
                }
                $this->sql_order = " order by {$order_col} {$order_colby} ";
            }

            if (!empty($limit_rpp)) {
                if (empty($limit_page)) {
                    $limit_page = 0;
                }
                $this->sql_limit = " limit {$limit_page},{$limit_rpp} ";
            }
            $sql = "SELECT {$col} FROM {$this->sql_from} where 1 = 1 {$this->sql_where} {$this->sql_group} ";
            if ($fetchAll) {
                $sql .= " {$this->sql_order} {$this->sql_limit}";
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->exec_parameter);
            $row = $fetchAll ? $stmt->fetchAll($fetch) : $stmt->fetch($fetch);
            return $row;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    private function execInsert($value)
    {
        try {
            $value = $this->existingColumns($value);
            if (empty($value)) {
                throw new Exception("Parameter Empty");
            }

            $field = "";
            $values = "";
            foreach ($value as $k => $v) {
                if (!empty($values)) {
                    $field .= " , ";
                    $values .= " , ";
                }
                $field .= $k;
                $values .= ":{$k}";
                $this->exec_parameter[$k] = $v;
            }
            if (
                empty($values) ||
                empty($field) ||
                empty($this->exec_parameter)
            ) {
                throw new Exception("Parameter Empty");
            }

            $sql = "insert into {$this->tb_nm} ({$field}) VALUES ({$values})";
            $stmt = $this->pdo->prepare($sql);
            $row = $stmt->execute($this->exec_parameter);
            $cnt = $stmt->rowCount();
            if ($cnt > 0) {
                $this->addLogData($this->exec_parameter, $sql, "insert");
            }
            return $cnt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    private function execUpdate($value, $search)
    {
        try {
            $value = $this->existingColumns($value);
            if (empty($value)) {
                throw new Exception("Parameter Empty");
            }

            foreach ($search as $k => $v) {
                $this->setWhere($k, $v);
            }
            if (empty($this->exec_parameter)) {
                throw new Exception("Parameter Empty");
            }

            $values = "";
            foreach ($value as $k => $v) {
                if (!empty($values)) {
                    $values .= " , ";
                }
                $values .= "{$k} = :{$k}";
                $this->exec_parameter[$k] = $v;
            }
            if (empty($values) || empty($this->exec_parameter)) {
                throw new Exception("Parameter Empty");
            }
            $sql = "update {$this->tb_nm} SET {$values} where 1=1 {$this->sql_where} ";
            $stmt = $this->pdo->prepare($sql);
            $row = $stmt->execute($this->exec_parameter);
            $cnt = $stmt->rowCount();
            if ($cnt > 0) {
                $this->addLogData($this->exec_parameter, $sql, "update");
            }
            return $cnt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    private function execDelete($search = [])
    {
        try {
            foreach ($search as $k => $v) {
                $this->setWhere($k, $v);
            }
            if (empty($this->exec_parameter)) {
                throw new Exception("Parameter Empty");
            }

            $sql = "delete from {$this->tb_nm} where 1=1 {$this->sql_where} ";
            $stmt = $this->pdo->prepare($sql);
            $row = $stmt->execute($this->exec_parameter);
            $cnt = $stmt->rowCount();
            if ($cnt > 0) {
                $this->addLogData($this->exec_parameter, $sql, "delete");
            }
            return $cnt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    protected function execSql($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    // --------------------------------------------------------------
    protected function addLogData($params = [], $sql = "", $type = "insert")
    {
        try {
            $model = new \common\models\ChangeLogModel();
            $value = [];
            $value["log_type"] = $type;
            $value["log_tb_nm"] = $this->tb_nm;
            $value["log_params_array"] = json_encode($params);
            $value["log_sql"] = $sql;
            $row = $model->add($value);
            return $row;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // --------------------------------------------------------------
    public function execute($sql)
    {
        try {
            $row = $this->execSql($sql, $params);
            return ["returnCode" => "000", "returnData" => $row];
        } catch (Exception $e) {
            return ["returnCode" => "009", "returnData" => $e->getMessage()];
        }
    }

    public function select(
        $col = "*",
        $search = [],
        $fetchAll = false,
        $type = "assoc"
    ) {
        try {
            $row = $this->execSelect($col, $search, $fetchAll, $type);
            if (empty($row) || !is_array($row)) {
                return ["returnCode" => "001", "returnData" => ""];
            }
            return ["returnCode" => "000", "returnData" => $row];
        } catch (Exception $e) {
            return ["returnCode" => "009", "returnData" => $e->getMessage()];
        }
    }

    protected function setValue($value, $type = "add")
    {
        return $value;
    }

    public function insert($value, $check = true)
    {
        try {
            $this->init();
            if ($check) {
                $value = $this->setValue($value, "add");
            }
            $row = $this->execInsert($value);
            if ($row <= 0) {
                return [
                    "returnCode" => "001",
                    "returnData" => "",
                ];
            }
            return [
                "returnCode" => "000",
                "returnData" => [
                    "lastInsertId" => $this->pdo->lastInsertId(),
                ],
            ];
        } catch (Exception $e) {
            return ["returnCode" => "009", "returnData" => $e->getMessage()];
        }
    }

    public function update($value, $search, $check = true)
    {
        try {
            $this->init();
            if ($check) {
                $value = $this->setValue($value, "set");
            }
            $row = $this->execUpdate($value, $search);
            if ($row <= 0) {
                return ["returnCode" => "001", "returnData" => ""];
            }
            return ["returnCode" => "000", "returnData" => ""];
        } catch (Exception $e) {
            return ["returnCode" => "009", "returnData" => $e->getMessage()];
        }
    }

    public function delete($search)
    {
        try {
            $this->init();
            $row = $this->execDelete($search);
            if ($row <= 0) {
                return ["returnCode" => "001", "returnData" => ""];
            }
            return ["returnCode" => "000", "returnData" => ""];
        } catch (Exception $e) {
            return ["returnCode" => "009", "returnData" => $e->getMessage()];
        }
    }
    // ----------------------------------------------
    public function getList($request)
    {
        $search = $request;
        return $this->select("*", $request, true);
    }
    public function get($request)
    {
        $search = $request;
        return $this->select("*", $search, false);
    }
    public function add($value, $check = true)
    {
        return $this->insert($value, $check);
    }
    public function set($value, $search, $check = true)
    {
        return $this->update($value, $search, $check);
    }
    public function remove($search)
    {
        return $this->delete($search);
    }

    //-------------------------------------------------
    public function leftJoin($key1, $table, $key2)
    {
        $this->sql_from = "( select * from {$this->tb_nm} a left join {$table} b on {$key1} = {$key2} ) c  ";
    }

    public function leftMultiJoin($key1, $table, $key2, $kkey1, $ttable, $kkey2)
    {
        $this->sql_from = "( select * from {$this->tb_nm} a left join {$table} b on {$key1} = {$key2} left join {$ttable} c on {$kkey1} = {$kkey2} ) d  ";
    }
    //-------------------------------------------------
    protected function log_print($sql, $exec = [])
    {
        /*
        echo "<script>";
        echo "console.log('★ {$sql}');";
        foreach ($exec as $k => $v) {
            echo "console.log('\tㄴ {$k} => {$v}');";
        }
        echo "</script>";
*/
    }
}

?>
