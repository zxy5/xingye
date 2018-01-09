<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/9
 * Time: 13:42
 *
 *优惠群分类model
 */

namespace app\admin\model;


use think\exception\PDOException;
use think\Model;

class CouponsClassModel extends Model
{
    protected $table = 'sd_coupons_class';

    /**
     * 获取分类列表
     * @param null $where
     * @param string $feild
     * @param string $order
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getClassList( $where = null,$feild = '*',$order = 'class_id desc' ){

        return $this->field($feild)->where($where)->order($order)->select();
    }

    /**
     * 获取分类信息
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     */
    public function getClassById( $id ){
        return $this->where('class_id',$id)->find();
    }


    /**
     * 通过id删除分类
     * @param $id
     * @return array
     */
    public function delClassById( $id ){
        try{
            $this->where('id',$id)->delete();
            return msg(1, '', '删除成功！');
        }catch( PDOException $e ){
            return msg(-1, '', $e->getMessage());
        }
    }

    /**
     * 通过id编辑分类
     * @param $id
     * @param $param
     * @return array
     */
    public function editClass( $id , $param ){
        try{
            $rule = [
                ['class_name','require','分类名称不能为空'],
            ];
            $re = $this->validate($rule)->where('id',$id)->update($param);
            if( $re ){
                return msg(1,'','更新成功！');
            }else{
                return msg( -1 , '' , '更新失败！');
            }
        }catch( PDOException $e ){
            return msg( -2 , '' , $e->getMessage() );
        }
    }

    /**
     * 添加分类
     * @param $param
     * @return array
     */
    public function addClass( $param ){
        try{
            $rule = [
                ['class_name','require','分类名称不能为空'],
            ];
            $re = $this->validate($rule)->insert($param);
            if( $re ){
                return msg(1,'','添加成功！');
            }else{
                return msg( -1 , '' , '添加失败！');
            }
        }catch( PDOException $e ){
            return msg( -2 , '' , $e->getMessage() );
        }
    }

}