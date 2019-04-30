<?php

namespace app\admin\controller;

use app\admin\model\Examdata;
use menu\MenuTools;
use rbacm\RBACTools;
use think\Controller;
use think\Request;

class Paper extends Controller
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
        if (!RBACTools::can('/paper')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $menus = MenuTools::get_all_menus() ;
        $pagesize = \think\facade\Config::get('pagesize');
        $list = \app\admin\model\Paper::paginate($pagesize) ;

        // 班级信息
        if ($list){

            foreach ($list as $k=>$each){
                $classname = '' ;
                // 查询班级名称
                $classes = \app\admin\model\Classz::field('name')->all(explode(',', $each['class_id'])) ;

                $len = count($classes) ;

                for ($i=0; $i<$len; $i++){
                    if ($i != $len -1 ){
                        $classname .= $classes[$i]['name'] . ',';
                    }else{
                        $classname .=  $classes[$i]['name']  ;
                    }
                }
                // 班级名称添加到list中
                $list[$k]['classname'] = $classname ;

            }


        }

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
        if (!RBACTools::can('/paper/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        //
        //菜单
        $menus = MenuTools::get_all_menus() ;

        // 获取试卷分类
        $paper_cat = \app\admin\model\PaperCategory::where('status' , 1)->select();

        // 班级信息
        $classz = \app\admin\model\Classz::select() ;


        return view('create',[
            'menus' => $menus,
            'paper_cat' => $paper_cat,
            'classz' => $classz

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
        if (!RBACTools::can('/paper/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        //
        $data = input('post.') ;

        $data['class_id'] = implode(',', $data['class_id']) ;


        $data['id'] = uniqid() ;
        // 创建时间
        $data['createdate'] = time() ;
        $data['start_time_stamp'] = strtotime($data['start_date'] . ' ' . $data['start_time']) ;
        $data['end_time_stamp'] = strtotime($data['start_date'] . ' ' . $data['end_time']) ;

        $data['poster'] = session('user')['user_name'] ;
        $paper = new \app\admin\model\Paper() ;
        $flag = $paper->allowField(true)->save($data) ;
        if ($flag){
            $this->success('添加成功', url('/paper'), '', '2' ) ;
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
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //

        // 判断权限
        if (!RBACTools::can('/paper/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $menus = MenuTools::get_all_menus() ;

        // 查询传入id的试卷信息
        $paper = \app\admin\model\Paper::find($id) ;
        if (! $paper){
            return ;
        }

        // 获取试卷分类
        $paper_cat = \app\admin\model\PaperCategory::where('status' , 1)->select();
        // 班级信息
        $classz = \app\admin\model\Classz::select() ;



        return view('edit',[
            'menus' => $menus,
            'paper' => $paper,
            'paper_cat' => $paper_cat,
            'classz' => $classz

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
        if (!RBACTools::can('/paper/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $data = input('put.');
        $paper = new \app\admin\model\Paper();

        $data['class_id'] = implode(',', $data['class_id']) ;
        $data['start_time_stamp'] = strtotime($data['start_date'] . ' ' . $data['start_time']) ;
        $data['end_time_stamp'] = strtotime($data['start_date'] . ' ' . $data['end_time']) ;

        $flag = $paper->save(
            $data, ['id' => $id ]
        ) ;

        if ($flag){
            $this->success('更新成功', url('/paper'), '', '2' ) ;
            exit();
        }else{
            // 可以记录日志
            $this->error('更新失败', url('/paper'), '', '2' ) ;
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
        if (!RBACTools::can('/paper/delete')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        //
        $flag = \app\admin\model\Paper::destroy($id) ;

        if ($flag){
            $this->success('删除成功', url('/paper'), '', '2' ) ;
            exit();
        }else{
            // 可以记录日志
            $this->error('删除失败', url('/paper'), '', '2' ) ;
//            \think\facade\Log::write('error') ;
        }
    }


    public function config($id){

        if (!$id){
            return ;
        }


        // 判断权限
        if (!RBACTools::can('/paper/config')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        //菜单
        $menus = MenuTools::get_all_menus() ;

        // 获取题目列表
        $questions =  \app\admin\model\Question::where('status', 1)->limit(9)->select() ;

        $count = \app\admin\model\Question::count() ;
        // 题库信息‘
        $db = \app\admin\model\QuestionDb::where('status', 1)->select() ;


        // 试卷信息
        $paper = \app\admin\model\Paper::where("id", $id)->find() ;

        // paper data to json to array

        $paper_data = json_decode($paper['data'], true) ;




//
//        dump($paper_data) ;
//        exit() ;
        return view('paper_config',[
            'menus' => $menus,
            'questions' => $questions,
            'paper' => $paper,
            'db' => $db,
            'id' => $id,
            'paper_data' => $paper_data,
            'count' => ceil($count /9 )


        ]) ;
    }


    /**
     * 按条件查找试题
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function find(){
        $data =  input('get.') ;
        $page = $data['page'] ;
        unset($data['page']) ;
        $res = [] ;
        if ($data){
            $questions = \app\admin\model\Question::where('status',1)->where($data)->limit(($page-1)*9 , 9)->select() ;

            // 总条数
            $count = \app\admin\model\Question::where('status',1)->where($data)->count() ;
            if (count($questions)>0){
                $res['status'] = 200 ;
                $res['data'] = $questions ;
                $res['count'] = ceil($count / 9) ;

            }else{
                $res['status'] = 0 ;
            }
        }else{
            // 没有筛选参数 直接查询
            $questions = \app\admin\model\Question::where('status',1)->limit(($page-1)*9 , 9)->select() ;

            $count = \app\admin\model\Question::count() ;
            if (count($questions)>0){
                $res['status'] = 200 ;
                $res['data'] = $questions ;
                $res['count'] = ceil( $count / 9 );
            }else{
                $res['status'] = 0 ;
            }
        }

        return $res ;
    }

    public function store(){


        // 判断权限
        if (!RBACTools::can('/paper/config')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        $data = input() ;

        // 假装数据过滤了
        // ....



        // 整理数据格式

        $paper_data = [] ;
        $json = '' ;
        if ($data){

            $paper_data['total_score'] = $data['total_score'] ;


            $paper_data['id'] = $data['id'] ;
            $paper_data['start_time'] = $data['start_time'] ;
            $paper_data['end_time'] = $data['end_time'] ;
            $paper_data['start_date'] = $data['start_date'] ;
            $paper_data['duration'] = $data['duration'] ;
            $paper_data['total_score'] = $data['total_score'] ;
            $paper_data['pass_score'] = $data['pass_score'] ;
            $paper_data['remark'] = $data['remark'] ;
            $paper_data['sessions'] = $data['sessions'] ;

            $flag = \app\admin\model\Paper::where('id', $data['id'])
                ->update([
                    'data'=>json_encode($paper_data),
                    'total_score' => $paper_data['total_score']
                    ]) ; ;


            if ($flag){
                $this->success('配置成功', url('/paper'), '', '2' ) ;
                exit();
            }else{
                // 可以记录日志
                $this->error('配置失败', url('/paper'), '', '2' ) ;
//            \think\facade\Log::write('error') ;
            }




        }else{
            $this->error('删除失败', url('/paper'), '', '2' ) ;
        }





    }


    /**
     * 查看考试详情
     */
    public function detail ($id){


        // 判断权限
        if (!RBACTools::can('/paper/detail/admin')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $menus = MenuTools::get_all_menus() ;

        if (!$id){
            return ;
        }

        // 试卷信息
        $paperInfo = \app\admin\model\Paper::where('id' , $id)->find() ;


        // 考试信息
        $exam_data = Examdata::where('paper_id', $id)->select() ;

        foreach ($exam_data as $each){
            $each['user'] = \app\admin\model\User::where('id', $each['user_id'])->select()[0] ;


//            $each['data'] = json_decode($each['data'], true) ;
        }



        return view('detail', [
            'menus' => $menus,
            'paper' => $paperInfo,
            'examInfo' => $exam_data
        ]) ;



    }


    /**
     * 试卷预览
     */
    public function preview($id){

        $menus = MenuTools::get_all_menus() ;
        // 判断权限
        if (!RBACTools::can('/paper/preview')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $user = session('user') ;
        $user_id = session('user')['id'] ;

        // 读取试卷信息
        $paper = \app\admin\model\Paper::where('id', $id)->find() ;


        if (!$paper){
            return ;
        }

        // 查询使用班级
        $class_id = $paper['class_id'] ;
        $classes = \app\admin\model\Classz::where('id', 'in' , explode(',' , $class_id))->select() ;

        $classes_str = "" ;
        foreach ($classes as $clz){
            $classes_str .= $clz['name'] . ',' ;
        }
        $classes_str = rtrim($classes_str, ',') ;


        $paper_data = json_decode( $paper['data'], true) ;

        if (isset($paper_data['sessions'])){
            // 把题目json data转成数组
            foreach ($paper_data['sessions'] as $k=>$session){

                foreach ($session['questions'] as $j=>$qsn){
                    $paper_data['sessions'][$k]['questions'][$j]['data']  =
                        json_decode( $paper_data['sessions'][$k]['questions'][$j]['data'] , true);


                }

            }
        }


        return view('preview',[
            'id' => $user_id,
            'paper' => $paper,
            'classes' => $classes_str,

            'menus' => $menus,
            'user' => $user,
            'paper_data' => $paper_data,



        ]) ;


    }




}
