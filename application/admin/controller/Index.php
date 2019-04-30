<?php

namespace app\admin\controller;

use app\admin\model\Menu;
use menu\MenuTools;
use rbacm\RBACTools;
use think\App;
use think\Controller;
use think\Request;


/**
 * Class Index
 * @package app\admin\controller
 *
 * php think make:controller admin/Index
 *
 */

class Index extends Controller
{


    public function __construct(App $app = null)
    {
        parent::__construct($app);
        // 判断权限
        if (!RBACTools::can('/admin/login')){

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





        $user = session('user') ;

        $count = $this->indexCount() ;

        $id = session('user')['id'] ;
        if (!$user){
            $this->error('请先登录', url('/login'), '', '1') ;
        }

        $news = \app\admin\model\News::order('post_time')->select() ;

        $menus = MenuTools::get_all_menus() ;

        return view('index',[
            'menus' => $menus,
            'user' => $user,
            'news' => $news,
            'count' => $count
        ]);

    }

    public function test(){
        $flag = cache('tp5', 'zrlee');
        dump($flag) ;
    }


    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }



    /**
     * 首页数据统计信息
     */
    public function indexCount(){
        if (!cache('indexCount')) {
            $count['student'] = \app\admin\model\User::where('user_type',3)->count() ;

            // 题库数量
            $count['db'] = \app\admin\model\QuestionDb::count() ;
            //题目数量
            $count['question'] = \app\admin\model\Question::count() ;
            // 单选题
            $count['dx'] = \app\admin\model\Question::where('type' ,1)->count() ;
            // 填空题
            $count['tk'] = \app\admin\model\Question::where('type' ,4)->count() ;
            // 判断题
            $count['pd'] = \app\admin\model\Question::where('type' ,3)->count() ;
            // 简答题
            $count['jd'] = \app\admin\model\Question::where('type' ,5)->count() ;
            cache('indexCount', $count) ;
        }

        return cache('indexCount') ;

    }
}
