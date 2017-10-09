<?php
/**
 * User: panj
 * Date: 2017/10/9
 * Time: 9:33
 */
define('IN_SYS', true);
require '../framework/bootstrap.inc.php';
$queryString = str_replace('?', '&', $_SERVER['QUERY_STRING']);
parse_str($queryString, $query);
global $_GPC;
if( is_array($query) && isset($query['act'])){
    /*TODO add logger*/
    $action = $query['act'];
    $method = 'action'.ucfirst($action);
    $obj = new AWTennis();
    if (method_exists($obj, $method))
    {
        $obj->$method();
    }
}

function loadJson($array = array(), $type = 1)
{
    if ($type == 1)
    {
        header("Content-Type: application/json; charset=utf-8");
    }
    else
    {
        header("Content-Type: text/html; charset=utf-8");
    }

    echo json_encode($array);
    exit();
}


class AWTennis {
    private $uniacid = '';
    private static $moduleName = 'awt_enroll';

    const GET_SUCCESS = 200;          //服务器成功返回用户请求的数据
    const POST_SUCCESS = 201;          //用户新建或修改数据成功
    const INVALID_REQUEST = 400;          //用户新建或修改数据失败
    const UNAUTHORIZED = 401;          //用户操作未授权
    const NOT_FOUND = 404;          //记录不存在

    protected static $resCodeArr = array(
        self::GET_SUCCESS => 'OK',
        self::POST_SUCCESS => 'CREATED',
        self::INVALID_REQUEST => 'INVALID REQUEST',
        self::UNAUTHORIZED => 'UNAUTHORIZED',
        self::NOT_FOUND => 'NOT FOUND',
    );

    public function __construct()
    {
        $site = WeUtility::createModuleSite(self::$moduleName);
    }


    protected function response($data = array())
    {
        $data['status'] = !isset($data['status']) || empty($data['status']) ? 404 : $data['status'];
        $data['data'] = !isset($data['data']) || empty($data['data']) ? "" : $data['data'];
        $data['tips'] = isset($data['tips']) ? $data['tips'] : '';
        $data['message'] = isset(self::$resCodeArr[$data['status']]) ? self::$resCodeArr[$data['status']] : '';
        loadJson($data);
    }

    public function actionAdd()
    {
        $this->response();
    }
    public function actionDel($id)
    {

    }
    public function actionEdit($id)
    {

    }
    public function actionGet($id)
    {

    }
    public function actionGetAll()
    {

    }

}
