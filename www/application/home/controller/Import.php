<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/22
 * Time: 15:03
 */

namespace app\home\controller;


use think\Controller;
use PHPExcel_Reader_Excel2007;
use PHPExcel_Cell;
use think\Db;

class Import extends Controller
{

    public function index(){
//        $reader = \PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
//        $PHPExcel = $reader->load("test.xlsx"); // 载入excel文件
//
        $objReader = new PHPExcel_Reader_Excel2007();
        $objExcel = $objReader ->load('test4.xlsx');

        $sheet = $objExcel ->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumm); //总列数转码
        for( $i=0; $i<$highestRow-1; $i++ ){
            $data[$i]['name'] = $sheet->getCellByColumnAndRow('0',$i+2)->getValue();
            $data[$i]['store_address'] = $sheet->getCellByColumnAndRow('1',$i+2)->getValue();
            $data[$i]['store_phone'] = $sheet->getCellByColumnAndRow('2',$i+2)->getValue();
//            $data[$i][3] = $sheet->getCellByColumnAndRow('3',$i+2)->getValue();
            $data[$i]['desc'] = $sheet->getCellByColumnAndRow('4',$i+2)->getValue();
            preg_match_all('/\d+[.]?\d*/',$data[$i]['desc'],$re);
            $a = round(($re[0][0]/$re[0][1])*10,2);
            $data[$i]['discount'] = $a;
            $data[$i]['add_time'] = time();
            $data[$i]['class_id'] =5;
            $data[$i]['start_time'] = time();
            $data[$i]['end_time'] = strtotime('2019-1-1 00:00:00');
            $data[$i]['user_id'] = 1;
        }

//        $str = '82.5元！价值100元的代金券1张，仅适用于菜品、鲜榨饮料、茶水、自带酒水服务费，不可抵扣香烟、酒水，可叠加使用52张';
//        preg_match_all('/\d+[.]?\d*/',$str, $matches);
        print_r( $data);
//        Db::name('coupons')->insertAll($data);
    }
}