<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/18
 * Time: 16:26
 */

namespace app\admin\model;



use think\Model;
use think\Validate;

class StoreModel extends Model
{
    protected $table = 'sd_store';

    /**
     * 获取列表
     * @param null $where
     * @param string $field
     * @param string $order
     * @return \think\Paginator
     */
    public function getStoreList( $where = null,$field='' ,$order='a.id desc'){
        if( $field=='' ){
            $field = 'a.*,b.name as c_name,c.user_name';
        }
        return $this->alias('a')->field($field)
                    ->join('__STORE_COUPONS__ b','a.coupons_id=b.id','LEFT')
                    ->join('__ADMIN__ c','a.user_id=c.id','LEFT')
                    ->where($where)->order($order)->paginate();
    }


    /**
     * 编辑门店
     * @param $id
     * @param $param
     * @return array
     */
    public function editStoreById( $id , $param ){
        try{
            $rule = [
                'store_name' => 'require',
                'coupons_id' => 'number',
            ];
            $msg = [
                'store_phone.require'=>'门店名称不能为空',
                'coupons_id.number'=>'优惠券id为数字'
            ];
            $validate = new Validate($rule,$msg);
            $validate->rule('store_phone','/^1[34578]\d{9}$/');
            $validate->message('store_phone','手机号码格式不正确');

            if(!$validate->check($param)){
                return msg('-1','',$validate->getError());
            }
            $result =  $this->where('id',$id)->update($param);
            if($result){
                return msg(1, '', '编辑成功');
            }else{
                // 验证失败 输出错误信息
                return msg(-1, '', '编辑失败!');
            }
        }catch(PDOException $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 通过id删除门店
     * @param $id
     * @return array
     */
    public function delStoreById( $id ){
        try{
            $this->where('id',$id)->delete();
            return msg(1, '', '删除成功！');
        }catch( PDOException $e ){
            return msg(-1, '', $e->getMessage());
        }
    }

    /**
     * 通过id获取门店信息
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     */
    public function getStoreById( $id ){
        return $this->where('id',$id)->find();
    }

    /**
     * 添加门店
     * @param $param
     * @return array
     */
    public function insertStore( $param ){
        try{
            $rule = [
                'store_name' => 'require',
                'coupons_id' => 'number',
            ];
            $msg = [
                'store_phone.require'=>'门店名称不能为空',
                'coupons_id.number'=>'优惠券id为数字'
            ];
            $validate = new Validate($rule,$msg);
            $validate->rule('store_phone','/^1[34578]\d{9}$/');
            $validate->message('store_phone','手机号码格式不正确');

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

    /**
     * 检测数据
     * @param $data
     * @return array
     */
    public function checkStoreData( $data ){
        $rule = [
            'store_name' => 'require',
            'coupons_id' => 'number',
        ];
        $msg = [
            'store_phone.require'=>'门店名称不能为空',
            'coupons_id.number'=>'优惠券id为数字'
        ];
        $validate = new Validate($rule,$msg);
        $validate->rule('store_phone','/^1[34578]\d{9}$/');
        $validate->message('store_phone','手机号码格式不正确');

        if(!$validate->check($data)){
            return msg('-1','',$validate->getError());
        }else{
            return msg('1','','符合');
        }
    }

}