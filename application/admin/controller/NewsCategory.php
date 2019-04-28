<?php

namespace app\admin\controller;

use menu\MenuTools;
use rbacm\RBACTools;
use think\Controller;
use think\Request;

class NewsCategory extends Controller
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
        if (!RBACTools::can('/newscate')){

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

        $list = \app\admin\model\NewsCategory::paginate($pagesize) ;


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
        if (!RBACTools::can('/newscate/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        $menus = MenuTools::get_all_menus() ;
        return view('create',[
            'menus' => $menus,

        ]) ;
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
        if (!RBACTools::can('/newscate/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        $data = input('post.') ;
        if ($data){
            $cate = new \app\admin\model\NewsCategory() ;
            $data['id'] = uniqid() ;
            $data['poster'] = session('user')['user_name'] ;
            $data['createtime'] = time() ;
            $flag = $cate->allowField(true)->save($data) ;

            if ($flag){
                $this->success('添加成功', url('/newscate'), '', '2' ) ;
                exit();
            }else{
                // 可以记录日志
                $this->error('添加失败', url('/newscate'), '', '2' ) ;
            }
        }else{
            $this->error('数据有误', url('/newscate'), '', '2' ) ;
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
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        // 判断权限
        if (!RBACTools::can('/newscate/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $cate = \app\admin\model\NewsCategory::find($id) ;


        $menus = MenuTools::get_all_menus() ;
        return view('edit',[
            'menus' => $menus,
            'cate' =>$cate

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
        if (!RBACTools::can('/newscate/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $data = input() ;
        if ($data){
            $cate = new \app\admin\model\NewsCategory() ;
            $flag = $cate->allowField(true)->save($data, ['id'=>$id]);
            if ($flag){
                $this->success('更新成功', url('/newscate'), '', '2' ) ;
                exit();
            }else{
                // 可以记录日志
                $this->error('更新失败', url('/newscate'), '', '2' ) ;
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
        if (!RBACTools::can('/newscate/delete')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $flag = \app\admin\model\NewsCategory::destroy($id) ;

        if ($flag){
            $this->success('删除成功', url('/newscate'), '', '2' ) ;
            exit();
        }else{
            // 可以记录日志
            $this->error('删除失败', url('/newscate'), '', '2' ) ;
//            \think\facade\Log::write('error') ;
        }
    }
}
