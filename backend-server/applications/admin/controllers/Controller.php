<?php
namespace applications\controllers;

use \Exception;

class Controller extends \common\controllers\ViewController
{
    protected $token;
    public $tab;
    public $request;
    public $row;
    public function __construct($params)
    {
        try {
            if (empty($_SESSION["user_idx"])) {
                $this->jsAccess(
                    "로그인 후 이동하여 주시기 바랍니다.",
                    _URL . "/auth/index"
                );
            }
            unset($_GET["url"]);
            $this->request = [];
            $this->row = [];
            $this->tab = json_decode(
                file_get_contents(_LIBS . "/admin_tab_menu.json"),
                true
            );
            parent::__construct($params);
        } catch (Exception $e) {
            //$this->error_json($e->errorCode, $e->getMessage());
        }
    }

    protected function init()
    {
    }

    public function getSearch($request)
    {
        $search = [];
        if (!empty($request["rpp"])) {
            $search["rpp"] = $request["rpp"];
            if (!empty($request["page"])) {
                $search["page"] = $request["page"];
            }
        }

        if (!empty($request["srch"])) {
            if (!empty($request["kwd"])) {
                $search["{$request["srch"]}__like__all"] = $request["kwd"];
            }
            unset($request["srch"]);
            unset($request["kwd"]);
        }
        if (!empty($request["term"])) {
            if (!empty($request["beg"])) {
                $search["{$request["term"]}__then__ge"] = $request["beg"];
            }
            if (!empty($request["end"])) {
                $search["{$request["term"]}__then__le"] = $request["end"];
            }
            unset($request["term"]);
            unset($request["beg"]);
            unset($request["end"]);
        }
        return $search;
    }
}
?>
