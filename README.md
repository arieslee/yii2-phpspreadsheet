# 说明

Yii2 处理 Excel 文件， 修改自[moonlandsoft/yii2-phpexcel](https://github.com/moonlandsoft/yii2-phpexcel)。

 修改项:
    
    1. 由于phpoffice/phpexcel已经弃用，更新为phpoffice/phpspreadsheet
    2. 导出文件后退出脚本导致程序后续代码无法执行的问题
    3. 增加按行读取excel的功能 1.0.8
    4. 提供获取文件总行的的功能 1.0.8
    5. 修复 BUG : 分页导入数据时，读取第二页时，无法获取第一行的数据作为数组的键值
    6. 增加配置项 storeFile ,作为临时储存第一行的数据作为键值，默认为null
    7. 增加配置项 dropKeysRow true/false，读取第一页的数据时，是否作为数组键值的行从数组中丢弃，默认为false
    8. 增加验证功能，readEndRow不能小于readStartRow

## 安装

 1. 安装包文件

	``` bash
	$ composer require sunmoon/yii2-phpspreadsheet '*'
	```

## 使用

1. 代码中使用参见 [moonlandsoft/yii2-phpexcel](https://github.com/moonlandsoft/yii2-phpexcel)

2. 如何按行读取excel

```
use sunmoon\phpspreadsheet\Excel as ExcelHelper;
public function actionReadExcel(){
    $fileName = 'demo.xlsx';
    //从第1行读取到第7行
    $data = ExcelHelper::import($fileName, ['readStartRow'=>1, 'readEndRow'=>7, ]);
    return $data;
}
```

3. 如何按行读取excel的总行数

```
use sunmoon\phpspreadsheet\Excel as ExcelHelper;
public function actionReadExcelRows(){
    $fileName = 'demo.xlsx';
    $data = ExcelHelper::getHighestRows($fileName);
    return $data;
}
```
## 其他

1. 从phpexcel迁移到phpspreadsheet的注意事项，[migration-from-PHPExcel](https://phpspreadsheet.readthedocs.io/en/develop/topics/migration-from-PHPExcel/)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
