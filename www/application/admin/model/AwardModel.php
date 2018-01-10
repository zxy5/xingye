<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/13
 * Time: 14:57
 *
 * 奖品model类
 */
namespace app\admin\model;

use think\Model;
use think\Validate;

class AwardModel extends Model
{
    // 确定链接表名
    protected $table = 'sd_award';

    /**
     * 获取奖品列表
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getAwardList( $where = null ){
        return $this->field($this->table . '.*,user_name')
            ->join('__ADMIN__', $this->table . '.user_id = ' . 'sd_admin.id','LEFT')
            ->order('id desc')->where($where)->select();
    }

    /**
     * 通过id获取奖品
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     */
    public function getAwardById( $id ){
        return $this->where('id',$id)->find();
    }

    /**
     * 编辑
     * @param $id
     * @param $param
     * @return array
     */
    public function editAwardById( $id,$param ){
        try{
            $rule = [
                ['name', 'require', '奖品名称不能为空'],
                ['thumd', 'require', '奖品缩略图不能为空'],
                ['type', 'require', '奖品类型不能为空'],
                ['desc', 'require', '奖品描述不能为空'],
                ['chance', 'number|elt:10000', '中奖概率必须为数字|中奖概率必须小于10000'],
                ['num', 'number', '奖品数量必须为数字']
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return msg('-1','',$validate->getError());
            }
            $result =  $this->where('id',$id)->update($param);
            if($result){
                return msg(1, '', '奖品修改成功！');
            }else{
                return msg(-1, '', '修改失败！');
            }
        }catch(PDOException $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 获取中奖概率之和
     * @return float|int
     */
    public function getChanceSum( $where = null ){
        return $this->where($where)->sum('chance');
    }

    /**
     * 添加奖品
     * @param $param
     * @return array
     */
    public function insertAward($param){
        try{
            $rule = [
                ['name', 'require', '奖品名称不能为空'],
                ['thumd', 'require', '奖品缩略图不能为空'],
                ['type', 'require', '奖品类型不能为空'],
                ['desc', 'require', '奖品描述不能为空'],
                ['chance', 'number|elt:10000', '中奖概率必须为数字|中奖概率必须小于10000'],
                ['num', 'number', '奖品数量必须为数字']
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return msg('-1','',$validate->getError());
            }
            $result =  $this->insert($param);
            if($result){
                return msg(1, '', '添加奖品成功');
            }else{
                // 验证失败 输出错误信息
                return msg(-1, '', '添加失败!');
            }
        }catch(PDOException $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 删除奖品
     *
     * @param $id
     * @return array
     */
    public function delAwardById( $id ){
        try{
            $this->where('id',$id)->delete();
            return msg(1, '', '删除成功！');
        }catch( PDOException $e ){
            return msg(-1, '', $e->getMessage());
        }
    }


}