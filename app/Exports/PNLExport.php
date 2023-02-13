<?php 
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Http\Controllers\Traits\IntranetTrait;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
// use Maatwebsite\Excel\Concerns\WithDefaultStyles;

class PNLData implements FromView, WithTitle, WithEvents
{	
	use IntranetTrait;
	// Return default data if there's no data
	protected $data_view = 'exports.pnl';
	protected $data_cost = [];
	protected $data_title = 'Workbook';

	public function __construct($view, $title, $data_cost)
    {
        $this->data_view = $view;
        $this->data_title = $title;
        $this->data_cost = $data_cost;
    }

    public function title(): string
    {
        return $this->data_title;
    }

    // public function columnFormats(): array
    // {
    //     return [
    //         'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
    //         'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
    //         'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
    //         'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
    //         'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
    //         'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
    //         'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
    //         'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
    //     ];
    // }

    function checkMergedCell($sheet, $cell) {
    	try {
		    foreach ($sheet->getMergeCells() as $cells) {
		        if ($cell->isInRange($cells)) {
		            // Cell is merged!
		            return true;
		        }
		    }
		    return false;
		} catch(\Exception $e){
			return true;
		}
	}

    public function registerEvents(): array
	{
		$sheet_obj = $this;
	    return [
	        AfterSheet::class=> function(AfterSheet $event) use ($sheet_obj) {
	        	// Border
				$styleArray = [
				    'borders' => [
				        'allBorders' => [
				            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
				            'color' => ['rgb' => 'D5D5D5'],
				        ],
				    ],
				];
				$column_already_formatted = [];
				$exclude_header_row = [1,2,3];
	            foreach ($event->sheet->getDelegate()->getRowIterator() as $row) {
	            	// Row
				    $cellIterator = $row->getCellIterator();
				    $cellIterator->setIterateOnlyExistingCells(FALSE);
				    foreach ($cellIterator as $cell) {
				    	if(!in_array($cell->getColumn(), $column_already_formatted)){
				    		// Set autosize for each column
				    		$event->sheet->getColumnDimension(sprintf('%s', $cell->getColumn()))->setAutoSize(true);
				    		array_push($column_already_formatted, $cell->getColumn());
				    	}

				    	// If value type is number align right
				    	if(count(explode('%', $cell->getValue())) > 1){
					    	$check_numeric_percentage = isset(explode('%', $cell->getValue())[0]) ? str_replace(',','', explode('%', $cell->getValue())[0]) : '';
					    	if(preg_match("/^-?[0-9,?.]+$/", $cell->getValue()) || is_numeric($check_numeric_percentage)){
					    		$cell->setValue($check_numeric_percentage/100);
								$event->sheet->getDelegate()->getStyle(sprintf('%s', $cell->getParent()->getCurrentCoordinate()))
					    		->getNumberFormat()->setFormatCode('0.00%');
					    		$event->sheet->getDelegate()->getStyle(sprintf('%s', $cell->getParent()->getCurrentCoordinate()))
			              		->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
					    	}
				    	} else {
				    		// Eliminate alfabet, search only cell with number
					    	$check_number = preg_replace('/\D?,/', '', $cell->getValue());
					    	if(is_numeric($check_number)){
					    		$cell->setValue($check_number);
					    		$event->sheet->getDelegate()->getStyle(sprintf('%s', $cell->getParent()->getCurrentCoordinate()))
					    		->getNumberFormat()->setFormatCode('#,##0');
					    		$event->sheet->getDelegate()->getStyle(sprintf('%s', $cell->getParent()->getCurrentCoordinate()))
			              		->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
					    	}
				    	}

				    	// Styling Each column and row 
		            	$event->sheet->getDelegate()->getStyle(sprintf('%s', $cell->getParent()->getCurrentCoordinate()))->getFont()->setName('Arial Unicode MS');
					    $event->sheet->getDelegate()->getStyle(sprintf('%s', $cell->getParent()->getCurrentCoordinate()))->getFont()->setSize(10);
					    $event->sheet->getDelegate()->getStyle(sprintf('%s', $cell->getParent()->getCurrentCoordinate()))
              			->getFont()->getColor()->setRGB('000000');
					    $event->sheet->getDelegate()->getStyle(sprintf('%s', $cell->getParent()->getCurrentCoordinate()))->getFont()->setItalic(false);
					    $event->sheet->getDelegate()->getStyle(sprintf('%s', $cell->getParent()->getCurrentCoordinate()))->getFont()->setBold(false);
					    // Exclude border in header
					    if(!in_array($row->getRowIndex(), $exclude_header_row)){
					    	$event->sheet->getDelegate()->getStyle(sprintf('%s', $cell->getParent()->getCurrentCoordinate()))->applyFromArray($styleArray);
						}

				    	// Cell with text total and make color darker
				    	if($this->startsWith(strtolower($cell->getValue()), 'total')){
				    		$cellIndexRange = sprintf("A%s:Q%s",$row->getRowIndex(), $row->getRowIndex());
				    		$event->sheet->getDelegate()->getStyle($cellIndexRange)
              				->getFill()->applyFromArray(['fillType' => 'solid', 'rotation' => 0, 'color' => ['rgb' => 'EAEAEA'],]);
				    	}

				    	if($cell->getParent() != null){
				    		if($sheet_obj->checkMergedCell($event->sheet, $cell) == false && strlen(trim($cell->getValue())) <= 0){
					    		$event->sheet->getDelegate()->getStyle(sprintf('%s', $cell->getParent()->getCurrentCoordinate()))
				              	->getFill()->applyFromArray(['fillType' => 'solid', 'rotation' => 0, 'color' => ['rgb' => '6C7293'],]);
				              	$event->sheet->getDelegate()->getStyle(sprintf('%s', $cell->getParent()->getCurrentCoordinate()))
				              	->getFont()->getColor()->setRGB('FFFFFF');
			              	} else if($sheet_obj->checkMergedCell($event->sheet, $cell) && strlen(trim($cell->getValue())) > 0 && !in_array($row->getRowIndex(), $exclude_header_row)){
			              		$event->sheet->getDelegate()->getStyle(sprintf('%s', $cell->getParent()->getCurrentCoordinate()))
				              	->getFill()->applyFromArray(['fillType' => 'solid', 'rotation' => 0, 'color' => ['rgb' => '000000'],]);
				              	$event->sheet->getDelegate()->getStyle(sprintf('%s', $cell->getParent()->getCurrentCoordinate()))
				              	->getFont()->getColor()->setRGB('FFFFFF');
			              	}
				    	}
				    }
				}

				// Format spesific column (for header)
	            // Set Bold & Center
				$cellRange = 'A1:Z3';
	            $event->sheet->getDelegate()->getStyle($cellRange)
	            ->getFont()->setBold(false);
	            $event->sheet->getDelegate()->getStyle($cellRange)
	            ->getFont()->setSize(15);
	           	$event->sheet->getDelegate()->getStyle($cellRange)
              	->getAlignment()
              	->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

              	// Format cell period's color, alignment of text
              	$cellRangePeriod = 'A4:Q4';
              	$event->sheet->getDelegate()->getStyle($cellRangePeriod)
              	->getAlignment()
              	->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
              	$event->sheet->getDelegate()->getStyle($cellRangePeriod)
              	->getFill()->applyFromArray(['fillType' => 'solid', 'rotation' => 0, 'color' => ['rgb' => '6C7293'],]);
              	$event->sheet->getDelegate()->getStyle($cellRangePeriod)
              	->getFont()->getColor()->setRGB('FFFFFF');

              	$cellRangePeriodCategory = 'A5:Q5';
              	$event->sheet->getDelegate()->getStyle($cellRangePeriodCategory)
              	->getAlignment()
              	->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
              	$event->sheet->getDelegate()->getStyle($cellRangePeriodCategory)
              	->getFill()->applyFromArray(['fillType' => 'solid', 'rotation' => 0, 'color' => ['rgb' => '000000'],]);
              	$event->sheet->getDelegate()->getStyle($cellRangePeriodCategory)
              	->getFont()->getColor()->setRGB('FFFFFF');
	        },
	    ];
	}

