<?php
namespace app\index\controller;

use app\admin\model\Examdata;
use app\admin\model\Paper;
use gmars\rbac\Rbac;
use rbacm\RBACTools;
use think\App;
use think\Controller;

class Index extends Controller
{


   public function __construct(App $app = null)
   {
       parent::__construct($app);
       // 判断权限
       if (!RBACTools::can('/index/login')){

           $user = session('user') ;
           if ($user['user_type'] == 3) {
               return $this->error('访问出错了，请联系管理员', url('/'), '', '2' ) ;
           }else{
               return $this->error('访问出错了，请联系管理员', url('/admin'), '', '2' ) ;
           }


       }
   }

    public function index()
    {
        // 获取个人信息
        $user = session('user') ;

        $id = session('user')['id'] ;
        if (!$user){
            $this->error('请先登录', url('/login'), '', '1') ;
        }

        $class_id =  $user['class_id'] ;


        $my_exam = $this->getExamCount($class_id, $id) ;

        $history_count = $this->getHistoryCount($class_id, $id) ;



        return view('index',[
            'user' => $user,
            'id' => $id,
            'exam_count' => isset($my_exam['normal']) ? count($my_exam['normal']) : 0 ,
            'timeout_exam' => isset($my_exam['timeout'])? count( $my_exam['timeout']):0  ,
            'history_count' => $history_count
        ]) ;
    }

    /**
     * 获取当前需要考试的次数
     * @param $class_id
     * @param $id
     * @return int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getExamCount($class_id, $id){
        //查找试卷
        $paper = Paper::where('class_id','like' , '%' . $class_id . '%')->order('start_time_stamp', 'desc')->select();
        $my_exam = [] ;
        $now_timestamp = time() ;
        foreach ($paper as $k=>$v){

            $edata =  Examdata::where('paper_id', $v['id'])->where('user_id',$id)->find() ;

            if ($v['start_time_stamp'] < $now_timestamp){
                // 超过考试时间的考试试卷

                $v['timeout'] = 1 ;
                $my_exam['timeout'][] = $v ;

            }else{
                $v['timeout'] = 0 ;
                if ($edata['status'] != 1){
                    $my_exam['normal'][] = $v ;
                }
            }



        }

        return $my_exam ;
    }

    /**
     * 获取历史考试次数
     * @param $class_id
     * @param $id
     * @return int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getHistoryCount($class_id, $id){
        $paper = Paper::where('class_id','like' , '%' . $class_id . '%')->select();

        $my_exam = [] ;

        foreach ($paper as $k=>$v){

            $edata =  Examdata::where('paper_id', $v['id'])->where('user_id',$id)->find() ;



            if ($edata['status'] == 1){
                $my_exam[] = $v ;
            }
        }


        return count($my_exam) ;
    }



    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function info (){
        return phpinfo() ;
    }


    /**
     * 创建用户
     */
    public function createUser(){
        $rbacObj = new Rbac();
        $data = [
            'user_name' => 'teacher222',
            'status' => 1,
            'password' =>crypt("zrlee.cn", 'exam'),
            'user_type' => 2
            ];
        $flag = $rbacObj->createUser($data) ;
        if ($flag){
            return "注册成功" ;
        }
    }



    public function test(){

        $rbacObj = new Rbac();
        $data = [

            ['name' => '教师编辑',
                'status' => 1,
                'description' => '学生编辑权限',
                'path' => '/teacher/edit',
                'create_time' => time()
            ],
            ['name' => '教师新增',
                'status' => 1,
                'description' => '教师新增权限',
                'path' => '/teacher/create',
                'create_time' => time()
            ],
            ['name' => '教师删除',
                'status' => 1,
                'description' => '教师删除权限',
                'path' => '/teacher/delete',
                'create_time' => time()
            ],
            ['name' => '教师列表',
                'status' => 1,
                'description' => '教师列表权限',
                'path' => '/teacher',
                'create_time' => time()
            ],

        ];

        foreach ($data as $v){
            $rbacObj->createPermission($v);
        }

    }

    public function role(){
        $rbacObj = new Rbac();
        $data = [
            'name' => '教师组',
            'status' => 1,
            'description' => '教师用户组',
            'sort_num' => 10,
            'parent_id' => 0
        ];
        $rbacObj->createRole($data);
    }


    // 用户 用户组
    public function aur(){
        $rbacObj = new Rbac();
        $rbacObj->assignUserRole(3, [3]);
    }


    // 组权限
    public function arp(){
        $rbacObj = new Rbac();
        $rbacObj->assignRolePermission(3, [52, 53,54,55]);
    }

    public function check (){
        RBACTools::can('/db/list');
    }


    public function aaa(){


        cache('menus' , [1,2,3]) ;
        dump(cache('menus')) ;
    }
}
