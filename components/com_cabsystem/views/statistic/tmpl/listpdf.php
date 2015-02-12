<?php
require_once(JPATH_ROOT.'/components/com_cabsystem/assets/libs/tcpdf/tcpdf.php');
?>

<?
$app = JFactory::getApplication();
$driver_id = urldecode($app->input->getInt('driver_id'));
$month = urldecode($app->input->getInt('month'));
$year = urldecode($app->input->getInt('year'));

$filter = !empty($this->driver_name) ? "Fahrer ".$this->driver_name : "Alle Fahrer";
$filter .= " | ";
$filter .= !empty($month) ? "Monat ".$month : "Alle Monate";
$filter .= " | ";
$filter .= !empty($year) ? "Jahr ".$year : "Alle Jahre";
	
if(!empty($driver_id)) {
	$this->orderModel->set('_driver_id',$driver_id);
}
if(!empty($month)) {
	$this->orderModel->set('_month',$month);
}
if(!empty($year)) {
	$this->orderModel->set('_year',$year);
}
$this->orderModel->set('_where_only_in_past',true);
$this->orderModel->set('_status',2);

// create new PDF document
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('Cabsystem');
$pdf->SetAuthor('Cabsystem');
$pdf->SetTitle('Statistik und Abrechnung');

// set default header data
$pdf->SetHeaderData('', 0, '', 'Cabsystem - Statistik und Abrechnung - '.$filter);

// set header and footer fonts
$pdf->setHeaderFont(Array('helvetica', '', 9));
$pdf->setFooterFont(Array('helvetica', '', 9));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

$html = '<img src="'.JPATH_ROOT.'/images/admin/logo_dunkel.png" alt="Cabsystem Logo" />';

$html .= "<h1>".JText::_('COM_CABSYSTEM_STATISTIC_LIST_TITLE')."</h1>";

$html .= "<p>aktuelle Auswahl: ";
$html .= $filter;
$html .= "</p>";

if(count($this->orderModel->listItems()) > 0) {
$html .= <<<EOD
<div class="content-panel">
	<table id="dataTableStatistics" cellspacing="0" cellpadding="0">

	<tbody>
EOD;
	$sum_tr = 0;	
	foreach($this->orderModel->listItems() as $order) {
		$sum_tr += $order->price;
		$this->_statisticListView->order = $order;
		ob_end_clean();
		ob_start();
		echo $this->_statisticListView->render();
		$html .= ob_get_contents();
		$html .= "<tr><td></td><td></td><td></td></tr>";
		ob_clean();
	}
	
	$html .= '<tr><td colspan="3" align="right"><strong>Die Summe für diese '.count($this->orderModel->listItems()).' Bestellungen beträgt € '.number_format($sum_tr,2).'</strong></td></tr>';

$html .= <<<EOD
	</tbody>
</table>
</div>
EOD;
}
else {
	$html .= '<td colspan="3">Keine Datensätze für diese Auswahl verfügbar</td>';
}

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

ob_end_clean();
ob_end_clean();
$pdf->Output('cabsystem_statistik_'.date('d_m_Y').'.pdf', 'I');
?>

