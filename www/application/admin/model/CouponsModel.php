<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/14
 * Time: 17:02
 */

namespace app\admin\model;


use think\Db;
use think\Model;
use think\Validate;

class CouponsModel extends Model
{
    protected $table = 'sd_coupons';

    /**
     * 获取门店优惠券列表
     * @param null $where
     * @param int $page
     * @return \think\Paginator
     */
    public function getCouponsList( $where = null,$field='' ,$order = 'a.id desc', $page = 15 ){
        if( $field=='' ){
            $field = 'a.*,class_name';
        }
        $re = $this->alias('a')->field( $field)
            ->join('__ADMIN__ b','a.user_id=b.id','LEFT')
            ->join('__COUPONS_CLASS__ c','a.class_id=c.class_id','LEFT')
            ->where($where)->order($order)->paginate($page);
        return $re;
//        return $this->where($where)->paginate($page);
    }

    /**
     * 获取列表
     * @param null $where
     * @param string $field
     * @param string $order
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getCouponsAll( $where = null,$field='' ,$order = 'a.id desc' ){
        if( $field=='' ){
            $field = 'a.*,user_name';
        }
        return Db::name('coupons')->alias('a')->field( $field)->join('__ADMIN__ b','a.user_id=b.id','LEFT')->where($where)->order($order)->select();
    }

    /**
     * 通过id获取
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     */
    public function getCouponsById( $id ,$field = '*'){
        return $this->field($field)->where('id',$id)->find();
    }

    /**
     * 重置密码
     * @param $id
     * @param $pas
     * @return array
     */
    public function resetPasswordById( $id,$pas ){
        try{
            $result =  $this->where('id',$id)->update(['login_password'=>$pas,'is_modify'=>0]);
            if($result){
                return msg(1, '', '重置成功！');
            }else{
                return msg(-1, '', '重置失败！');
            }
        }catch(PDOException $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 修改
     * @param $id
     * @param $param
     * @return array
     */
    public function editCouponsById( $id,$param ){
        try{
            $rule = [
                ['name', 'require', '优惠券名称不能为空'],
                ['thumd', 'require', '优惠券缩略图不能为空'],
                ['desc', 'require', '优惠券描述不能为空'],
            ];
            $validate = new Validate($rule);
            $validate->rule('login_phone','/^1[34578]\d{9}$/');
            $validate->message('login_phone','手机号码格式不正确');

            if(!$validate->check($param)){
                return msg('-1','',$validate->getError());
            }
            $result =  $this->validate($rule)->where('id',$id)->update($param);
            if($result){
                return msg(1, '', '修改成功！');
            }else{
                return msg(-1, '', '修改失败！');
            }
        }catch(PDOException $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 删除
     * @param $id
     * @return array
     */
    public function delCouponsById( $id ){
        try{
            $this->where('id',$id)->delete();
            return msg(1, '', '删除成功！');
        }catch( PDOException $e ){
            return msg(-1, '', $e->getMessage());
        }
    }

    /**
     * 增加优惠券
     * @param $param
     * @return array
     */
    public function insertCoupons( $param ){
        try{
            $rule = [
                ['name', 'require', '优惠券名称不能为空'],
                ['thumd', 'require', '优惠券缩略图不能为空'],
                ['desc', 'require', '优惠券描述不能为空'],
            ];
            $validate = new Validate($rule);
            $validate->rule('login_phone','/^1[34578]\d{9}$/');
            $validate->message('login_phone','手机号码格式不正确');

            if(!$validate->check($param)){
                return msg('-1','',$validate->getError());
            }
            $result =  $this->insert($param);
            if($result){
                return msg(1, '', '添加成功');
            }else{
                // 验证失败 输出错误信息
                return msg(-1, '', '添加失败!');
            }
        }catch(PDOException $e){
            return msg(-2, '', $e->getMessage());
        }
    }

}