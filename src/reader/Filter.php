<?php
/**
 * Created by PhpStorm.
 * User: sunmoon
 * Date: 2018/8/21
 * Time: 下午2:29
 */

namespace sunmoon\phpspreadsheet\reader;


class Filter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter{
    /**
     * @var int
     */
    public $startRow = 1;
    /**
     * @var int
     */
    public $endRow = 0;
    public function readCell($column, $row, $worksheetName = '') {
        //如果不设置endRow，那就读取全部数据
        if(!$this->endRow){
            return true;
        }
        //只读取指定的行
        if ($row == 1 || ($row >= $this->startRow && $row <= $this->endRow)) {
            return true;
        }
        return false;
    }
}