<?php

namespace core\admin\controller;

class FiledownloadController extends BaseAdmin {

    protected $phpExel;
    protected $catalog;

    protected function inputData(){

        ini_set('mysql.connect_timeout', 200);
        set_time_limit(0);

        parent::inputData();

        include_once ($_SERVER['DOCUMENT_ROOT'] . PATH .'libraries/PHPExcel.php');
        $this->phpExel = new \PHPExcel();

        /*Установка активного листа*/
        $this->phpExel->setActiveSheetIndex(0);
        $activeSheet = $this->phpExel->getActiveSheet();
        /*Установка активного листа*/

        /*Создание дополнитьельного листа*/

        //$this->phpExel->createSheet(1);

        /*Создание дополнитьельного листа*/

        /*Ориентация листа*/
        $activeSheet->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        /*Ориентация листа*/

        /*Размер листа для печати*/
        $activeSheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        /*Размер листа для печати*/

        /*Поля документа*/
        $activeSheet->getPageMargins()->setTop(0.5);
        $activeSheet->getPageMargins()->setRight(0.75);
        $activeSheet->getPageMargins()->setBottom(0.5);
        $activeSheet->getPageMargins()->setLeft(0.75);
        /*Поля документа*/

        /*Название листа*/
        $activeSheet->setTitle('Список товаров');
        /*Название листа*/

        /*Фиксированная строка внизу документа*/
        $activeSheet->getHeaderFooter()->setOddFooter('&L&B'.$activeSheet->getTitle().'&RСтраница &P из &N');
        /*Фиксированная строка внизу документа*/

        /*Установка шрифта по умолчанию*/
        $this->phpExel->getDefaultStyle()->getFont()->setName('Arial');
        /*Установка шрифта по умолчанию*/

        /*Размер шрифта*/
        $this->phpExel->getDefaultStyle()->getFont()->setSize(10);
        /*Размер шрифта*/

        /*Ширина столбцов*/
        $activeSheet->getColumnDimension('A')->setWidth(50);
        $activeSheet->getColumnDimension('B')->setWidth(10);
        $activeSheet->getColumnDimension('C')->setWidth(20);
        $activeSheet->getColumnDimension('D')->setWidth(20);
        $activeSheet->getColumnDimension('E')->setWidth(20);
        /*Ширина столбцов*/

        /*Объединение яцеек*/
        $activeSheet->mergeCells('A1:E1');
        /*Объединение яцеек*/

        /*Высотя рядов*/
        $activeSheet->getRowDimension('1')->setRowHeight(60);
        /*Высотя рядов*/

        /*Добавление текстовых данных в ячейку*/

        $this->parameters['project'] = $this->clearStr(base64_decode(urldecode($this->parameters['project'])));

        $activeSheet->setCellValue('A1', 'Список товаров');
        $activeSheet->getStyle('A1')->getAlignment()->setWrapText(true);
        /*Добавление текстовых данных в ячейку*/

        /*Стили для ячейки*/
        $style_header = array(
                            'font' => array(
                                            'bold' => true,
                                            'name' => 'Arial',
                                            'size' => 20,
                                            'color' => array(
                                                            'rgb' => 'ffffff'
                                                            ),
                                            ),
                            'alignment' => array(
                                                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                ),
                            'fill' => array(
                                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                            'color' => array('rgb' => '2e778f')
                                            ),
                            );

        $style_slogan = array(
            'font' => array(
                'italic' => true,
                'name' => 'Arial',
                'size' => 11,
                'color' => array(
                    'rgb' => 'ffffff'
                ),
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '2e778f')
            ),
            'borders' => array(
                                'bottom' => array(
                                                'style' => \PHPExcel_Style_Border::BORDER_THICK
                                                )
                                )
        );

