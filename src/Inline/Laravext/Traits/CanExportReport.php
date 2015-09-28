<?php namespace Inline\Laravext\Traits;

use Illuminate\Http\Request;

trait CanExportReport
{
    public function getBaseQuery()
    {
        $query = $this->_model->baseQuery();
        $this->applySorterToQuery($query);
        $this->applyFilterToQuery($query);
        $this->applySearchToQuery($query);
        return $query;
    }

    public function exportToDocument(Request $request)
    {
        //TODO Validate if exporter exists

        $exporter = $this->exporter->setBaseQuery($this->getBaseQuery());
        switch ($exporter->documentType) {
            case "pdf":
                return $exporter->exportToPdf();
                break;
            case "xls":
                return $exporter->exportToXls();
                break;
            default:
                return $exporter->exportToHtml();
                break;
        }
    }


}