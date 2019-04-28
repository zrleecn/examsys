<?php

namespace app\admin\controller;

use menu\MenuTools;
use rbacm\RBACTools;
use think\Controller;
use think\Request;

class News extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //



        // 判断权限
        if (!RBACTools::can('/news')){

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

        $list = \app\admin\model\News::paginate($pagesize) ;


//        $url = \think\facade\Request::baseUrl() ;
//        dump(dirname($_SERVER['SCRIPT_NAME'])) ;
//        dump($url) ;
//        exit() ;

        return view('index',[
            'menus' => $menus,
            'list' => $list,
        ]) ;
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        // 判断权限
        if (!RBACTools::can('/news/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }


        $menus = MenuTools::get_all_menus() ;


        // 类目信息
        $cate = \app\admin\model\NewsCategory::select() ;


        return view('create',[
            'menus' => $menus,
            'cate' => $cate
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
        if (!RBACTools::can('/news/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $data = input("post.") ;

        // 数据验证。。。。略

        if ($data) {
            $data['id'] = uniqid() ;
            $data['status'] = 1 ;
            $data['post_time'] = time() ;
            $data['poster'] = session('user')['user_name'] ;


            $news = new \app\admin\model\News() ;
            $flag = $news->allowField(true)->save($data) ;



            if ($flag){
                $this->success('添加成功', url('/news'), '', '2' ) ;
                exit();
            }else{
                // 可以记录日志
                $this->error('添加失败', url('/news'), '', '2' ) ;
            }
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



        // 对应id的详细信息
        $news = \app\admin\model\News::find($id) ;


        if (!$news){
            return $this->error('no such news', url('/news'), '', '2') ;
        }

        return view('detail', [
            'news' => $news
        ]) ;




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

        // 判断权限
        if (!RBACTools::can('/news/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $menus = MenuTools::get_all_menus() ;

        //
        $cate = \app\admin\model\NewsCategory::select() ;

        $news = \app\admin\model\News::find($id) ;
        if ( !$news){
            $this->error('信息不存在', url('/news'), '', '2' ) ;
            exit();
        }

        return view('edit',[
            'menus' => $menus,
            'cate' =>$cate,
            'news' => $news

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
        //
        // 判断权限
        if (!RBACTools::can('/news/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        $data = input() ;
        if ($data){
            $dept = new \app\admin\model\News() ;
            $flag = $dept->allowField(true)->save($data, ['id'=>$id]);
            if ($flag){
                $this->success('更新成功', url('/news'), '', '2' ) ;
                exit();
            }else{
                // 可以记录日志
                $this->error('更新失败', url('/news'), '', '2' ) ;
            }
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
        //


        // 判断权限
        if (!RBACTools::can('/news/delete')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        $data = \app\admin\model\News::get($id) ;

        if (!$data){
            $this->error('信息不存在', url('/news'), '', '2' ) ;
            exit();
        }

        $flag = $data->delete() ;
        if ($flag){
            $this->success('删除成功', url('/news'), '', '2' ) ;
            exit();
        }else{
            // 可以记录日志
            $this->error('删除失败', url('/news'), '', '2' ) ;
//            \think\facade\Log::write('error') ;
        }
    }
}
