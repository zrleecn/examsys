<?php

namespace app\admin\controller;

use rbacm\RBACTools;
use think\Controller;
use think\Request;
use menu\MenuTools;

class PaperCategory extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {




        // 判断权限
        if (!RBACTools::can('/papercate')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }


        // 左侧菜单
        $menus = MenuTools::get_all_menus() ;
        $pagesize = \think\facade\Config::get('pagesize');

        // 按照状态排序 关闭状态的放在最后
        $list = \app\admin\model\PaperCategory::order('status', 'desc')->order('createdate', 'desc')->paginate($pagesize) ;



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
        if (!RBACTools::can('/papercate/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        //
        $menus = MenuTools::get_all_menus() ;



        $list = [] ;
        return view('category_add',[
            'menus' => $menus,
            'list' => $list,
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

        // 判断权限
        if (!RBACTools::can('/papercate/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        //
        $data = input('post.') ;
        // 数据验证 。。。
        if ($data){
            // 添加id
            $data['id'] = uniqid();
            // 创建时间
            $data['createdate'] =time() ;


            // 保存数据
            $categoryObj = new \app\admin\model\PaperCategory() ;
            $flag =  $categoryObj->allowField(true)->save($data) ;
            if ($flag){
                $this->success('添加成功', url('/papercate'), '', '2' ) ;
                exit();
            }else{
                // 可以记录日志
                $this->error('添加失败', url('/papercate'), '', '2' ) ;
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
        //

        // 判断权限
        if (!RBACTools::can('/papercate/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        $menus = MenuTools::get_all_menus() ;

        $category = \app\admin\model\PaperCategory::find($id) ;


        $list = [] ;
        return view('category_edit',[
            'menus' => $menus,
            'category' => $category,
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
        if (!RBACTools::can('/papercate/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        //
        $data = input('post.') ;
        // 数据验证 。。。
        if ($data){

            // 保存数据
            $categoryObj = new \app\admin\model\PaperCategory() ;
            $flag =  $categoryObj->allowField(true)->save($data, ['id'=>$id]) ;
            if ($flag){
                $this->success('添加成功', url('/papercate'), '', '2' ) ;
                exit();
            }else{
                // 可以记录日志
                $this->error('添加失败', url('/papercate'), '', '2' ) ;
            }

        }
    }

    /**ffffcccccccccc
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //

        // 判断权限
        if (!RBACTools::can('/papercate/delete')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        $paper = \app\admin\model\Paper::where('cid', $id)->limit(1)->select() ;
        if (count($paper) >0){

            $this->error('删除失败,分类下有试卷', url('/papercate'), '', '2' ) ;
        }else{
            $flag = \app\admin\model\PaperCategory::destroy($id) ;
            if ($flag){
                $this->success('删除成功', url('/papercate'), '', '2' ) ;
                exit();
            }else{
                // 可以记录日志
                $this->error('删除失败', url('/papercate'), '', '2' ) ;
//            \think\facade\Log::write('error') ;
            }
        }





    }
}