    public function view(): View
    {
    	$data_passed = $this->data_cost;
    	$data_view = $this->data_view;
    	$data_title = $this->data_title;
        return view($data_view, [
            'data' => isset($data_passed['DATA']) ? $data_passed['DATA'] : [],
            'title' => $data_title,
            'month' => isset($data_passed['MONTH']) ? $data_passed['MONTH'] : '',
            'year' => isset($data_passed['YEAR']) ? $data_passed['YEAR'] : '',
			'company' => isset($data_passed['COMPANY']) ? $data_passed['COMPANY'] : '',
        ]);
    }
}


class PNLExport implements WithMultipleSheets
{
	protected $data_cost = [];
    public function __construct($pnl_cost=[]){
    	$this->data_cost = $pnl_cost;
    }

    public function sheets(): array
    {
        $sheets = [];
        $data_cost_template = isset($this->data_cost['DATA']) ? $this->data_cost['DATA'] : [];
        $data_month = isset($this->data_cost['MONTH']) ? $this->data_cost['MONTH'] : 'Unknown';
        $data_year = isset($this->data_cost['YEAR']) ? $this->data_cost['YEAR'] : 'Unknown';
		$data_company = isset($this->data_cost['COMPANY']) ? $this->data_cost['COMPANY'] : 'Unknown';
        foreach($data_cost_template as $key => $data){
        	$data_pnl = [];
        	$title = isset($data['DESC']) ? $data['DESC'] : sprintf('No - Desc - %s', $key);
        	$data_pnl['DATA'] = isset($data['DATA']) ? $data['DATA'] : [];
        	$data_pnl['MONTH'] = $data_month;
        	$data_pnl['YEAR'] = $data_year;
			$data_pnl['COMPANY'] = $data_company;
        	$sheets[] = new PNLData(sprintf('exports_pnl.%s', $key), $title, $data_pnl);
        }
        return $sheets;
    }
}