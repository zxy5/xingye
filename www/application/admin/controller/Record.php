<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/18
 * Time: 9:58
 * 记录类
 */

namespace app\admin\controller;


use think\Db;
use PHPExcel;
use PHPExcel_IOFactory;
class Record extends Base
{
    /**
     * etc记录
     */
    public function etc_list(){
        $where = array();
        $url = '/admin/record/etc_export';
        if( !empty(input('param.name')) ){
            $where['etc_name'] = ['like','%'.input('param.name').'%'];
            $url .= '/name/'.trim($_GET['name']);
        }
        if( !empty($_GET['start']) ){
            $where['add_time'] = ['>=',strtotime($_GET['start'])];
            $url .= '/start/'.trim($_GET['start']);
        }
        if( !empty($_GET['end']) ){
            $where['add_time'] = ['<=',strtotime($_GET['end'])];
            $url .= '/end/'.trim($_GET['end']);
        }
        $list = Db::name('record_etc')->where($where)->order('add_time desc,id desc')->paginate(15);
        $this->assign('ex_url',$url);
        $this->assign('list',$list);
        $this->assign('get',$_GET);
        return $this->fetch();
    }

    /**
     * 删除
     */
    public function etc_del(){
        $id = input('param.id');
        $re = Db::name('record_etc')->where('id',$id)->delete();
        if( $re ){
            return json(msg('1','','删除成功！'));
        }else{
            return json(msg('-1','','删除失败！'));
        }
    }

    /**
     * etc记录导出
     */
    public function etc_export(){
        $where = array();
        if( !empty(input('param.name')) ){
            $where['etc_name'] = ['like','%'.input('param.name').'%'];
        }
        if( !empty($_GET['start']) ){
            $where['add_time'] = ['>=',strtotime($_GET['start'])];
        }
        if( !empty($_GET['end']) ){
            $where['add_time'] = ['<=',strtotime($_GET['end'])];
        }

        $data = Db::name('record_etc')->where($where)->order('add_time desc,id desc')->select();
        $PHPExcel = new PHPExcel();
        $PHPSheet = $PHPExcel->getActiveSheet(); //获得当前活动sheet的操作对象
        $PHPSheet->setTitle('excel数据导出'); //给当前活动sheet设置名称
        //给当前活动sheet填充数据，数据填充是按顺序一行一行填充的
        $PHPSheet->setCellValue('A1','id')
            ->setCellValue('B1','姓名')
            ->setCellValue('C1','电话')
            ->setCellValue('D1','添加时间');
        foreach( $data as $k => $v ){
            $key = $k+2;
            $PHPSheet->setCellValue('A'.$key , $v['id'])
                ->setCellValue('B'.$key , $v['etc_name'])
                ->setCellValue('C'.$key , $v['etc_phone'])
                ->setCellValue('E'.$key , date('Y-m-d',$v['add_time']));
        }
        $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');//按照指定格式生成Excel文件，‘Excel2007’表示生成2007版本的xlsx，
        header('Content-Type: applicationnd.ms-excel');
        header('Content-Disposition: attachment;filename="ETC记录表.xls"');
        header('Cache-Control: max-age=0');
        $PHPWriter->save ( 'php://output' );
        exit;
    }

    /**
     * 社保记录列表
     */
    public function social_list(){
        $where = array();
        $url = '/admin/record/social_export';
        if( !empty(input('param.name')) ){
            $where['s_name'] = ['like','%'.input('param.name').'%'];
            $url .= '/name/'.trim($_GET['name']);
        }
        if( !empty($_GET['start']) ){
            $where['add_time'] = ['>=',strtotime($_GET['start'])];
            $url .= '/start/'.trim($_GET['start']);
        }
        if( !empty($_GET['end']) ){
            $where['add_time'] = ['<=',strtotime($_GET['end'])];
            $url .= '/end/'.trim($_GET['end']);
        }
        $list = Db::name('record_social')->where($where)->order('add_time desc,id desc')->paginate(15);
        $this->assign('ex_url',$url);
        $this->assign('list',$list);
        $this->assign('get',$_GET);
        return $this->fetch();
    }

    /**
     * 删除社保记录
     * @return \think\response\Json
     * @throws \think\Exception
     */
    public function social_del(){
        $id = input('param.id');
        $re = Db::name('record_social')->where('id',$id)->delete();
        if( $re ){
            return json(msg('1','','删除成功！'));
        }else{
            return json(msg('-1','','删除失败！'));
        }
    }

    /**
     * 导出社保记录
     */
    public function social_export(){
        $where = array();
        if( !empty(input('param.name')) ){
            $where['s_name'] = ['like','%'.input('param.name').'%'];
        }
        if( !empty($_GET['start']) ){
            $where['add_time'] = ['>=',strtotime($_GET['start'])];
        }
        if( !empty($_GET['end']) ){
            $where['add_time'] = ['<=',strtotime($_GET['end'])];
        }

        $data = Db::name('record_social')->where($where)->order('add_time desc,id desc')->select();
        $PHPExcel = new PHPExcel();
        $PHPSheet = $PHPExcel->getActiveSheet(); //获得当前活动sheet的操作对象
        $PHPSheet->setTitle('excel数据导出'); //给当前活动sheet设置名称
        //给当前活动sheet填充数据，数据填充是按顺序一行一行填充的
        $PHPSheet->setCellValue('A1','id')
            ->setCellValue('B1','姓名')
            ->setCellValue('C1','电话')
            ->setCellValue('D1','添加时间');
        foreach( $data as $k => $v ){
            $key = $k+2;
            $PHPSheet->setCellValue('A'.$key , $v['id'])
                ->setCellValue('B'.$key , $v['s_name'])
                ->setCellValue('C'.$key , $v['s_phone'])
                ->setCellValue('E'.$key , date('Y-m-d',$v['add_time']));
        }
        $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');//按照指定格式生成Excel文件，‘Excel2007’表示生成2007版本的xlsx，
        header('Content-Type: applicationnd.ms-excel');
        header('Content-Disposition: attachment;filename="社保记录表.xls"');
        header('Cache-Control: max-age=0');
        $PHPWriter->save ( 'php://output' );
        exit;
    }
}