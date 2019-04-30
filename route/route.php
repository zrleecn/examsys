<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------



Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');


// admin/QuestionDb资源路由
Route::resource('db','admin/Question_Db')->middleware('Login');
// 试题
Route::resource('question','admin/Question')->middleware('Login');
// 试卷分类
Route::resource('papercate','admin/PaperCategory')->middleware('Login');
// 院系
Route::resource('dept', 'admin/Department')->middleware('Login');
// 班级资源路由
Route::resource('clz', 'admin/Classz')->middleware('Login');
// 用户管理资源路由
Route::resource('user', 'admin/User')->middleware('Login');
// 教师管理资源路由
Route::resource('teacher', 'admin/Teacher')->middleware('Login');

// 试卷资源路由
Route::resource('paper', 'admin/Paper')->middleware('Login');
// 专业管理资源路由
Route::resource('major', 'admin/Major')->middleware('Login');
Route::resource('student', 'index/User')->middleware('Login');

// 新闻公告资源路由
Route::resource('newscate', 'admin/NewsCategory')->middleware('Login');
Route::resource('news', 'admin/News')->middleware('Login');



Route::rule('student/passwd/:id/edit', 'index/user/passwdedit')->middleware('Login');
Route::rule('api/clz/check/:name','admin/Classz/check');
Route::rule('api/dept/check/:name','admin/Department/check');

// 登录
Route::alias('login','admin/Login');

Route::rule('exam/doExam/:id', 'index/Exam/doExam')->middleware('Login');
Route::rule('exam/detail/:id', 'index/Exam/detail')->middleware('Login');
Route::rule('exam/submit', 'index/Exam/submit')->middleware('Login');
Route::rule('exam/history', 'index/Exam/history')->middleware('Login');

Route::rule('exam', 'index/Exam/index')->middleware('Login');

Route::rule('config/paper/:id','admin/Paper/config');
Route::rule('detail/paper/:id','admin/Paper/detail');
// 试卷预览
Route::rule('/paper/preview/:id','admin/Paper/preview');
Route::rule('api/paper/find','admin/Paper/find');
Route::post('api/paper/store','admin/Paper/store');


// db 别名路由到 admin/QuestionDb 控制器
//Route::alias('db','admin/Question_Db');



return [

];
