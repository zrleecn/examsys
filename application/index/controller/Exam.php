<?php

namespace app\index\controller;

use app\admin\model\Classz;
use app\admin\model\Examdata;
use app\admin\model\Paper;
use app\admin\model\Question;
use think\Controller;
use think\Request;

class Exam extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        // 获取个人信息
        $user = session('user') ;
        $id = session('user')['id'] ;
        if (!$user){
            $this->error('请先登录', url('/login'), '', '1') ;
        }

        // 当前时间日期字符串
        $date_str = date('Y-m-d', time()) ;

        $class_id =  $user['class_id'] ;

        //查找试卷
        $pagesize = \think\facade\Config::get('pagesize');
//        $paper = Paper::where('start_date', $date_str)->paginate($pagesize) ;
//        $paper = Paper::where('start_date', $date_str)->where('class_id','like' , '%' . $class_id . '%')->select();
        $paper = Paper::where('class_id','like' , '%' . $class_id . '%')->order('start_time_stamp', 'desc')->select();


        $my_exam = [] ;
//        foreach ($paper as $k=>$v){
//            $class_id_arr = explode(',' , $v['class_id']) ;
//
//            if (in_array($class_id, $class_id_arr)) {
//                // 到时后再通过考试记录表过滤已经考过的试卷
//                $my_exam[] = $v;
//            }
//        }
//
//        dump($paper) ;
//
//        exit() ;

        $now_timestamp = time() ;
        foreach ($paper as $k=>$v){

            $edata =  Examdata::where('paper_id', $v['id'])->where('user_id',$id)->find() ;

            if ($v['start_time_stamp'] < $now_timestamp){
                // 超过考试时间的考试试卷

                $v['timeout'] = 1 ;
            }else{
                $v['timeout'] = 0 ;
            }


           if ($edata['status'] != 1){
               $my_exam[] = $v ;
           }
        }





        return view('exam',[
            'id' => $id,
            'exams' => $my_exam
        ]) ;
    }


    /**
     *
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function history(){
        $user = session('user') ;
        $id = session('user')['id'] ;
        if (!$user){
            $this->error('请先登录', url('/login'), '', '1') ;
        }

        // 当前时间日期字符串
        $date_str = date('Y-m-d', time()) ;

        $class_id =  $user['class_id'] ;

        $paper = Paper::where('class_id','like' , '%' . $class_id . '%')->select();

        $my_exam = [] ;

        foreach ($paper as $k=>$v){

            $edata =  Examdata::where('paper_id', $v['id'])->where('user_id',$id)->find() ;



            if ($edata['status'] == 1){
                $my_exam[] = $v ;
            }
        }

        // 获取试卷考试成绩
        foreach ($my_exam as $v){
            $exam_info = Examdata::where('paper_id', $v['id'])->where('user_id', $id)->find() ;
            $v['score'] = $exam_info['score'] ;
        }



        return view('history',[
            'id' => $id,
            'exams' => $my_exam
        ]) ;
    }


    /**
     * 开始答题
     * @param $id
     * @return \think\response\View|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function doExam($id){


        $user = session('user') ;
        $user_id = session('user')['id'] ;

        // 读取试卷信息
        $paper = Paper::where('id', $id)->find() ;


        if (!$paper){
            return ;
        }

        // 查看考试时间
        if ($paper['end_time_stamp'] < time()){
            return $this->error('考试时间已经结束！', url('/exam'), '', '10' ) ;
        }


        // 重复考试验证
        $exam = new Examdata() ;

        $einfo = $exam->where('paper_id', $paper['id'])->where('user_id', $user_id)->find() ;
        if ($einfo['status'] == 1){
            return $this->error('你已经完成这次考试了！', url('/exam'), '', '10' ) ;
        }






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

//        dump($paper_data) ;
//        exit() ;

        // 查询使用班级
        $class_id = $paper['class_id'] ;
        $classes = Classz::where('id', 'in' , explode(',' , $class_id))->select() ;

        $classes_str = "" ;
        foreach ($classes as $clz){
           $classes_str .= $clz['name'] . ',' ;
        }
        $classes_str = rtrim($classes_str, ',') ;




        if (!$user){
            $this->error('请先登录', url('/login'), '', '1') ;
        }

        return view('doExam',[
            'id' => $user_id,
            'paper' => $paper,
            'paper_data' => $paper_data,
            'classes' => $classes_str,
            'user' => $user


        ]) ;


    }


    public function detail($id){
        $user = session('user') ;
        $user_id = session('user')['id'] ;

        // 读取试卷信息
        $paper = Paper::where('id', $id)->find() ;


        if (!$paper){
            return ;
        }

        // 查询使用班级
        $class_id = $paper['class_id'] ;
        $classes = Classz::where('id', 'in' , explode(',' , $class_id))->select() ;

        $classes_str = "" ;
        foreach ($classes as $clz){
            $classes_str .= $clz['name'] . ',' ;
        }
        $classes_str = rtrim($classes_str, ',') ;

        // 考试信息
        $exam_info = Examdata::where('paper_id', $id)->where('user_id', $user_id)->find() ;


        $exam_data_array = json_decode($exam_info['data'] , true) ;


//        dump($exam_data_array) ;
//        exit() ;

        // 有些填空题是多个填空项的 需要把数组转成字符串输出到html
        foreach ($exam_data_array as $k=>$v){
            if (is_array($v)){
                if (is_array($v['ukey'])){
                    $exam_data_array[$k]['ukey'] = implode(',' , $v['ukey']) ;
                }
            }

        }

//        dump($exam_data_array) ;
//        exit() ;


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

//        dump($paper_data) ;
//        exit() ;
//


        return view('detail',[
            'id' => $user_id,
            'paper' => $paper,
            'classes' => $classes_str,
            'examInfo' => $exam_info,
            'user' => $user,
            'paper_data' => $paper_data,
            'exam_data' => $exam_data_array


        ]) ;




    }

    /**
     * 交卷
     */
    public function submit(){


        $input = input() ;

//        dump($input) ;
//        exit() ;


        $input_data = $input ; // 这个保存原来状态数据 不改变




        // 假装过滤数据了



        // 填空题 答案转字符串
        foreach ($input as $k=>$v){
            if (is_array($v)){
                if (is_array($v['ukey'])){


                    $input[$k]['ukey'] = implode(',', $input[$k]['ukey']);
                }
            }

        }

        $user_id = session('user')['id'] ;
        $exam_id =  uniqid() ;
        $data['id'] = $exam_id ;
        $data['user_id'] = $user_id ;
        $data['paper_id'] = $input['id'] ;

        unset($input['id']) ;
        $data['data'] = json_encode($input_data,true) ;
        $data['status'] = 1 ;


        // 批改试卷


        unset($input_data['id']) ;

        $total_score = 0 ;



        $exam = new Examdata() ;

        $einfo = $exam->where('paper_id', $data['paper_id'])->where('user_id', $user_id)->find() ;
        if ($einfo['status'] == 1){
            return $this->error('试卷已经提交过不能再次提交', url('/exam'), '', '10' ) ;
        }




        if($exam->allowField(true)->save($data) ){

            foreach ($input_data as $k=>$v){

                $q = Question::where('id', $k)->find() ;  // array('key'=>value)

                if ($q['type'] != 5 && $q['type'] != 4){  // 简答题手动批改
//                    echo $v['ukey'] . '===' . $q['key']  ;
                    if ($v['ukey'] == $q['key']){
                        // 答题正确
                        $total_score += intval($v['score']) ;
//                        echo $total_score .'<br>' ;
                    }
                }

                if ($q['type'] == 4){
                    // 填空题批改
                    $ukey = implode(',', $v['ukey'] ) ;
//                    echo $ukey . "--->" . $q['key'] ;

                    if ($ukey == $q['key']){
                        $total_score += intval($v['score']) ;
//                        echo $total_score .'<br>';
                    }
                }






            }
            // 保存分数
            $exam->where('id' , $exam_id)->update(['score'=>$total_score]) ;


            $this->success('提交成功', url('/exam'), '', '10' ) ;
        }else{
            $this->success('提交失败', url('/exam'), '', '10' ) ;
        }




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
}
