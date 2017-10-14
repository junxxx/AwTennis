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
    private $uniacid = 2;
    private static $moduleName = 'awt_enroll';
    private $logger;

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
        global $_W;
        $_W['uniacid'] = $this->uniacid;
        $site = WeUtility::createModuleSite(self::$moduleName);
        load()->classs('logging');
        $path = AWT_ENROLL_PATH.'/data/LOGS/api/tennis/';
        $logFile = 'tennis';
        $this->logger = new Log($path, $logFile);
    }


    protected function response($data = array())
    {
        $data['status'] = !isset($data['status']) || empty($data['status']) ? 404 : $data['status'];
        $data['data'] = !isset($data['data']) || empty($data['data']) ? array() : $data['data'];
        $data['tips'] = isset($data['tips']) ? $data['tips'] : '';
        $data['message'] = isset(self::$resCodeArr[$data['status']]) ? self::$resCodeArr[$data['status']] : '';
        loadJson($data);
    }

    /**
     * Add member group
     * @return array
     * @author junxxx
     * @date   2017-10-14
     */
    public function actionAdd()
    {
        global $_GPC, $_W;
        if (!$_W['ispost'])
        {
            $response = array(
                'status' => self::INVALID_REQUEST,
                'tips'   => 'post method is required',
            );
            $this->response($response);
        }

        $groupName = isset($_GPC['groupName']) ? trim($_GPC['groupName']) : '';
        $groupName = str_replace(' ', '', $groupName);
        if (empty($groupName))
        {
            $response = array(
                'status' => self::INVALID_REQUEST,
                'tips'   => 'groupName is required',
            );
            $this->response($response);
        }
        $groupNameExist = m('group')->getByName($groupName);
        if ($groupNameExist)
        {
            $response = array(
                'status' => self::INVALID_REQUEST,
                'tips'   => "groupName {$groupName} is exist,change another one",
            );
            $this->response($response);
        }

        $time = time();
        $group = array(
            'uniacid' => $this->uniacid,
            'groupname' => $groupName,
            'ctime' => $time,
            'update_time' => $time,
        );
        $ret = m('group')->insert($group);
        if ($ret)
        {
            $response = array(
                'status' => self::POST_SUCCESS,
                'data'   => $group,
            );
        }
        else
        {
            $this->logger->write("insert groupName failed:" . var_export($ret, true));
            $response = array(
                'tips'   => 'post failed',
            );
        }
        $this->response($response);
    }

    public function actionDel()
    {
        global $_GPC, $_W;
        if (!$_W['ispost'])
        {
            $response = array(
                'status' => self::INVALID_REQUEST,
                'tips'   => 'post method is required',
            );
            $this->response($response);
        }
        $id = isset($_GPC['id']) ? intval($_GPC['id']) : '';
        if (empty($id))
        {
            $response = array(
                'status' => self::INVALID_REQUEST,
                'tips'   => 'id is required',
            );
            $this->response($response);
        }
        $res = m('group')->deleteById($id);
        if ($res)
        {
            $response = array(
                'status' => self::POST_SUCCESS,
            );
        }
        else
        {
            $this->logger->write("delete group failed:" . var_export($res, true));
            $response = array(
                'tips'   => 'post failed',
            );
        }
        $this->response($response);
    }
    public function actionEdit()
    {
        global $_GPC, $_W;
        if (!$_W['ispost'])
        {
            $response = array(
                'status' => self::INVALID_REQUEST,
                'tips'   => 'post method is required',
            );
            $this->response($response);
        }
        $id = isset($_GPC['id']) ? intval($_GPC['id']) : '';
        $groupName = isset($_GPC['groupName']) ? trim($_GPC['groupName']) : '';
        $groupName = str_replace(' ', '', $groupName);
        if (empty($id))
        {
            $response = array(
                'status' => self::INVALID_REQUEST,
                'tips'   => 'id is required',
            );
            $this->response($response);
        }
        if (empty($groupName))
        {
            $response = array(
                'status' => self::INVALID_REQUEST,
                'tips'   => 'groupName is required',
            );
            $this->response($response);
        }
        $groupNameExist = m('group')->getByName($groupName);
        if ($groupNameExist)
        {
            $response = array(
                'status' => self::INVALID_REQUEST,
                'tips'   => "groupName {$groupName} is exist,change another one",
            );
            $this->response($response);
        }
        $data = array(
            'id' => $id,
            'groupname' => $groupName,
        );
        $res = m('group')->edit($data);
        if ($res)
        {
            $response = array(
                'status' => self::POST_SUCCESS,
            );
        }
        else
        {
            $this->logger->write("update group failed:" . var_export($res, true));
            $response = array(
                'tips'   => 'post failed',
            );
        }
        $this->response($response);
    }
    /**
     * Get member group by Id
     * @return array
     * @author junxxx
     * @date   2017-10-14
     */
    public function actionGet()
    {
        global $_GPC;
        $id = isset($_GPC['id']) ? intval($_GPC['id']) : '';
        if (empty($id))
        {
            $response = array(
                'status' => self::INVALID_REQUEST,
                'tips'   => 'id is required',
            );
            $this->response($response);
        }
        $group = m('group')->getById($id);
        $response['status'] = self::GET_SUCCESS;
        $response['data'] = $group;
        $this->response($response);
    }

    /**
     * Get member group list
     * @return array
     * @author junxxx
     * @date   2017-10-14
    */
    public function actionGetAll()
    {
        $group = m('group')->getAll();
        $response['status'] = self::GET_SUCCESS;
        $response['data'] = $group;
        $this->response($response);
    }

}