        $style_date = array(

            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'cfcfcf')
            ),
            'borders' => array(
                'bottom' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_NONE
                )
            )
        );

        $style_date1 = array(

            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'cfcfcf')
            ),
            'borders' => array(
                'left' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_NONE
                )
            )
        );

        $style_head_content = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10,
                'color' => array(
                'rgb' => 'ffffff'
                ),
                'bold' => true,
                'italic' => true
             ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '2e778f')
            ),
        );

        $style_content = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10,
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'cfcfcf')
            ),
        );

        $style_parameters = array(
                    'alignment' => array(
                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'wrap' => true
                    )
            );

        $style_wrap = array(

            'borders' => array(
                'allborders' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '696969'),
                    ),
                    'outline' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THICK
                                )
            )
        );

        $activeSheet->getStyle('A1:E1')->applyFromArray($style_header);
        $activeSheet->getStyle('A2:E2')->applyFromArray($style_slogan);
        $activeSheet->getStyle('A4:D4')->applyFromArray($style_date);
        $activeSheet->getStyle('E4')->applyFromArray($style_date1);
        $activeSheet->getStyle('A6:E6')->applyFromArray($style_head_content);

        /*СТили для ячейки*/

        /*Объединение яцеек*/
        $activeSheet->mergeCells('A2:E2');
        /*Объединение яцеек*/

        /*Добавление текстовых данных в ячейку*/
        $activeSheet->setCellValue('A2', '');
        /*Добавление текстовых данных в ячейку*/

        /*Объединение яцеек*/
        $activeSheet->mergeCells('A4:D4');
        /*Объединение яцеек*/

        /*Добавление текстовых данных в ячейку*/
        $activeSheet->setCellValue('A4', 'Дата создания');
        /*Добавление текстовых данных в ячейку*/

        /*Добавление текстовых данных в ячейку*/
        $date = date("d-m-Y");
        $activeSheet->setCellValue('E4', $date);
        /*Добавление текстовых данных в ячейку*/

        /*Установка типа данных для ячейки*/
        $activeSheet->getStyle('D4')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
        /*Установка типа данных для ячейки*/

        /*Добавление текстовых данных в ячейку*/
        $activeSheet->setCellValue('A6', 'Название');
        /*Добавление текстовых данных в ячейку*/

        /*Добавление текстовых данных в ячейку*/
        $activeSheet->setCellValue('B6', 'Размер');
        /*Добавление текстовых данных в ячейку*/

        /*Добавление текстовых данных в ячейку*/
        $activeSheet->setCellValue('C6', 'Артикул');
        $activeSheet->getStyle('C6')->getAlignment()->setWrapText(true);
        $activeSheet->getRowDimension('C6')->setRowHeight(25);
        /*Добавление текстовых данных в ячейку*/

        /*Добавление текстовых данных в ячейку*/
        $activeSheet->setCellValue('D6', 'Категория');
        $activeSheet->getStyle('D6')->getAlignment()->setWrapText(true);
        $activeSheet->getRowDimension('D6')->setRowHeight(25);
        /*Добавление текстовых данных в ячейку*/

        /*Добавление текстовых данных в ячейку*/
        $activeSheet->setCellValue('E6', 'Производитель');
        $activeSheet->getStyle('E6')->getAlignment()->setWrapText(true);
        $activeSheet->getRowDimension('E6')->setRowHeight(25);
        /*Добавление текстовых данных в ячейку*/

        /*Получаем данные для запроса*/

        $data = $this->model->get('good', [
            'fields' => ['name', 'size', 'article_manufact'],
            'where' => ['activ' => 1],
            'join' => [
                'categories' => [
                    'fields' => ['name as c_name'],
                    'on' => ['parent_id', 'id']
                ],
                'manufacturers' => [
                    'fields' => ['name as m_name'],
                    'on' => [
                        'table' => 'good',
                        'fields' => ['manufacturers', 'id']
                    ]
                ]
            ],
            'order' => [5]
        ]);

        $row_start = 6;
        $current_row = $row_start;

        foreach($data as $value){
            $current_row++;

            $activeSheet->setCellValue('A'.$current_row, $value['name']);
            $activeSheet->setCellValue('B'.$current_row, $value['size']);
            $activeSheet->setCellValue('C'.$current_row, $value['article_manufact']);
            $activeSheet->setCellValue('D'.$current_row, $value['c_name']);
            $activeSheet->setCellValue('E'.$current_row, $value['m_name']);

            $activeSheet->getStyle('A'.$current_row)->getAlignment()->setWrapText(true);
            $activeSheet->getStyle('B'.$current_row)->getAlignment()->setWrapText(true);
            $activeSheet->getStyle('C'.$current_row)->getAlignment()->setWrapText(true);
            $activeSheet->getStyle('D'.$current_row)->getAlignment()->setWrapText(true);
            $activeSheet->getStyle('E'.$current_row)->getAlignment()->setWrapText(true);

            $activeSheet->getRowDimension("$current_row")->setRowHeight(60);
            $activeSheet->getStyle('A'.$current_row)->applyFromArray($style_content);
            $activeSheet->getStyle('B'.$current_row)->applyFromArray($style_parameters);
            $activeSheet->getStyle('C'.$current_row)->applyFromArray($style_parameters);
            $activeSheet->getStyle('D'.$current_row)->applyFromArray($style_parameters);
            $activeSheet->getStyle('E'.$current_row)->applyFromArray($style_parameters);

        }

        /*Заполняем файл данными из БД*/

        $activeSheet->getStyle('A'.$row_start.':E'.$current_row)->applyFromArray($style_wrap);
    }

    protected function outputData(){
        /*Отдача файла на скачивание*/

        $fileType = [
            'excel' => [
                'c-t' => 'application/vnd.ms-excel',
                'ext' => 'xls',
                'render' => 'Excel5'
            ],
            'pdf' => [
                'c-t' => 'application/pdf',
                'ext' => 'pdf',
                'render' => 'PDF'
            ],
            'csv' => [
                'c-t' => 'text/csv',
                'ext' => 'csv',
                'render' => 'CSV'
            ],
        ];

        $type = $this->parameters['alias'] ?: 'excel';

        if($this->parameters['alias'] === 'pdf'){
            $rendererName = \PHPExcel_Settings::PDF_RENDERER_TCPDF;
            $rendererLibraryPath = $_SERVER['DOCUMENT_ROOT'] . PATH . 'libraries/PHPExcel/tcpdf';

            if (!\PHPExcel_Settings::setPdfRenderer(
                $rendererName,
                $rendererLibraryPath
            )) {
                die(
                    'NOTICE: Please set the ' . $rendererName . ' and ' . $rendererLibraryPath . ' values' .
                    '<br />' .
                    'at the top of this script as appropriate for your directory structure'
                );
            }
        }

        header('Content-Type:' . $fileType[$type]['c-t']);
        header('Content-Disposition:attachment;filename="sites_' . date("d-m-Y") . '.' . $fileType[$type]['ext'] . '"');

        $objectWriter = \PHPExcel_IOFactory::createWriter($this->phpExel, $fileType[$type]['render']);

        $objectWriter->save('php://output');
        /*Отдача файла на скачивание*/

        /*Сохранение документа в файл*/
        /*  $objectWriter = PHPExcel_IOFactory::createWriter($this->phpExel, 'Excel5');
            $objectWriter->save('file.xls');

            $this->redirect(PATH);*/
        /*Сохранение документа в файл*/
        exit();
    }
}
