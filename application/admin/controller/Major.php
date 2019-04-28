<?php

namespace app\admin\controller;

use menu\MenuTools;
use rbacm\RBACTools;
use think\Controller;
use think\Request;

class Major extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {




        // 判断权限
        if (!RBACTools::can('/major')){

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

        $list = \app\admin\model\Major::paginate($pagesize) ;


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
        //
        // 判断权限
        if (!RBACTools::can('/major/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        $menus = MenuTools::get_all_menus() ;


        // 院系信息
        $dept = \app\admin\model\Department::select() ;


        return view('create',[
            'menus' => $menus,
            'dept' => $dept
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

        // 判断权限
        if (!RBACTools::can('/major/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        //
        $data = input("post.") ;

        // 数据验证。。。。略

        if ($data) {
            $data['id'] = uniqid() ;

            $major = new \app\admin\model\Major() ;
            $flag = $major->allowField(true)->save($data) ;



            if ($flag){
                $this->success('添加成功', url('/major'), '', '2' ) ;
                exit();
            }else{
                // 可以记录日志
                $this->error('添加失败', url('/major'), '', '2' ) ;
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
        if (!RBACTools::can('/major/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $menus = MenuTools::get_all_menus() ;

        // 院系信息
        $dept = \app\admin\model\Department::select() ;

        $major = \app\admin\model\Major::find($id) ;
        if ( !$major){
            $this->error('信息不存在', url('/major'), '', '2' ) ;
            exit();
        }

        return view('edit',[
            'menus' => $menus,
            'dept' =>$dept,
            'major' => $major

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
        if (!RBACTools::can('/major/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $data = input() ;
        if ($data){
            $major = new \app\admin\model\Major() ;
            $flag = $major->allowField(true)->save($data, ['id'=>$id]);
            if ($flag){
                $this->success('更新成功', url('/major'), '', '2' ) ;
                exit();
            }else{
                // 可以记录日志
                $this->error('更新失败', url('/major'), '', '2' ) ;
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
        // 判断权限
        if (!RBACTools::can('/major/delete')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        return '和其他的删除都是大同小异 没区别 就不用写了';




    }
}
