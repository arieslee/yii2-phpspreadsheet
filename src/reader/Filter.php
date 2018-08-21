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
    public $startRow;
    /**
     * @var int
     */
    public $endRow;

    /**
     * Filter constructor.
     * @param int $startRow
     * @param int $endRow
     */
    public function __construct($startRow = 1, $endRow = 0) {
        $this->startRow = $startRow;
        $this->endRow = $endRow;
    }

    /**
     * read cell
     * @param $column
     * @param $row
     * @param string $worksheetName
     * @return bool
     */
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