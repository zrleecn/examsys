<?php

namespace app\admin\controller;

use menu\MenuTools;
use rbacm\RBACTools;
use think\App;
use think\Config;
use think\Controller;
use think\Request;

/**
 * 试题控制器
 * Class Question
 * @package app\admin\controller
 */
class Question extends Controller
{

    public function __construct(App $app = null)
    {
        parent::__construct($app);
//        if (!RBACTools::can('/question')){
//
//            $user = session('user') ;
//            if ($user['user_type'] == 3) {
//                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
//            }else{
//                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
//            }
//
//
//        }
    }


    /**
     *
     * 显示资源列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {



        // 判断权限
        if (!RBACTools::can('/question')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }


        // 左侧菜单
        $menus = MenuTools::get_all_menus() ;
        // 获取试题列表
        $pagesize = \think\facade\Config::get('pagesize');

        $list = \app\admin\model\Question::where('status',1)->order('createdate','desc')->paginate($pagesize) ;


//        $url = \think\facade\Request::baseUrl() ;
//        dump(dirname($_SERVER['SCRIPT_NAME'])) ;
//        dump($url) ;
//        exit() ;

        return view('question_list',[
            'menus' => $menus,
            'list' => $list,
        ]) ;
    }

    /**
     * 显示创建资源表单页.
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @return \think\Response
     */
    public function create()
    {



        // 判断权限
        if (!RBACTools::can('/question/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $menus = MenuTools::get_all_menus() ;

        // 读取题库
        $qdb = \app\admin\model\QuestionDb::select() ;


        return view('question_add',[
            'menus' => $menus,
            'qdb' => $qdb
        ]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //


        // 判断权限
        if (!RBACTools::can('/question/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }


        $data = input('post.') ;

        $data['createdate'] = time() ;

        $data['data'] = json_encode($data) ;
        $data['id'] = uniqid();
        $data['author'] = session('user')['user_name'] ;

        if ( is_array($data['key'])){
            // 多选题答案
            $data['key'] = implode( ',',$data['key']);

        }

        // 数据验证 先不写了

        // 保存数据
        $question = new \app\admin\model\Question() ;
        $flag = $question->allowField(true)->save($data);

        if ($flag){
            // 试题数量
            $db = \app\admin\model\QuestionDb::where('id', $data['dbid'])->find() ;
            $db->question_count = $db->question_count + 1 ;
            $db->save() ;

            $this->success('添加成功', url('/question'), '', '2' ) ;



            exit();
        }else{
            // 可以记录日志
            $this->error('添加失败', url('/question'), '', '2' ) ;
        }


    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //

        // 判断权限
        if (!RBACTools::can('/question/read')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }


        $question = \app\admin\model\Question::find($id) ;
        $question['db'] = $question->dbinfo ;

        return $question ;
    }

    /**
     * 显示编辑资源表单页.
     *
         @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit($id)
    {

        // 判断权限
        if (!RBACTools::can('/question/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        //
        $menus = MenuTools::get_all_menus() ;


        // 读取题库
        $qdb = \app\admin\model\QuestionDb::select() ;

        // 获取对应id的题目
        $question = \app\admin\model\Question::get($id);


        $items = [0,0,0,0] ;

        // 选项
        if ( isset((json_decode($question['data']) )->item)){

            $items = (json_decode($question['data']) )->item ;
        }

        // 多选题
        if ($question['type'] == 2) {
            $question['key'] = explode(',', $question['key']) ;
        }

        return view('question_edit',[
            'menus' => $menus,
            'qdb' => $qdb,
            'question' => $question,
            'items' =>$items,
            'B' => 'B',
            'C' => 'C',
            'A' => 'A',
            'D' => 'D',
        ]) ;

    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {


        // 判断权限
        if (!RBACTools::can('/question/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        //
        $data = input() ;
        $data['data'] = json_encode($data) ;

        if ( is_array($data['key'])){
            // 多选题答案
            $data['key'] = implode( ',',$data['key']);

        }

        // 表单验证省略了。。。
        $question = new \app\admin\model\Question() ;
        $flag = $question->allowField(true)->save($data, ['id'=>$id]);
        if ($flag){
            $this->success('更新成功', url('/question'), '', '2' ) ;
            exit();
        }else{
            // 可以记录日志
            $this->error('更新失败', url('/question'), '', '2' ) ;
        }

    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {

        // 判断权限
        if (!RBACTools::can('/question/delete')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        //
        // 获取题目信息
        $question = \app\admin\model\Question::get($id) ;
        // 关联查找所在题库信息
        $dbid = $question->dbinfo->id ;
        // 删除题目
        $flag = \app\admin\model\Question::where('id', $id)->update(['status'=> 0 ]) ;

        if ($flag){

            // 题库数量减一
            $db = \app\admin\model\QuestionDb::where('id', $dbid)->find() ;

            $db->question_count = $db->question_count - 1 ;
            $db->save() ;


            $this->success('删除成功', url('/question'), '', '2' ) ;
            exit();
        }else{
            // 可以记录日志
            $this->error('删除失败', url('/question'), '', '2' ) ;
//            \think\facade\Log::write('error') ;
        }
    }
}
