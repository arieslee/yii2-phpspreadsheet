<?php
namespace sunmoon\phpspreadsheet;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\i18n\Formatter;
use yii\helpers\Json;
/**
 * Excel Widget for generate Excel File or for load Excel File.
 *
 * Usage
 * -----
 *
 * Exporting data into an excel file.
 *
 * ~~~
 *
 * // export data only one worksheet.
 *
 * \sunmoon\phpspreadsheet\Excel::widget([
 * 		'models' => $allModels,
 * 		'mode' => 'export', //default value as 'export'
 * 		'columns' => ['column1','column2','column3'],
 * 		//without header working, because the header will be get label from attribute label.
 * 		'headers' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
 * ]);
 *
 * \sunmoon\phpspreadsheet\Excel::export([
 * 		'models' => $allModels,
 * 		'columns' => ['column1','column2','column3'],
 * 		//without header working, because the header will be get label from attribute label.
 * 		'headers' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
 * ]);
 *
 * // export data with multiple worksheet.
 *
 * \sunmoon\phpspreadsheet\Excel::widget([
 * 		'isMultipleSheet' => true,
 * 		'models' => [
 * 			'sheet1' => $allModels1,
 * 			'sheet2' => $allModels2,
 * 			'sheet3' => $allModels3
 * 		],
 * 		'mode' => 'export', //default value as 'export'
 * 		'columns' => [
 * 			'sheet1' => ['column1','column2','column3'],
 * 			'sheet2' => ['column1','column2','column3'],
 * 			'sheet3' => ['column1','column2','column3']
 * 		],
 * 		//without header working, because the header will be get label from attribute label.
 * 		'headers' => [
 * 			'sheet1' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
 * 			'sheet2' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
 * 			'sheet3' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3']
 * 		],
 * ]);
 *
 * \sunmoon\phpspreadsheet\Excel::export([
 * 		'isMultipleSheet' => true,
 * 		'models' => [
 * 			'sheet1' => $allModels1,
 * 			'sheet2' => $allModels2,
 * 			'sheet3' => $allModels3
 * 		],
 * 		'columns' => [
 * 			'sheet1' => ['column1','column2','column3'],
 * 			'sheet2' => ['column1','column2','column3'],
 * 			'sheet3' => ['column1','column2','column3']
 * 		],
 * 		//without header working, because the header will be get label from attribute label.
 * 		'headers' => [
 * 			'sheet1' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
 * 			'sheet2' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
 * 			'sheet3' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3']
 * 		],
 * ]);
 *
 * ~~~
 *
 * New Feature for exporting data, you can use this if you familiar yii gridview.
 * That is same with gridview data column.
 * Columns in array mode valid params are 'attribute', 'header', 'format', 'value', and footer (TODO).
 * Columns in string mode valid layout are 'attribute:format:header:footer(TODO)'.
 *
 * ~~~
 *
 * \sunmoon\phpspreadsheet\Excel::export([
 *  	'models' => Post::find()->all(),
 *     	'columns' => [
 *     		'author.name:text:Author Name',
 *     		[
 *     				'attribute' => 'content',
 *     				'header' => 'Content Post',
 *     				'format' => 'text',
 *     				'value' => function($model) {
 *     					return ExampleClass::removeText('example', $model->content);
 *     				},
 *     		],
 *     		'like_it:text:Reader like this content',
 *     		'created_at:datetime',
 *     		[
 *     				'attribute' => 'updated_at',
 *     				'format' => 'date',
 *     		],
 *     	],
 *     	'headers' => [
 *     		'created_at' => 'Date Created Content',
 * 		],
 * ]);
 *
 * ~~~
 *
 *
 * Import file excel and return into an array.
 *
 * ~~~
 *
 * $data = \sunmoon\phpspreadsheet\Excel::import($fileName, $config); // $config is an optional
 *
 * $data = \sunmoon\phpspreadsheet\Excel::widget([
 * 		'mode' => 'import',
 * 		'fileName' => $fileName,
 * 		'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
 * 		'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
 * 		'getOnlySheet' => 'sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
 * ]);
 *
 * $data = \sunmoon\phpspreadsheet\Excel::import($fileName, [
 * 		'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
 * 		'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
 * 		'getOnlySheet' => 'sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
 *	]);
 *
 * // import data with multiple file.
 *
 * $data = \sunmoon\phpspreadsheet\Excel::widget([
 * 		'mode' => 'import',
 * 		'fileName' => [
 * 			'file1' => $fileName1,
 * 			'file2' => $fileName2,
 * 			'file3' => $fileName3,
 * 		],
 * 		'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
 * 		'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
 * 		'getOnlySheet' => 'sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
 * ]);
 *
 * $data = \sunmoon\phpspreadsheet\Excel::import([
 * 			'file1' => $fileName1,
 * 			'file2' => $fileName2,
 * 			'file3' => $fileName3,
 * 		], [
 * 		'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
 * 		'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
 * 		'getOnlySheet' => 'sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
 *	]);
 *
 * ~~~
 *
 * Result example from the code on the top :
 *
 * ~~~
 *
 * // only one sheet or specified sheet.
 *
 * Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2));
 *
 * // data with multiple worksheet
 *
 * Array([Sheet1] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2)),
 * [Sheet2] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2)));
 *
 * // data with multiple file and specified sheet or only one worksheet
 *
 * Array([file1] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2)),
 * [file2] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2)));
 *
 * // data with multiple file and multiple worksheet
 *
 * Array([file1] => Array([Sheet1] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2)),
 * [Sheet2] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2))),
 * [file2] => Array([Sheet1] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2)),
 * [Sheet2] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2))));
 *
 * ~~~
 *
 * @property string $mode is an export mode or import mode. valid value are 'export' and 'import'
 * @property boolean $isMultipleSheet for set the export excel with multiple sheet.
 * @property array $properties for set property on the excel object.
 * @property array $models Model object or DataProvider object with much data.
 * @property array $columns to get the attributes from the model, this valid value only the exist attribute on the model.
 * If this is not set, then all attribute of the model will be set as columns.
 * @property array $headers to set the header column on first line. Set this if want to custom header.
 * If not set, the header will get attributes label of model attributes.
 * @property string|array $fileName is a name for file name to export or import. Multiple file name only use for import mode, not work if you use the export mode.
 * @property string $savePath is a directory to save the file or you can blank this to set the file as attachment.
 * @property string $format for excel to export. Valid value are 'Excel5','Excel2007','Excel2003XML','00Calc','Gnumeric'.
 * @property boolean $setFirstTitle to set the title column on the first line. The columns will have a header on the first line.
 * @property boolean $asAttachment to set the file excel to download mode.
 * @property boolean $setFirstRecordAsKeys to set the first record on excel file to a keys of array per line.
 * If you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
 * @property boolean $setIndexSheetByName to set the sheet index by sheet name or array result if the sheet not only one
 * @property string $getOnlySheet is a sheet name to getting the data. This is only get the sheet with same name.
 * @property array|Formatter $formatter the formatter used to format model attribute values into displayable texts.
 * This can be either an instance of [[Formatter]] or an configuration array for creating the [[Formatter]]
 * instance. If this property is not set, the "formatter" application component will be used.
 *
 * @author Moh Khoirul Anam <moh.khoirul.anaam@gmail.com>
 * @copyright 2014
 * @since 1
 */
