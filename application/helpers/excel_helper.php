<?php
require_once 'PHPExcel-1.8.0/PHPExcel.php';

function getExcel($titles, $widths, $list, $fn, $conv = false)
{
    $chars = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
        'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
        'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
        'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ'

    );
    $objExcel = new PHPExcel();
    $objProps = $objExcel->getProperties();
    $objProps->setCreator("");
    $objProps->setLastModifiedBy("");
    $objProps->setTitle("");
    $objProps->setSubject("");
    $objProps->setDescription("");
    $objProps->setKeywords("");
    $objProps->setCategory("");

    $objExcel->setActiveSheetIndex(0);

    $objActSheet = $objExcel->getActiveSheet();

    //设置当前活动sheet的名称
    $objActSheet->setTitle('Sheet1');
    $objStyleA5 = $objActSheet->getStyle('A1');
    $objFontA5 = $objStyleA5->getFont();
    $objFontA5->setName('宋体');
    $objActSheet->duplicateStyle($objStyleA5, 'A1:' . $chars[count($titles) - 1] . '' . (count($list) + 2));
    $objFontA5->setBold(true);
    $objActSheet->duplicateStyle($objStyleA5, 'A1:' . $chars[count($titles) - 1] . '1');

    foreach ($widths as $n => $width) {
        $objActSheet->getColumnDimension($chars[$n])->setWidth($width);
    }

    //*************************************
    //设置单元格内容
    //
    //由PHPExcel根据传入内容自动判断单元格内容类型
    foreach ($titles as $n => $title) {
        if ($conv) {
            $title = iconv("gbk", "utf-8//ignore", $title);
        }
        $objActSheet->setCellValue($chars[$n] . '1', $title . '');
    }
    foreach ($list as $n1 => $item) {
        $n2 = 0;
        foreach ($item as $item2) {
            if ($conv) {
                $item2 = iconv("gbk", "utf-8//ignore", $item2);
            }
            if (preg_match("/^#[0-9A-Fa-f]{6}\|/", $item2)) {
                $color = substr($item2, 0, 7);
                $item2 = substr($item2, 8);
                $objStyle = $objActSheet->getStyle($chars[$n2] . '' . ($n1 + 2));
                $objFont = $objStyle->getFont();
                $objFont->getColor()->setARGB('FF' . substr($color, 1));
            }
            $objActSheet->setCellValueExplicit($chars[$n2] . '' . ($n1 + 2), $item2 . '', PHPExcel_Cell_DataType::TYPE_STRING);
            $n2++;
        }
    }
    $objWriter = new PHPExcel_Writer_Excel5($objExcel);
    $objWriter->save($fn);
}


function readexcel($filePath)
{
    if (stristr($filePath, '.xlsx')) $PHPReader = new PHPExcel_Reader_Excel2007();
    else $PHPReader = new PHPExcel_Reader_Excel5();
    if (!$PHPReader->canRead($filePath)) {
        return false;
    }

    $PHPExcel = $PHPReader->load($filePath);
    $currentSheet = $PHPExcel->getSheet(0);
    /**取得一共有多少列*/
    $allColumn = $currentSheet->getHighestColumn();
    /**取得一共有多少行*/
    $allRow = $currentSheet->getHighestRow();
    $all = array();
    for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
        $flag = 0;
        $col = array();
        for ($currentColumn = 'A'; ord($currentColumn) <= ord($allColumn); $currentColumn++) {
            $address = $currentColumn . $currentRow;
            $string = $currentSheet->getCell($address)->getFormattedValue();
            $col[$flag] = $string;
            $flag++;
        }
        $all[] = $col;
    }
    return $all;
}

/* getExcel(
array('姓名', '号码', '群组', '备注'),
array(30, 20, 30, 30),
array(
	array('郭宇航', '18988651520', '广州电信', '的士速递'),
	array('各地方', '189885651520', '各地方', '的士速递'),
	array('回复', '1898862520', '放到山电信', '的士速递'),
	array('几个号', '1898451520', '混个', '的士速递'),
), 'fdsfd.xls'); */
//header("Location: fdsfd.xls?".time());