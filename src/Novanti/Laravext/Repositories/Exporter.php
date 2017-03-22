<?php namespace Novanti\Laravext\Repositories;

use Illuminate\Http\Request;
use PHPExcel_IOFactory;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Border;
use PHPExcel;
use Carbon\Carbon;

class Exporter
{
    public $sort;
    public $filters;
    public $ignoreFilter;
    public $query;
    public $columns;
    public $documentType;
    public $documentTitle;
    public $documentFontSize;
    public $documentOrientation;
    protected $baseQuery;
    protected $columnDataType = [];
    protected $columnsDataIndex = [];


    public function __construct(Request $request)
    {
        $this->sort = json_decode($request->get('sort'), true);
        $this->filters = json_decode($request->get('filters'), true);
        $this->ignoreFilter = $request->get('ignore_filter');
        $this->query = $request->get('query');
        $this->columns = json_decode($request->get('columns'), true);
        $this->documentType = $request->get('document_type');
        $this->documentTitle = !empty($request->get('document_title')) ? $request->get('document_title') : trans('laravext::laravext.default_document_name');
        $this->documentFontSize = !empty($request->get('document_font_size')) ? $request->get('document_font_size') : config('laravext.default_document_font_size');
        $this->documentOrientation = !empty($request->get('document_orientation')) ? $request->get('document_orientation') : config('laravext.default_document_orientation');
        if (is_array($this->columns)) {
            foreach ($this->columns as $column) {
                $this->columnsDataIndex[] = $column['dataIndex'];
                if (isset($column['dataType'])) {
                    $this->columnDataType[$column['dataIndex']] = $column['dataType'];
                }
            }
        }


    }

    public function setBaseQuery($baseQuery)
    {
        $this->baseQuery = $baseQuery;
        return $this;
    }

    public function getBaseQuery()
    {
        return $this->baseQuery;
    }

    public function buildViewVars()
    {
        return [
            'documentTitle' => $this->documentTitle,
            'documentFontSize' => $this->documentFontSize,
            'columns' => $this->columns,
            'rows' => $this->getBaseQuery()->get(),
            'columnsDataType' => $this->columnDataType,
            'columnsDataIndex' => $this->columnsDataIndex,
            'stylesheetUrl' => config('laravext.pdf_stylesheet_url')
        ];
    }

    public function exportToXls()
    {
        $fileName = studly_case($this->documentTitle) . '_' . date('Y.m.d.H.i');
        if (config('laravext.xls_config.template_file')) {
            $objReader = PHPExcel_IOFactory::createReader('Excel5');
            $objPHPExcel = $objReader->load(config('laravext.xls_config.template_file'));
        } else {
            $objPHPExcel = new PHPExcel();
        }

        $objPHPExcel->getActiveSheet()->setTitle(config('laravext.xls_config.sheet_title'));
        $objPHPExcel->getActiveSheet()->setShowGridlines(false);
        $objPHPExcel->setActiveSheetIndex(0);
        $headerStyleArray = array(
            'font' => array(
                'bold' => true
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'cccccc')
            )
        );
        for ($index = 0; $index < count($this->columns); $index++) {
            $col = $this->columns[$index];
            $colLetter = $objPHPExcel->setActiveSheetIndex(0)->getCell()->stringFromColumnIndex($index);
            if ($index === 0) {
                $firstCell = "A" . (config('laravext.xls_config.start_row_index') - 1);
            }
            if ($index === (count($this->columns) - 1)) {
                $lastColLetter = $colLetter;
            }
            if ($col['dataIndex']) {

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colLetter . (config('laravext.xls_config.start_row_index') - 1), $col['text']);
                $objPHPExcel->getActiveSheet()->getColumnDimension($colLetter)->setAutoSize(true);

                $objPHPExcel->getActiveSheet()->getStyle($colLetter . (config('laravext.xls_config.start_row_index') - 1))
                    ->applyFromArray($headerStyleArray);

            }
        }
        $rows = $this->getBaseQuery()->get();

        $rowIndex = config('laravext.xls_config.start_row_index');
        $rowsArray = $rows->toArray();

        for ($i = 0; $i < $rows->count(); $i++) {
            $columnIndex = 0;

            for ($j = 0; $j < count($this->columns); $j++) {

                $col = $this->columns[$j];
                if (isset($col['dataIndex'])) {

                    $row = $rowsArray[$i][$col['dataIndex']];
                    switch ($col['dataType']) {
                        case 'money':
                            $row = number_format($row, 2, ',', '.');
                            break;
                        case 'date':
                            if ($row) {
                                $row = Carbon::parse($row)->format('d/m/Y');
                            }
                            break;
                        case 'datetime':
                            $row = Carbon::parse($row)->format('d/m/Y H:i');
                            break;
                        case 'boolean':
                            $row = (!empty($row)) ? trans('laravext::laravext.true') : trans('laravext::laravext.false');
                            break;
                    }

                    $columnLetter = $objPHPExcel->setActiveSheetIndex(0)->getCell()->stringFromColumnIndex($columnIndex);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnLetter . $rowIndex, $row);
                    $columnIndex++;
                }
            }
            $rowIndex++;
        }

        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $objPHPExcel->getActiveSheet()->getStyle($firstCell.':'.$lastColLetter.($rowIndex -1))->applyFromArray($styleArray);
        unset($styleArray);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;

    }

    public function exportToHtml()
    {
        return view(config('laravext.default_template_view'), $this->buildViewVars());
    }

    public function exportToPdf()
    {
        $pdf = new \Novanti\LaravelPDF\PDF(config('laravext.wkhtmltopdf_bin'), storage_path());
        $pdf->loadView(config('laravext.default_template_view'), $this->buildViewVars())
            ->pageSize('A4')
            ->orientation($this->documentOrientation)
            //TODO add header and footer support
            // ->headerHtml(URL::route('report_header'))
            // ->footerHtml(URL::route('report_footer'))
        ;

        return $pdf->download(studly_case($this->documentTitle) . '_' . date('Y.m.d.H.i') . ".pdf");
    }

}