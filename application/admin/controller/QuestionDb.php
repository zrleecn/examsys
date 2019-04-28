<?php

namespace app\admin\controller;

use app\admin\validate\DbValidater;
use menu\MenuTools;
use rbacm\RBACTools;
use think\Controller;
use think\Log;
use think\Request;


class QuestionDb extends Controller
{
    /**
     * 显示资源列表
        @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {

        if (!RBACTools::can('/db')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        //菜单
        $menus = MenuTools::get_all_menus() ;

        // 获取题库列表
        $list = \app\admin\model\QuestionDb::paginate(5) ;

        return view('db_list',[
            'menus' => $menus,
            'list' => $list,
        ]) ;
    }

    /**
     *  显示创建资源表单页.
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function create()
    {


        if (!RBACTools::can('/db/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $menus = MenuTools::get_all_menus() ;

        return view('db_add',[
            'menus' => $menus,
        ]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request

     */
    public function save(Request $request)
    {
        //


        // 判断权限
        if (!RBACTools::can('/db/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }


//        $token=request()->param("__token__");//这是表单提交获取的token
//        var_dump(request()->session());//直接打印session中的token

        $data = input('post.') ;
        $validate = new DbValidater() ;
        if (!$validate->check($data)){
            // 验证不通过
            dump($validate->getError()) ;
            exit() ;
        }

        // 创建时间
        $data['createdate'] = time() ;
        $data['poster'] = session('user')['user_name'] ;


        $question_db = new \app\admin\model\QuestionDb() ;
        $flag = $question_db->allowField(true)->save($data) ;
        if ($flag){
            $this->success('添加成功', url('/db'), '', '2' ) ;
            exit();
        }else{
            // 可以记录日志
            $this->error('添加出错了', url(''), '', '2' ) ;
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
     *
     * 显示编辑资源表单页
     * @param $id
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit($id)
    {

        // 判断权限
        if (!RBACTools::can('/db/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        //
        $menus = MenuTools::get_all_menus() ;

        // 获取传入id的题库信息
        $db_info = \app\admin\model\QuestionDb::where('id', $id)->find() ;

        return view('db_update',[
            'menus' => $menus,
            'info' => $db_info,
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
        if (!RBACTools::can('/db/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        //
        $data = input() ;

        $validate = new DbValidater() ;
        if (!$validate->check($data)){
            // 验证不通过
            dump($validate->getError()) ;
            exit() ;
        }

        $qdb = new \app\admin\model\QuestionDb() ;
        // 更新
        $flag = $qdb->save([
            'name' => $data['name'],
            'remark'=> $data['remark'],
            'status' => $data['status']
        ], ['id' => $id ]);
        if ($flag){
            $this->success('更新成功', url('/db'), '', '2' ) ;
            exit();
        }else{
            // 可以记录日志
            $this->error('更新失败', url(''), '', '2' ) ;
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
        if (!RBACTools::can('/db/delete')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        // 查询该题库中有没有题目
        $question = \app\admin\model\Question::where('dbid', $id) ;
        if (count($question)>0){

            $this->error('删除失败,原因题库有题目', url('/db'), '', '2' ) ;
            exit() ;
        }
        $flag = \app\admin\model\QuestionDb::destroy($id) ;

        if ($flag){
            $this->success('删除成功', url('/db'), '', '2' ) ;
            exit();
        }else{
            // 可以记录日志
            $this->error('删除失败', url(''), '', '2' ) ;
//            \think\facade\Log::write('error') ;
        }
    }
}
