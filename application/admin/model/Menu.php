<?php

namespace app\admin\model;

use think\Model;

class Menu extends Model
{
    //


    /**
     *
     * 递归获取菜单
     * @param int $pid 父菜单的id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get_all_menu($pid=0){



        $data = Menu::where('pid', $pid)->select() ;


        foreach ( $data as $k=>$v){
            $data[$k]['child'] = [] ;
            // 获取子菜单
            $data[$k]['child'] = $this->get_all_menu($v['id']) ;
        }

        return $data ;
    }
}
