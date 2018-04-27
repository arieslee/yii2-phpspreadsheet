# 说明

Yii2 处理 Excel 文件， 修改自[moonlandsoft/yii2-phpexcel](https://github.com/moonlandsoft/yii2-phpexcel)。

 修改项:
    
    1. 由于phpoffice/phpexcel已经弃用，更新为phpoffice/phpspreadsheet
    2. 导出文件后退出脚本导致程序后续代码无法执行的问题

## 安装

 1. 安装包文件

	``` bash
	$ composer require sunmoon/yii2-phpspreadsheet '*'
	```

## 使用

1. 代码中使用参见 [moonlandsoft/yii2-phpexcel](https://github.com/moonlandsoft/yii2-phpexcel)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