class Excel extends \yii\base\Widget
{
    /**
     * @version 1.2.0
     */
    const VERSION = '1.2.0';
    /**
     * @var boolean to exit script after export.
     */
    public $exit = true;
    /**
     * @var string mode is an export mode or import mode. valid value are 'export' and 'import'.
     */
    public $mode = 'export';
    /**
     * @var boolean for set the export excel with multiple sheet.
     */
    public $isMultipleSheet = false;
    /**
     * @var array properties for set property on the excel object.
     */
    public $properties;
    /**
     * @var Model object or DataProvider object with much data.
     */
    public $models;
    /**
     * @var array columns to get the attributes from the model, this valid value only the exist attribute on the model.
     * If this is not set, then all attribute of the model will be set as columns.
     */
    public $columns = [];
    /**
     * @var array header to set the header column on first line. Set this if want to custom header.
     * If not set, the header will get attributes label of model attributes.
     */
    public $headers = [];
    /**
     * @var string|array name for file name to export or save.
     */
    public $fileName;
    /**
     * @var string save path is a directory to save the file or you can blank this to set the file as attachment.
     */
    public $savePath;
    /**
     * @var string format for excel to export. Valid value are 'Csv','Xml','Xlsx','Xls','Gnumeric','Html','Ods','Pdf','Slk'.
     */
    public $format;
    /**
     * @var boolean to set the title column on the first line.
     */
    public $setFirstTitle = true;
    /**
     * @var boolean to set the file excel to download mode.
     */
    public $asAttachment = true;
    /**
     * @var boolean to set the first record on excel file to a keys of array per line.
     * If you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
     */
    public $setFirstRecordAsKeys = true;
    /**
     * 储存文件的路径
     * @var null
     */
    public $storeFile = null;
    /**
     * @var int 第一条记录的索引
     */
    public $firstRecordIndex = 0;
    /**
     * @var boolean to set the sheet index by sheet name or array result if the sheet not only one.
     */
    public $setIndexSheetByName = false;
    /**
     * @var string sheetname to getting. This is only get the sheet with same name.
     */
    public $getOnlySheet;
    /**
     * @var boolean to set the import data will return as array.
     */
    public $asArray;
    /**
     * @var array to unread record by index number.
     */
    public $leaveRecordByIndex = [];
    /**
     * @var array to read record by index, other will leave.
     */
    public $getOnlyRecordByIndex = [];
    /**
     * @var array|Formatter the formatter used to format model attribute values into displayable texts.
     * This can be either an instance of [[Formatter]] or an configuration array for creating the [[Formatter]]
     * instance. If this property is not set, the "formatter" application component will be used.
     */
    public $formatter;
    /**
     * @var int
     */
    public $readStartRow = 1;
    /**
     * @var int
     */
    public $readEndRow = 0;
    /**
     * 是否丢弃作为键值的行的数据
     * @var bool
     */
    public $dropKeysRow = false;
    /**
     * executeColumns方法中迭代处理的callback
     * @var null
     */
    public $exportIterationCallback = null;
    /**
     * (non-PHPdoc)
     * @see \yii\base\BaseObject::init()
     */
    public function init()
    {
        parent::init();
        if ($this->formatter == null) {
            $this->formatter = \Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = \Yii::createObject($this->formatter);
        }
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }
    }

    /**
     * Setting data from models
     */
    public function executeColumns(&$activeSheet = null, $models, $columns = [], $headers = [])
    {
        if ($activeSheet == null) {
            $activeSheet = $this->activeSheet;
        }
        $hasHeader = false;
        $row = 1;
        $char = 26;
        foreach ($models as $model) {
            if (empty($columns)) {
                $columns = $model->attributes();
            }
            if ($this->setFirstTitle && !$hasHeader) {
                $isPlus = false;
                $colplus = 0;
                $colnum = 1;
                foreach ($columns as $key=>$column) {
                    $col = '';
                    if ($colnum > $char) {
                        $colplus += 1;
                        $colnum = 1;
                        $isPlus = true;
                    }
                    if ($isPlus) {
                        $col .= chr(64+$colplus);
                    }
                    $col .= chr(64+$colnum);
                    $header = '';
                    if (is_array($column)) {
                        if (isset($column['header'])) {
                            $header = $column['header'];
                        } elseif (isset($column['attribute']) && isset($headers[$column['attribute']])) {
                            $header = $headers[$column['attribute']];
                        } elseif (isset($column['attribute'])) {
                            $header = $model->getAttributeLabel($column['attribute']);
                        }
                    } else {
                        $header = $model->getAttributeLabel($column);
                    }
                    //$activeSheet->setCellValue($col.$row,$header);
                    $activeSheet->getCell($col.$row)->setValue($header);
                    $colnum++;
                }
                $hasHeader=true;
                $row++;
            }
            $isPlus = false;
            $colplus = 0;
            $colnum = 1;
            $currentValue = [];
            foreach ($columns as $key=>$column) {
                $col = '';
                if ($colnum > $char) {
                    $colplus++;
                    $colnum = 1;
                    $isPlus = true;
                }
                if ($isPlus) {
                    $col .= chr(64+$colplus);
                }
                $col .= chr(64+$colnum);
                if (is_array($column)) {
                    $column_value = $this->executeGetColumnData($model, $column);
                } else {
                    $column_value = $this->executeGetColumnData($model, ['attribute' => $column]);
                }
                //$activeSheet->setCellValue($col.$row,$column_value);
                $activeSheet->getCell($col.$row)->setValue($column_value);
                $currentValue[] = $column_value;
                $colnum++;
            }
            if($this->exportIterationCallback){
                call_user_func_array($this->exportIterationCallback, [
                    'row'=>$row,
                    'value' => $currentValue,
                ]);
            }
            $row++;
        }
    }

    /**
     * Setting label or keys on every record if setFirstRecordAsKeys is true.
     * @param array $sheetData
     * @return multitype:multitype:array
     */
    public function executeArrayLabel($sheetData)
    {
        if($this->isPageRead()){
            $keys = $this->readStoreFile();
            if(!$keys && $this->isBeginning()){
                $keys = ArrayHelper::remove($sheetData, $this->firstRecordIndex);
                $this->writeStoreFile($keys);
            }
        }else{
            $keys = ArrayHelper::remove($sheetData, $this->firstRecordIndex);
        }
        if(!$keys){
            throw new InvalidParamException('Unable to get valid keys');
        }
        $newData = [];

        foreach ($sheetData as $values)
        {
            $newData[] = array_combine($keys, $values);
        }
        //读取第一页的数据时，是否把作为数组键值的行去掉
        if($newData && $this->dropKeysRow && $this->isBeginning()){
            unset($newData[0]);
            return array_values($newData);
        }
        return $newData;
    }

    /**
     * 是否为开始
     * @return bool
     */
    public function isBeginning(){
        return ($this->readStartRow <= 1);
    }
    /**
     * 写入储存文件
     * @param $data
     * @return bool|int
     */
    public function writeStoreFile($data){
        if(is_array($data)){
            $data = Json::encode($data);
        }
        return file_put_contents($this->storeFile, $data);
    }
    /**
     * 读取储存文件
     * @return bool|mixed
     */
    public function readStoreFile(){
        $content = file_get_contents($this->storeFile);
        if(!$content){
            return false;
        }
        return json_decode($content, true);
    }
    /**
     * Leave record with same index number.
     * @param array $sheetData
     * @param array $index
     * @return array
     */
    public function executeLeaveRecords($sheetData = [], $index = [])
    {
        foreach ($sheetData as $key => $data)
        {
            if (in_array($key, $index))
            {
                unset($sheetData[$key]);
            }
        }
        return $sheetData;
    }

    /**
     * Read record with same index number.
     * @param array $sheetData
     * @param array $index
     * @return array
     */
    public function executeGetOnlyRecords($sheetData = [], $index = [])
    {
        foreach ($sheetData as $key => $data)
        {
            if (!in_array($key, $index))
            {
                unset($sheetData[$key]);
            }
        }
        return $sheetData;
    }

    /**
     * Getting column value.
     * @param Model $model
     * @param array $params
     * @return Ambigous <NULL, string, mixed>
     */
    public function executeGetColumnData($model, $params = [])
    {
        $value = null;
        if (isset($params['value']) && $params['value'] !== null) {
            if (is_string($params['value'])) {
                $value = ArrayHelper::getValue($model, $params['value']);
            } else {
                $value = call_user_func($params['value'], $model, $this);
            }
        } elseif (isset($params['attribute']) && $params['attribute'] !== null) {
            $value = ArrayHelper::getValue($model, $params['attribute']);
        }

        if (isset($params['format']) && $params['format'] != null)
            $value = $this->formatter()->format($value, $params['format']);

        return $value;
    }

    /**
     * Populating columns for checking the column is string or array. if is string this will be checking have a formatter or header.
     * @param array $columns
     * @throws InvalidParamException
     * @return multitype:multitype:array
     */
    public function populateColumns($columns = [])
    {
        $_columns = [];
        foreach ($columns as $key => $value)
        {
            if (is_string($value))
            {
                $value_log = explode(':', $value);
                $_columns[$key] = ['attribute' => $value_log[0]];

                if (isset($value_log[1]) && $value_log[1] !== null) {
                    $_columns[$key]['format'] = $value_log[1];
                }

                if (isset($value_log[2]) && $value_log[2] !== null) {
                    $_columns[$key]['header'] = $value_log[2];
                }
            } elseif (is_array($value)) {
                if (!isset($value['attribute']) && !isset($value['value'])) {
                    throw new InvalidParamException('Attribute or Value must be defined.');
                }
                $_columns[$key] = $value;
            }
        }

        return $_columns;
    }

    /**
     * Formatter for i18n.
     * @return Formatter
     */
    public function formatter()
    {
        if (!isset($this->formatter))
            $this->formatter = \Yii::$app->getFormatter();

        return $this->formatter;
    }

    /**
     * Setting header to download generated file xls
     */
    public function setHeaders()
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->getFileName() .'"');
        header('Cache-Control: max-age=0');
    }

    /**
     * Getting the file name of exporting xls file
     * @return string
     */
    public function getFileName()
    {
        $fileName = 'exports.xls';
        if (isset($this->fileName)) {
            $fileName = $this->fileName;
            if (strpos($fileName, '.xls') === false)
                $fileName .= '.xls';
        }
        return $fileName;
    }

    /**
     * Setting properties for excel file
     * @param \PhpOffice\PhpSpreadsheet\Spreadsheet $objectExcel
     * @param array $properties
     */
    public function properties(&$objectExcel, $properties = [])
    {
        foreach ($properties as $key => $value)
        {
            $keyname = "set" . ucfirst($key);
            $objectExcel->getProperties()->{$keyname}($value);
        }
    }

    /**
     * saving the xls file to download or to path
     */
    public function writeFile($sheet)
    {
        if (!isset($this->format))
            $this->format = 'Xlsx';
        $objectwriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($sheet, $this->format);
        $path = 'php://output';
        if (isset($this->savePath) && $this->savePath != null) {
            $path = $this->savePath . '/' . $this->getFileName();
        }
        $objectwriter->save($path);
        $this->exit && exit();
    }
    /**
     * to array
     * @param $activeSheet
     * @param null $nullValue
     * @param bool $calculateFormulas
     * @param bool $formatData
     * @param bool $returnCellRef
     * @return array
     */
    public function toArray($activeSheet, $nullValue = null, $calculateFormulas = true, $formatData = true, $returnCellRef = false){
        if($this->readStartRow && $this->readEndRow){
            $r = -1;
            $minCol = 'A';
            $maxCol = $activeSheet->getHighestColumn(); //总列数
            $maxCol++ ;
            $cellCollection = $activeSheet->getCellCollection();
            $parent = $activeSheet->getParent();
            $returnValue = [];
            for ($row = $this->readStartRow; $row <= $this->readEndRow; ++$row) {
                $rRef = ($returnCellRef) ? $row : ++$r;
                $c = -1;
                // Loop through columns in the current row
                for ($col = $minCol; $col != $maxCol; ++$col) {
                    $cRef = ($returnCellRef) ? $col : ++$c;
                    //    Using getCell() will create a new cell if it doesn't already exist. We don't want that to happen
                    //        so we test and retrieve directly against cellCollection
                    if ($cellCollection->has($col . $row)) {
                        // Cell exists
                        $cell = $cellCollection->get($col . $row);
                        if ($cell->getValue() !== null) {
                            if ($cell->getValue() instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
                                $returnValue[$rRef][$cRef] = $cell->getValue()->getPlainText();
                            } else {
                                if ($calculateFormulas) {
                                    $returnValue[$rRef][$cRef] = $cell->getCalculatedValue();
                                } else {
                                    $returnValue[$rRef][$cRef] = $cell->getValue();
                                }
                            }

                            if ($formatData) {
                                $style = $parent->getCellXfByIndex($cell->getXfIndex());
                                $returnValue[$rRef][$cRef] = \PhpOffice\PhpSpreadsheet\Style\NumberFormat::toFormattedString(
                                    $returnValue[$rRef][$cRef],
                                    ($style && $style->getNumberFormat()) ? $style->getNumberFormat()->getFormatCode() : \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL
                                );
                            }
                        } else {
                            // Cell holds a NULL
                            $returnValue[$rRef][$cRef] = $nullValue;
                        }
                    } else {
                        // Cell doesn't exist
                        $returnValue[$rRef][$cRef] = $nullValue;
                    }
                }
            }
            // Return
            return $returnValue;
        }else{
            return $activeSheet->toArray($nullValue, $calculateFormulas, $formatData, $returnCellRef);
        }
    }

    /**
     * 载入文件
     * @param $fileName
     * @return array|mixed
     */
    public static function getHighestRows($fileName, $format = null, $setIndexSheetByName = false){
        if (!$format)
            $format = \PhpOffice\PhpSpreadsheet\IOFactory::identify($fileName);
        $objectReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($format);
        $objectPhpExcel = $objectReader->load($fileName);
        $sheetCount = $objectPhpExcel->getSheetCount();
        $rows = [];
        if ($sheetCount > 1) {
            foreach ($objectPhpExcel->getSheetNames() as $sheetIndex => $sheetName) {
                $objectPhpExcel->setActiveSheetIndexByName($sheetName);
                $indexed = $setIndexSheetByName==true ? $sheetName : $sheetIndex;
                $rows[$indexed] = $objectPhpExcel->getActiveSheet()->getHighestRow();
                //每次读取文件后都要利用disconnectWorksheets方法清理phpspreadsheet的内存
                $objectPhpExcel->disconnectWorksheets();
            }
        }else{
            $rows = $objectPhpExcel->getActiveSheet()->getHighestRow();
        }
        return $rows;
    }
    /**
     * reading the xls file
     */
    public function readFile($fileName)
    {
        if (!isset($this->format))
            $this->format = \PhpOffice\PhpSpreadsheet\IOFactory::identify($fileName);
        $objectreader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($this->format);
        //2018-08-21增加按行读取的功能
        if($this->isPageRead()){
            $readFilter = new \sunmoon\phpspreadsheet\reader\Filter($this->readStartRow, $this->readEndRow);
            $objectreader->setReadDataOnly(true); //只读数据
            $objectreader->setReadFilter($readFilter);
        }
        $objectPhpExcel = $objectreader->load($fileName);
        $sheetCount = $objectPhpExcel->getSheetCount();
        $sheetDatas = [];
        if ($sheetCount != 1) {
            throw new \yii\base\InvalidValueException('Sheet count is incorrect, it can only be 1');
        } else {
            $sheetDatas = $this->toArray($objectPhpExcel->getActiveSheet()); //add on
            if ($this->setFirstRecordAsKeys) {
                $sheetDatas = $this->executeArrayLabel($sheetDatas);
            }
            if (!empty($this->getOnlyRecordByIndex)) {
                $sheetDatas = $this->executeGetOnlyRecords($sheetDatas, $this->getOnlyRecordByIndex);
            }
            if (!empty($this->leaveRecordByIndex)) {
                $sheetDatas = $this->executeLeaveRecords($sheetDatas, $this->leaveRecordByIndex);
            }
            //每次读取文件后都要利用disconnectWorksheets方法清理phpspreadsheet的内存
            $objectPhpExcel->disconnectWorksheets();
        }
        return $sheetDatas;
    }

    /**
     * @see \yii\base\Widget::run()
     * @return array|string
     * @throws InvalidConfigException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function run()
    {
        if ($this->mode == 'export')
        {
            $sheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            if (!isset($this->models))
                throw new InvalidConfigException('Config models must be set');

            if (isset($this->properties))
            {
                $this->properties($sheet, $this->properties);
            }

            if ($this->isMultipleSheet) {
                $index = 0;
                $worksheet = [];
                foreach ($this->models as $title => $models) {
                    $sheet->createSheet($index);
                    $sheet->getSheet($index)->setTitle($title);
                    $worksheet[$index] = $sheet->getSheet($index);
                    $columns = isset($this->columns[$title]) ? $this->columns[$title] : [];
                    $headers = isset($this->headers[$title]) ? $this->headers[$title] : [];
                    $this->executeColumns($worksheet[$index], $models, $this->populateColumns($columns), $headers);
                    $index++;
                }
            } else {
                $worksheet = $sheet->getActiveSheet();
                $this->executeColumns($worksheet, $this->models, isset($this->columns) ? $this->populateColumns($this->columns) : [], isset($this->headers) ? $this->headers : []);
            }

            if ($this->asAttachment) {
                $this->setHeaders();
            }
            $this->writeFile($sheet);
        }
        elseif ($this->mode == 'import')
        {
            //验证读取的行数是否正确
            $this->validateReadRow();
            //把首行作为数组的key并且分页读取就必须设置storeFile
            if($this->setFirstRecordAsKeys === true && !$this->isPageRead()){
                if(!$this->storeFile){
                    throw new InvalidConfigException('Please set storeFile');
                }
                if(!file_exists($this->storeFile)){
                    throw new InvalidConfigException('The store file is not exists');
                }
            }
            if (is_array($this->fileName)) {
                $datas = [];
                foreach ($this->fileName as $key => $filename) {
                    $datas[$key] = $this->readFile($filename);
                }
                return $datas;
            } else {
                return $this->readFile($this->fileName);
            }
        }
    }

    /**
     * 是不是分页读取
     * @return bool
     */
    public function isPageRead(){
        if($this->readStartRow >= 0 && $this->readEndRow > 0){
            return true;
        }
        return false;
    }
    /**
     * 验证读取的行数
     * @return bool
     * @throws InvalidConfigException
     */
    public function validateReadRow(){
        if($this->readEndRow > 0 && $this->readEndRow < $this->readStartRow){
            throw new InvalidConfigException('"readEndRow" must be greater than "readStartRow"');
        }
        return true;
    }
    /**
     * Exporting data into an excel file.
     *
     * ~~~
     *
     * \sunmoon\phpspreadsheet\Excel::export([
     * 		'models' => $allModels,
     * 		'columns' => ['column1','column2','column3'],
     * 		//without header working, because the header will be get label from attribute label.
     * 		'header' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
     * ]);
     *
     * ~~~
     *
     * New Feature for exporting data, you can use this if you familiar yii gridview.
     * That is same with gridview data column.
     * Columns in array mode valid params are 'attribute', 'header', 'format', 'value', and footer (TODO).
     * Columns in string mode valid layout are 'attribute:format:header:footer(TODO)'.
     *
     * ~~~
     *
     * \sunmoon\phpspreadsheet\Excel::export([
     *  	'models' => Post::find()->all(),
     *     	'columns' => [
     *     		'author.name:text:Author Name',
     *     		[
     *     				'attribute' => 'content',
     *     				'header' => 'Content Post',
     *     				'format' => 'text',
     *     				'value' => function($model) {
     *     					return ExampleClass::removeText('example', $model->content);
     *     				},
     *     		],
     *     		'like_it:text:Reader like this content',
     *     		'created_at:datetime',
     *     		[
     *     				'attribute' => 'updated_at',
     *     				'format' => 'date',
     *     		],
     *     	],
     *     	'headers' => [
     *     		'created_at' => 'Date Created Content',
     * 		],
     * ]);
     *
     * ~~~
     *
     * @param array $config
     * @return string
     */
    public static function export($config=[])
    {
        $config = ArrayHelper::merge(['mode' => 'export'], $config);
        return self::widget($config);
    }

    /**
     * Import file excel and return into an array.
     *
     * ~~~
     *
     * $data = \sunmoon\phpspreadsheet\Excel::import($fileName, ['setFirstRecordAsKeys' => true]);
     *
     * ~~~
     *
     * @param string!array $fileName to load.
     * @param array $config is a more configuration.
     * @return string
     */
    public static function import($fileName, $config=[])
    {
        $config = ArrayHelper::merge([
            'mode' => 'import',
            'fileName' => $fileName,
            'asArray' => true,
        ], $config);
        return self::widget($config);
    }

    /**
     * 预载入文件
     * @param $fileName
     * @param array $config
     * @return string
     */
    public static function load($fileName, $config = []){
        $config = ArrayHelper::merge(['mode' => 'load', 'fileName' => $fileName, 'asArray' => true], $config);
        return self::widget($config);
    }
    /**
     * @param array $config
     * @return string
     * @throws InvalidConfigException
     */
    public static function widget($config = [])
    {
        if ($config['mode'] == 'import' && !isset($config['asArray'])) {
            $config['asArray'] = true;
        }

        if (isset($config['asArray']) && $config['asArray']==true)
        {
            $config['class'] = get_called_class();
            $widget = \Yii::createObject($config);
            return $widget->run();
        } else {
            return parent::widget($config);
        }
    }
}