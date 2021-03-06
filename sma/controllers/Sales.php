<?php defined('BASEPATH') OR exit('No direct script access allowed');
  require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
class Sales extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        if ($this->Supplier) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->lang->load('sales', $this->Settings->language);
        $this->lang->load('vouchers', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('sales_model');
		$this->load->model('pos_model');
        $this->load->model('vouchers_model');
        $this->load->model('companies_model');
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '1024';
        $this->data['logo'] = true;
    }

    function index($warehouse_id = NULL)
    {
        $this->sma->checkPermissions('index',null,'sales');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
       // if ($this->Owner || $this->Admin) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
       // } else {
       //     $this->data['warehouses'] = NULL;
       //     $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
       //     $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : NULL;
       // }
        $this->data['total_kes'] = $this->sales_model->getTOTALKES();
        $this->data['total_us'] = $this->sales_model->getTOTALUS();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('sales')));
        $meta = array('page_title' => lang('sales'), 'bc' => $bc);
        $this->page_construct('sales/index', $meta, $this->data);
    }

    function graph(){
          if(!$this->Owner && !$this->Admin){
                    $this->session->set_flashdata('error',"Not authorised to view page");
            redirect($_SERVER["HTTP_REFERER"]);
          }
          $fromdate=trim($this->input->get("fromdate"));
          $todate=trim($this->input->get("todate"));
        //die($fromdate."sds");
         $date1 = DateTime::createFromFormat('d/m/Y',$fromdate);
         $date2 = DateTime::createFromFormat('d/m/Y',$todate);
      $table="ps_";
        
          if(empty($fromdate)){
              $fromdate=date("Y-m-d");
            $todate=date("Y-m-d");
          }
		  
          else{
                $fromdate=$date1->format("Y-m-d");
            $todate=$date2->format("Y-m-d");
          }
          
		  
          $results=$this->db->query("select sum(grand_total) as total_sales from sma_sales where DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '$fromdate' AND '$todate' ")->result_array();     
       $resultspurchases=$this->db->query("select sum(grand_total) as total_purchases from sma_purchases where DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '$fromdate' AND '$todate' ")->result_array();     
          $resultsexpenses=$this->db->query("select sum(amount) as total_expense from sma_expenses where DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '$fromdate' AND '$todate' ")->result_array();     
       $resultspayments=$this->db->query("select sum(amount) as total_amount,paid_by from sma_payments where DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '$fromdate' AND '$todate' group by paid_by")->result_array();     
    
//          $resultbookings=$this->db->query('SELECT SUM(cap.total_order_amount - cap.total_paid_amount) AS `amount_due`,SUM(op.amount) as paid_amount,so.total_paid_tax_incl,so.total_paid_tax_incl ,so.payment as payment_method,so.board_type,DATE_FORMAT(so.invoice_date,"%Y-%m-%d") as invoice_date, so.source AS order_source,op.transaction_id as transaction_id,
//        so.id_currency,
//        so.id_order AS id_pdf,
//        CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`,
//         CONCAT(LEFT(emp.`firstname`, 1), \'. \', emp.`lastname`) AS `employee_names`,
//        osl.`name` AS `osname`,
//        os.`color`,hri.`room_num` as room_num  FROM `'.$table.'orders` so LEFT JOIN `'.$table.'customer` c ON (c.`id_customer` = so.`id_customer`)
//           
//            
//                   LEFT JOIN `'.$table.'employee` emp ON (so.`employee` = emp.`id_employee`)  
//              LEFT JOIN `'.$table.'order_payment` op ON (so.`reference` = op.`order_reference`)
//              LEFT JOIN `'.$table.'htl_cart_booking_data` hcb ON (so.`id_order` = hcb.`id_order`)   
//                  LEFT JOIN `'.$table.'htl_room_information` hri ON (hcb.`id_room` = hri.`id`)  
//                       INNER JOIN `'.$table.'order_state` os ON (os.`id_order_state` = so.`current_state`)
//                 LEFT JOIN `'.$table.'htl_customer_adv_payment` cap ON (cap.`id_order` = so.`id_order`)
//                      LEFT JOIN `'.$table.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state`)
//                 WHERE  (DATE_FORMAT(so.invoice_date,"%Y-%m-%d") between "'.$fromdate.'" and "'.$todate.'" ) group by invoice_date ')->result_array();
//         
       
          
       
      // die(print_r(json_encode($resultbookings)));
       
       $resultbookings=array();
          $this->data['warehouses'] = NULL;
         $this->data['sales'] = round($results[0]["total_sales"],2);
          $this->data['purchases'] = round($resultspurchases[0]["total_purchases"],2);
          $this->data["fromdate"]=$fromdate;
           $this->data["todate"]=$todate;

          $this->data["payment_type"]=json_encode($resultspayments);
          $this->data["room_bookings"]=json_encode($resultbookings);
          $this->data['expenses'] = round($resultsexpenses[0]["total_expense"],2);
         $meta = array('page_title' => lang('product_expiry_alerts'));
       
          $this->page_construct('reports/graph', $meta, $this->data); 
    }
	
       function stmntofaccount(){
		   
          if(!$this->Owner && !$this->Admin){
                    $this->session->set_flashdata('error',"Not authorised to view page");
            redirect($_SERVER["HTTP_REFERER"]);
          }
		   //$this->form_validation->set_rules('subject', lang("subject"), 'trim|required');
          $fromdate=trim($this->input->get("fromdate"));
          $todate=trim($this->input->get("todate"));
		   $user_id=trim($this->input->get("chkincustomer_edit"));
     $company=$this->companies_model->getCompanyByID($user_id);
        
         $date1 = DateTime::createFromFormat('d/m/Y',$fromdate);
         $date2 = DateTime::createFromFormat('d/m/Y',$todate);
	if(!empty($fromdate)){
		   $fromdate= $date1->format("Y-m-d");
		  $todate= $date2->format("Y-m-d");
	}
	
	//bbf from 01-nov
		  $resultbbf=$this->db->query("SELECT * FROM (SELECT sma_sales.id,date,reference_no,total as credit,'0' as debit,'Invoice' as doc,IF(sma_sales.pos='1',GROUP_CONCAT(CONCAT(" . $this->db->dbprefix('sale_items') . ".product_name, '(', " . $this->db->dbprefix('sale_items') . ".quantity) SEPARATOR ')'),'Accomodation Invoice') as rmks FROM `sma_sales` 
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
WHERE customer_id = '$user_id' AND pos='0' AND DATE_FORMAT(date,'%Y-%m-%d') < '$fromdate'  AND DATE_FORMAT(date,'%Y-%m-%d') > '2021-11-01'  GROUP BY  sma_sales.id  UNION
SELECT sma_sales.id,date,reference_no,total as credit,'0' as debit,'Invoice' as doc,GROUP_CONCAT(CONCAT(" . $this->db->dbprefix('sale_items') . ".product_name, '(', " . $this->db->dbprefix('sale_items') . ".quantity) SEPARATOR ')') as rmks 
FROM `sma_sales` 
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
WHERE customer_id = '$user_id' AND pos='1' AND DATE_FORMAT(date,'%Y-%m-%d') < '$fromdate' AND DATE_FORMAT(date,'%Y-%m-%d') > '2021-11-01'  GROUP BY  sma_sales.id UNION
SELECT sma_payments.id,sma_payments.date,sma_payments.reference_no,'0',sma_payments.amount,'Payment' as doc,paid_by FROM `sma_payments` 
JOIN sma_sales ON sma_sales.id = sma_payments.sale_id 
WHERE  sma_sales.customer_id = '$user_id' AND DATE_FORMAT(sma_payments.date,'%Y-%m-%d') < '$fromdate' AND DATE_FORMAT(sma_payments.date,'%Y-%m-%d')> '2021-11-01'  UNION 
SELECT sma_reception_payments.id,sma_reception_payments.date,sma_reception_payments.reference_no,'0',sma_reception_payments.amount,'Payment' as doc,paid_by FROM `sma_reception_payments` 
JOIN sma_sales ON sma_sales.id = sma_reception_payments.sale_id AND  sma_sales.customer_id = '$user_id' AND DATE_FORMAT(sma_reception_payments.date,'%Y-%m-%d') < '$fromdate' AND DATE_FORMAT(sma_reception_payments.date,'%Y-%m-%d') > '2021-11-01' ) AS T1 ORDER BY date")->result();

$result=$this->db->query("SELECT * FROM (SELECT sma_sales.id,date,reference_no,total as credit,'0' as debit,'Invoice' as doc,IF(sma_sales.pos='1',GROUP_CONCAT(CONCAT(" . $this->db->dbprefix('sale_items') . ".product_name, '(', " . $this->db->dbprefix('sale_items') . ".quantity) SEPARATOR ')'),CONCAT('Accomodation Invoice')) as rmks FROM `sma_sales` 
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
WHERE customer_id = '$user_id' AND pos='0' AND DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '$fromdate' AND '$todate' AND DATE_FORMAT(date,'%Y-%m-%d') > '2021-11-01' GROUP BY  sma_sales.id  UNION
SELECT sma_sales.id,date,reference_no,total as credit,'0' as debit,'Invoice' as doc,GROUP_CONCAT(CONCAT(" . $this->db->dbprefix('sale_items') . ".product_name, '(', " . $this->db->dbprefix('sale_items') . ".quantity) SEPARATOR ')') as rmks 
FROM `sma_sales` 
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
WHERE customer_id = '$user_id' AND pos='1' AND DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '$fromdate' AND '$todate' AND DATE_FORMAT(date,'%Y-%m-%d') > '2021-11-01' GROUP BY  sma_sales.id UNION
SELECT sma_payments.id,sma_payments.date,sma_payments.reference_no,'0',sma_payments.amount,'Payment' as doc,paid_by FROM `sma_payments` 
JOIN sma_sales ON sma_sales.id = sma_payments.sale_id 
WHERE  sma_sales.customer_id = '$user_id' AND DATE_FORMAT(sma_payments.date,'%Y-%m-%d') BETWEEN '$fromdate' AND '$todate' AND DATE_FORMAT(sma_payments.date,'%Y-%m-%d') >'2021-11-01' UNION 
SELECT sma_reception_payments.id,sma_reception_payments.date,sma_reception_payments.reference_no,'0',sma_reception_payments.amount,'Payment' as doc,paid_by FROM `sma_reception_payments` 
JOIN sma_sales ON sma_sales.id = sma_reception_payments.sale_id AND  sma_sales.customer_id = '$user_id' AND DATE_FORMAT(sma_reception_payments.date,'%Y-%m-%d') BETWEEN '$fromdate' AND '$todate' AND DATE_FORMAT(sma_reception_payments.date,'%Y-%m-%d') > '2021-11-01' ) AS T1 ORDER BY date")->result();

		  
        $html='<table width="98%" border="1" style="margin-left:10px;border-collapse:collapse;font-size:14px">
            <tr><th width="20%"><img src="' . site_url() . 'assets/uploads/logos/logo.png" alt="' . SITE_NAME . '"  /></th><th style="text-align:center" colspan="5"><u>Statement of Account:<br>'.strtoupper($company->name).'<br>From: '.date('d-M-Y',strtotime($fromdate)).' To : '.date('d-M-Y',strtotime($todate)).'</u></th></tr>
                <tr><td><b>Date</b></td><td><b>Ref #</b></td><td ><b>Items Billed</b></td><td width="80px"><b>Debit</b></td> <td width="80px"><b>Credit</b></td><td width="80px"><b>Balance</b></td></tr>
';
$bbfdebit =0; $bbfcredit =0;
foreach ($resultbbf as $val) { 
$bbfdebit = $bbfdebit + $val->debit;
$bbfcredit = $bbfcredit + $val->credit;

}
$bbfamnt = $bbfcredit-$bbfdebit;
$html.='  <tr><td colspan="3" style="text-align:right">BBF</td><td colspan="3" style="text-align:right"><b>'.number_format($bbfamnt).' &nbsp;</b></td></tr>
';
$totalsales=$result;
        $count=1;
        $totalbill=0; $runningbal=0; $totalcredit=0; $totaldebit=0;
		if(!empty($fromdate)){
        foreach ($totalsales as $value) { 
            $totalbill+=$value->grand_total;
			 $totalcredit =  $totalcredit +$value->credit;
			 $totaldebit =  $totaldebit +$value->debit;
			$runningbal = ($runningbal+$value->credit) - $value->debit;
            $saleitems=$this->site->getAllSaleItems($value->id);
			
            
$html.='<tr style="cellpadding:2px">
    <td class="contentDetails">'.date("d/m/Y H:i",  strtotime($value->date)).'</td><td class="contentDetails">'.$value->doc.'  -  '.$value->reference_no.'</td> <td class="contentDetails">'.$value->rmks.'</td>';

  $html.='<td class="contentDetails" style="text-align:right">'.number_format($value->debit).'</td><td class="contentDetails" style="text-align:right">'.number_format($value->credit).'</td>
  <td  style="text-align:right">'.number_format($runningbal+$bbfamnt).'</td></tr>';
$count++;
            }
            $html.="<tr><td colspan='3' style='text-align:center'><b>TOTALS</b></td><td style='text-align:right'><b>".number_format($totaldebit)."</td><td style='text-align:right'><b>".number_format($totalcredit)."</b></td><td style='text-align:right'><b>".number_format($totalcredit -$totaldebit+$bbfamnt)."</b></td></tr>
			<tr><td colspan='5' style='text-align:center'><b>AMOUNT DUE</b></td><td style='text-align:right'><b>".number_format($totalcredit -$totaldebit+$bbfamnt)."</b></td></tr>";

$html.='</table>';
$html.='<table style="font-size:14px">
<tr> <td><br><i><u>Payment Terms</u></i></td></tr>
<tr> <td><br>1. Payment is due as per our Contractual terms and Conditions.</td></tr>
<tr> <td>2. Please make all cheques payable to <b>Rocy Investment Ltd</b>.</td></tr>
<tr> <td><br><b>BANK DETAILS.</b></td></tr>
<tr> <td>Account Name : Rocy Investment Ltd

<br>Bank Name : Stanbic Bank

<br>Branch Name : Mbale

<br>UGX A/C No : 9030016175942.</td></tr>
</table>';


  $mpdf=new \mPDF('c','A4-P','','' , 0 , 0 , 0 , 0 , 0 , 0); 
  $mpdf->SetMargins(0,0,10);
  $mpdf->SetLeftMargin(30);
 
//$mpdf->SetDisplayMode('fullpage');
 
$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
 
$mpdf->WriteHTML($html);


$mpdf->Output('receipt.pdf','I');

		}
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $this->data['user_id'] = $user_id;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('customers_report')));
        

		 
		  		   $this->data['customers'] = $this->site->getAllCompanies("customer");
		
          $this->data["fromdate"]=$fromdate;
           $this->data["todate"]=$todate;
          
       $meta = array('page_title' => lang('customers_report'), 'bc' => $bc);
       
          $this->page_construct('reports/customestmt', $meta, $this->data); 
    }
	       
		   
		   
		   function supplierstmt(){
		   
          if(!$this->Owner && !$this->Admin){
                    $this->session->set_flashdata('error',"Not authorised to view page");
            redirect($_SERVER["HTTP_REFERER"]);
          }
		   //$this->form_validation->set_rules('subject', lang("subject"), 'trim|required');
          $fromdate=trim($this->input->get("fromdate"));
          $todate=trim($this->input->get("todate"));
		   $user_id=trim($this->input->get("chkinsupplier_edit"));
     $company=$this->companies_model->getCompanyByID($user_id);
        print_r($company);
         $date1 = DateTime::createFromFormat('d/m/Y',$fromdate);
         $date2 = DateTime::createFromFormat('d/m/Y',$todate);
	if(!empty($fromdate)){
		   $fromdate= $date1->format("Y-m-d");
		  $todate= $date2->format("Y-m-d");
	}
	
	//bbf from 01-nov
		  $resultbbf=$this->db->query("SELECT * FROM (SELECT sma_purchases.id,sma_purchases.date,reference_no,total as credit,'0' as debit,'Invoice' as doc,'Purchase Supplier Invoice' as rmks FROM `sma_purchases` 
LEFT JOIN sma_purchase_items ON sma_purchases.id = sma_purchase_items.purchase_id 
WHERE  	supplier_id = '$user_id' AND DATE_FORMAT(sma_purchases.date,'%Y-%m-%d') < '$fromdate'  GROUP BY  sma_purchases.id  UNION
SELECT sma_payments.id,sma_payments.date,sma_payments.reference_no,'0',sma_payments.amount,'Payment' as doc,paid_by FROM `sma_payments` 
JOIN sma_purchases ON sma_purchases.id = sma_payments.purchase_id 
WHERE  sma_purchases.supplier_id = '$user_id' AND DATE_FORMAT(sma_payments.date,'%Y-%m-%d') < '$fromdate'  ) AS T1 ORDER BY date")->result();

$result=$this->db->query("SELECT * FROM (SELECT sma_purchases.id,sma_purchases.date,reference_no,total as credit,'0' as debit,'Purchase' as doc,GROUP_CONCAT(CONCAT(' '," . $this->db->dbprefix('purchase_items') . ".product_name, '(', " . $this->db->dbprefix('purchase_items') . ".quantity) SEPARATOR ')') as rmks FROM `sma_purchases` 
LEFT JOIN sma_purchase_items ON sma_purchases.id = sma_purchase_items.purchase_id 
WHERE supplier_id = '$user_id'  AND DATE_FORMAT(sma_purchases.date,'%Y-%m-%d') BETWEEN '$fromdate' AND '$todate'  GROUP BY  sma_purchases.id  UNION
SELECT sma_payments.id,sma_payments.date,sma_payments.reference_no,'0',sma_payments.amount,'Payment' as doc,paid_by FROM `sma_payments` 
JOIN sma_purchases ON sma_purchases.id = sma_payments.purchase_id 
WHERE  sma_purchases.supplier_id = '$user_id' AND DATE_FORMAT(sma_payments.date,'%Y-%m-%d') BETWEEN '$fromdate' AND '$todate'  ) AS T1 ORDER BY date")->result();
		  
        $html='<table width="98%" border="1" style="margin-left:10px;border-collapse:collapse;font-size:14px">
            <tr><th width="20%"><img src="' . site_url() . 'assets/uploads/logos/logo.png" alt="' . SITE_NAME . '"  /></th><th style="text-align:center" colspan="5"><u>Supplier Statement:<br>'.strtoupper($company->name).'<br>From: '.date('d-M-Y',strtotime($fromdate)).' To : '.date('d-M-Y',strtotime($todate)).'</u></th></tr>
                <tr><td><b>Date</b></td><td><b>Ref #</b></td><td width="30%"><b>Narration</b></td><td width="80px"><b>Debit</b></td> <td width="80px"><b>Credit</b></td><td width="80px"><b>Balance</b></td></tr>
';
$bbfdebit =0; $bbfcredit =0;
foreach ($resultbbf as $val) { 
$bbfdebit = $bbfdebit + $val->debit;
$bbfcredit = $bbfcredit + $val->credit;

}
$bbfamnt = $bbfcredit-$bbfdebit;
$html.='  <tr><td colspan="3" style="text-align:right">BBF</td><td colspan="3" style="text-align:right"><b>'.number_format($bbfamnt).'</b></td></tr>
';
$totalsales=$result;
        $count=1;
        $totalbill=0; $runningbal=0; $totalcredit=0; $totaldebit=0;
		if(!empty($fromdate)){
        foreach ($totalsales as $value) { 
            $totalbill+=$value->grand_total;
			 $totalcredit =  $totalcredit +$value->credit;
			 $totaldebit =  $totaldebit +$value->debit;
			$runningbal = ($runningbal+$value->credit) - $value->debit;
            $saleitems=$this->site->getAllSaleItems($value->id);
			
            
$html.='<tr style="cellpadding:2px">
    <td class="contentDetails">'.date("d/m/Y H:i",  strtotime($value->date)).'</td><td class="contentDetails">'.$value->doc.'  -  '.$value->reference_no.'</td> <td class="contentDetails">'.$value->rmks.'</td>';

  $html.='<td class="contentDetails" style="text-align:right">'.number_format($value->debit).'</td><td class="contentDetails" style="text-align:right">'.number_format($value->credit).'</td>
  <td  style="text-align:right">'.number_format($runningbal+$bbfamnt).'</td></tr>';
$count++;
            }
            $html.="<tr><td colspan='3' style='text-align:center'><b>TOTALS</b></td><td style='text-align:right'><b>".number_format($totaldebit)."</td><td style='text-align:right'><b>".number_format($totalcredit)."</b></td><td style='text-align:right'><b>".number_format($totalcredit -$totaldebit+$bbfamnt)."</b></td></tr>
			<tr><td colspan='5' style='text-align:center'><b>AMOUNT DUE</b></td><td style='text-align:right'><b>".number_format($totalcredit -$totaldebit+$bbfamnt)."</b></td></tr>";

$html.='</table>';
$html.='';


  $mpdf=new \mPDF('c','A4-P','','' , 0 , 0 , 0 , 0 , 0 , 0); 
  $mpdf->SetMargins(0,0,10);
  $mpdf->SetLeftMargin(30);
 
//$mpdf->SetDisplayMode('fullpage');
 
$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
 
$mpdf->WriteHTML($html);


$mpdf->Output('supplier_account.pdf','I');

		}
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $this->data['user_id'] = $user_id;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('customers_report')));
        

		 
		  		   $this->data['customers'] = $this->site->getAllCompanies("supplier");
		
          $this->data["fromdate"]=$fromdate;
           $this->data["todate"]=$todate;
          
       $meta = array('page_title' => lang('suppliers_report'), 'bc' => $bc);
       
          $this->page_construct('reports/supplrstmt', $meta, $this->data); 
    }
	       
		   

		   function saledet_report(){
		   
          if(!$this->Owner && !$this->Admin){
                    $this->session->set_flashdata('error',"Not authorised to view page");
            redirect($_SERVER["HTTP_REFERER"]);
          }
		   //$this->form_validation->set_rules('subject', lang("subject"), 'trim|required');
          $fromdate=trim($this->input->get("fromdate"));
          $todate=trim($this->input->get("todate"));
		   $department=trim($this->input->get("departmentt"));
		   $paid_by=trim($this->input->get("paid_byy"));
		   
     $company=$this->companies_model->getCompanyByID($user_id);
        
         $date1 = DateTime::createFromFormat('d/m/Y',$fromdate);
         $date2 = DateTime::createFromFormat('d/m/Y',$todate);
	if(!empty($fromdate)){
		   $fromdate= $date1->format("Y-m-d")." 06:00:00";
		  $todate= $date2->format("Y-m-d")." 05:59:59";
	
	if ($paid_by) {
                $filterpay = "sma_sales.pmethod = '$paid_by'";
				$filterpays = "sma_reception_payments.paid_by = '$paid_by'";
            }
	if($department=='bar'){
				if ($paid_by){	
					$filtercat = 'AND sma_products.category_id <> "57"';
				}else{$filtercat = 'sma_products.category_id <> "57"';}
					$dispdep = 'Bar';
				}else if($department=='kitchen'){
					if ($paid_by){	
					$filtercat = 'AND sma_products.category_id = "57"';
				}else{$filtercat = 'sma_products.category_id = "57"';}
					
					$dispdep = 'Restaurant';
				}
				else if($department=='recp'){
					$dispdep = 'Reception';
					if ($paid_by){	
					$filtercat = ('AND sma_sales.pos = "0"');
				}else{$filtercat = ('sma_sales.pos = "0"');}
					
				}
		  $query1=$this->db->query("SELECT sma_sales.date, CONCAT('Sale: ',sma_sales.reference_no,' Payment No -',sma_payments.id) as reference_no, sma_rooms.name as biller, customer, GROUP_CONCAT(CONCAT(sma_sale_items.product_name, '__', sma_sale_items.quantity) SEPARATOR '___') as iname, grand_total, IF(sma_payments.paid_by ='nonchrg',0,sma_payments.amount) as paid, (grand_total-paid) as balance, IF(grand_total=paid, 'paid', 'due') payment_status, pmethod as paid_by, CONCAT(sma_payments.mpesa_transaction_no, '', sma_payments.cost_center_no, '', sma_payments.cheque_no, '', sma_payments.cc_no) as transaction_no 
		  FROM `sma_sales` 
		  LEFT JOIN `sma_sale_items` ON `sma_sale_items`.`sale_id`=`sma_sales`.`id` 
		  LEFT JOIN `sma_payments` ON `sma_payments`.`sale_id`=`sma_sales`.`id` 
		  LEFT JOIN `sma_rooms` ON `sma_rooms`.`id`=`sma_sales`.`room_id` 
		  LEFT JOIN `sma_products` ON `sma_products`.`id`=`sma_sale_items`.`product_id` 
		  LEFT JOIN `sma_warehouses` ON `sma_warehouses`.`id`=`sma_sales`.`warehouse_id` 
		  WHERE $filterpay  $filtercat
		  
		  AND (sma_payments.date BETWEEN '$fromdate' and '$todate')
		  GROUP BY `sma_payments`.`id`  ORDER BY `sma_sales`.`date` DESC ")->result();

$query2=$this->db->query("SELECT sma_reception_payments.date, sma_reception_payments.reference_no,sma_rooms.name as biller,customer,'Bar/Rest Bill Payment' as iname,grand_total, paid, (grand_total-paid) as balance, IF(grand_total=paid, 'paid', 'due') payment_status, sma_reception_payments.paid_by as paid_by, CONCAT(sma_reception_payments.mpesa_transaction_no, '', sma_reception_payments.cost_center_no, '', sma_reception_payments.cheque_no, '', sma_reception_payments.cc_no) as transaction_no 
		  FROM  sma_reception_payments
          LEFT JOIN sma_sales ON sma_reception_payments.sale_id = sma_sales.id
           LEFT JOIN sma_rooms ON sma_rooms.id =sma_sales.room_id 
     	 WHERE sma_reception_payments.date BETWEEN '$fromdate' and '$todate'")->result();
if($department !='recp'){
					
					$union = $query1;
				}
				else{
					$union = array_merge($query1, $query2);
				}
		 // print_r($union);
		 // die();
		
        $html='<table width="100%" border="1" style="margin-left:10px;border-collapse:collapse;font-size:16px">
            <tr><th width="20%">'.$paid_by.' <br>'.$dispdep. ' Sales</th><th style="text-align:center" colspan="5"><u>Detailed Sales Report:<br><br>From: '.$this->input->get("fromdate").' to: '.$this->input->get("todate").'</u></th></tr>
                <tr><td><b>Date</b></td><td><b>Ref #</b></td><td ><b>Customer</b></td><td width="25%"><b>Details</b></td> <td width="5%"><b>Paid By</b></td><td width="10%"><b>Paid</b></td></tr>
';
$totalamnt =0;
       foreach ($union as $value) { 
           $totalamnt = $totalamnt +  $value->paid;
			
            
$html.='<tr style="cellpadding:2px">
    <td class="contentDetails">'.date("d/m/Y H:i",  strtotime($value->date)).'</td><td class="contentDetails">'.$value->reference_no.' </td> <td class="contentDetails">'.$value->customer.'</td>
	<td class="contentDetails">'.$value->biller.'- '.$value->iname.'</td><td class="contentDetails">'.$value->paid_by.'</td><td class="contentDetails" style="text-align:right">'.number_format($value->paid).'&nbsp;</td></tr>';
	   }
 $html.='<tr style="cellpadding:2px">
 <td colspan="4" style="text-align:center"><b>Total '.$paid_by.' ('.$dispdep. ')</b></td>
<td class="contentDetails" style="text-align:right" colspan="2"><b>'.number_format($totalamnt).'&nbsp;</b></td>';
          
$html.='</table>';



  $mpdf=new \mPDF('c','A4-P','','' , 0 , 0 , 0 , 0 , 0 , 0); 
  $mpdf->SetMargins(0,0,10);
  $mpdf->SetLeftMargin(30);
 
//$mpdf->SetDisplayMode('fullpage');
 
$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
 
$mpdf->WriteHTML($html);


$mpdf->Output('receipt.pdf','I');

		}
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $this->data['user_id'] = $user_id;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('customers_report')));
        

		 
		  		   $this->data['customers'] = $this->site->getAllCompanies("customer");
		
          $this->data["fromdate"]=$fromdate;
           $this->data["todate"]=$todate;
          
       $meta = array('page_title' => lang('customers_report'), 'bc' => $bc);
       
          $this->page_construct('reports/salesstmt', $meta, $this->data); 
    }
	
			function viewcostingrpt($modal = NULL)
    {
        $this->sma->checkPermissions('index');
       if ($this->input->get('cost_fromdate')) {
            $sdate = $this->input->get('cost_fromdate');
			$date1 = DateTime::createFromFormat('d/m/Y',$sdate);
			$sdate = $date1->format("Y-m-d")." 06:00:00";
			$roomingdate = $date1->format("Y-m-d");
        } 
		if ($this->input->get('cost_todate')) {
            $tdate = $this->input->get('cost_todate');
			 $date2 = DateTime::createFromFormat('d/m/Y',$tdate);
			  $tdate= $date2->format("Y-m-d")." 05:59:59";
			  
        }
			$costdepartmentt = $this->input->get('costdepartmentt');
		if($costdepartmentt=="bar"){
			$filter = "and sma_products.category_id <> '57' AND sma_products.category_id > '53' ";
		}else if($costdepartmentt=="kitchen"){
			$filter = "and sma_products.category_id = '57' ";
		} else {
			$filter = "";
		}

        $this->load->helper('text');
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message'] = $this->session->flashdata('message');
        
		$this->data['costanalysis']=$this->db->query("SELECT name,SUM(cost) as cost,SUM(price) as price,id FROM(SELECT sma_products.name,sma_products.cost,IF(sma_payments.paid_by ='nonchrg',sma_sale_items.unit_price,sma_sale_items.unit_price)as price,sma_products.id
		FROM `sma_sales` JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
		JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
		JOIN sma_payments on sma_payments.sale_id = sma_sales.id 
		WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate' AND pos ='1' $filter ) T7 GROUP BY T7.id ")->result();


		
$this->data['fromdate'] = $sdate;
$this->data['todate'] = $tdate;
      //$this->load->view($this->theme . 'pos/viewsmrpt', $this->data);
		$this->load->view($this->theme . 'reports/viewcosting', $this->data);
    }
				
				
				function foodcostingrpt($modal = NULL)
    {
        $this->sma->checkPermissions('index');
       if ($this->input->get('fcost_fromdate')) {
            $sdate = $this->input->get('fcost_fromdate');
			$date1 = DateTime::createFromFormat('d/m/Y',$sdate);
			$sdate = $date1->format("Y-m-d")." 06:00:00";
			$roomingdate = $date1->format("Y-m-d");
        } 
		if ($this->input->get('fcost_todate')) {
            $tdate = $this->input->get('fcost_todate');
			 $date2 = DateTime::createFromFormat('d/m/Y',$tdate);
			  $tdate= $date2->format("Y-m-d")." 05:59:59";
			  
        }
			$costdepartmentt = $this->input->get('fcostdepartmentt');
		if($costdepartmentt=="bar"){
			$filter = "and sma_products.category_id <> '57' AND sma_products.category_id > '53' ";
		}else if($costdepartmentt=="kitchen"){
			$filter = "and sma_products.category_id = '57' ";
		} else {
			$filter = "";
		}

        $this->load->helper('text');
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message'] = $this->session->flashdata('message');
        
		$this->data['transfercost']=$this->db->query("SELECT DATE_FORMAT(date,'%Y-%m-%d') as transferdate,SUM(grand_total) AS transfercost FROM `sma_transfers` 
		WHERE date BETWEEN '$sdate' AND '$tdate' AND to_warehouse_id = '33' GROUP BY DATE_FORMAT(date,'%Y-%m-%d') ")->result();
		
		$this->data['purchsase']=$this->db->query("SELECT DATE_FORMAT(date,'%Y-%m-%d') as purchasedate ,SUM(grand_total) AS purchsasecost FROM `sma_purchases` 
		WHERE date BETWEEN  '$sdate' AND '$tdate' AND warehouse_id = '33' GROUP BY DATE_FORMAT(date,'%Y-%m-%d') ")->result();

		$this->data['nonchrg']=$this->db->query("SELECT date as ncdate,sum(sale) as ncsale FROM
		(SELECT  DATE_FORMAT(sma_sales.date,'%Y-%m-%d') as date ,sma_sales.id, sma_sale_items.product_name,sma_products.cost,sma_sale_items.quantity,sma_products.cost*sma_sale_items.quantity AS sale 
		FROM `sma_sales` 
		JOIN sma_sale_items ON sma_sale_items.sale_id = sma_sales.id 
		JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
		WHERE date BETWEEN '$sdate' AND '$tdate' and sma_products.category_id = '57' AND pos='1' AND sma_sales.pmethod = 'nonchrg') T7 GROUP BY DATE_FORMAT(date,'%Y-%m-%d') ")->result();
		
		$this->data['totalsales']=$this->db->query("SELECT T7.date as tdate,SUM(T7.sale) AS sale FROM(SELECT DATE_FORMAT(sma_sales.date,'%Y-%m-%d') as date,sma_sales.id, sma_sale_items.product_name,sma_sale_items.unit_price,sma_sale_items.quantity,sma_sale_items.unit_price*sma_sale_items.quantity AS sale
			FROM `sma_sales` 
			JOIN sma_sale_items ON sma_sale_items.sale_id = sma_sales.id
			JOIN sma_products ON sma_products.id = sma_sale_items.product_id
			WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate'  and sma_products.category_id = '57' AND  pos='1'  AND pmethod <> 'nonchrg' AND pmethod <> 'compli'
			AND sma_sales.pmethod <> 'nonchrg') T7  GROUP BY DATE_FORMAT(date,'%Y-%m-%d')")->result();
		
$this->data['fromdate'] = $sdate;
$this->data['todate'] = $tdate;
      //$this->load->view($this->theme . 'pos/viewsmrpt', $this->data);
		$this->load->view($this->theme . 'reports/foodcosting', $this->data);
    }
	
		function viewsalesumm($modal = NULL)
    {
        $this->sma->checkPermissions('index');
       if ($this->input->get('sumry_fromdate')) {
            $sdate = $this->input->get('sumry_fromdate');
			$date1 = DateTime::createFromFormat('d/m/Y',$sdate);
			$sdate = $date1->format("Y-m-d")." 06:00:00";
			$roomingdate = $date1->format("Y-m-d");
        } 
		if ($this->input->get('sumry_todate')) {
            $tdate = $this->input->get('sumry_todate');
			 $date2 = DateTime::createFromFormat('d/m/Y',$tdate);
			  $tdate= $date2->format("Y-m-d")." 05:59:59";
			  
        } 
		

        $this->load->helper('text');
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['receptionsales1']=$this->db->query("SELECT sum(grand_total) as totalsales ,sma_sales.id FROM `sma_sales` WHERE
		date BETWEEN '$sdate' AND '$tdate' AND pos = '0' ")->result();
		
		$this->data['receptionsales']=$this->db->query("SELECT sum(amount) as totalsales FROM (SELECT sma_roomstats.room_id,IF(sma_companies.customer_group_id ='5',sma_vouchers.contact_name,sma_companies.name) as customer, IF(sma_companies.customer_group_id ='5',sma_companies.name,'SELF') as company,sma_vouchers.no_adults,sma_vouchers.no_children,sma_vouchers.check_in ,sma_vouchers.check_out,sma_vouchers.total_nights,
CASE
    WHEN sma_categories.code='executive_room' THEN sma_vouchers.executive_rate
    WHEN sma_categories.code='superior_room' THEN  	sma_vouchers.superior_rate
    WHEN sma_categories.code='deluxe_room' THEN  	sma_vouchers.deluxe_rate
    WHEN sma_categories.code='standard_room' THEN  	sma_vouchers.standard_rate
    WHEN sma_categories.code='singles_room' THEN  	sma_vouchers.singles_rate
    ELSE sma_vouchers.twin_rate
 END as amount
FROM `sma_roomstats`
LEFT JOIN sma_sales on sma_sales.id = sma_roomstats.sale_id
LEFT JOIN sma_companies on sma_companies.id = sma_sales.customer_id
LEFT JOIN sma_vouchers ON sma_vouchers.id = sma_sales.voucher_id
LEFT JOIN sma_rooms on sma_rooms.id = sma_roomstats.room_id
LEFT JOIN sma_categories on sma_categories.id = sma_rooms.category_type
WHERE '$roomingdate' >= sma_roomstats.check_in AND  '$roomingdate' < sma_roomstats.check_out )AS T6 RIGHT JOIN sma_rooms ON sma_rooms.id = T6.room_id")->result();


		
		//roomscashsales
		$this->data['receptioncashsales']=$this->db->query("SELECT SUM(t7.amnt) AS amnt FROM (SELECT sma_payments.paid_by,SUM(sma_payments.amount) AS amnt FROM `sma_sales` 
		LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id 
		WHERE sma_payments.paid_by = 'cash' AND pos = '0' AND sma_payments.date BETWEEN '$sdate' AND '$tdate' AND sma_sales.date BETWEEN '$sdate' AND '$tdate'
		GROUP BY sma_payments.id) as t7")->result();
				
		//roomsmpesasales
		$this->data['receptionmpesasales']=$this->db->query("SELECT SUM(t7.amnt) AS amnt FROM (SELECT sma_payments.paid_by,SUM(sma_payments.amount) AS amnt FROM `sma_sales` 
		LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id 
		WHERE sma_payments.paid_by = 'mpesa' AND pos = '0' AND sma_payments.date BETWEEN '$sdate' AND '$tdate' 
		GROUP BY sma_payments.id) as t7")->result();
		
		//roomsmpesasales current day selection sales 
		$this->data['receptionmpesasalescs']=$this->db->query("SELECT SUM(t7.amnt) AS amnt FROM (SELECT sma_payments.paid_by,SUM(sma_payments.amount) AS amnt FROM `sma_sales` 
		LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id 
		WHERE sma_payments.paid_by = 'mpesa' AND pos = '0' AND sma_payments.date BETWEEN '$sdate' AND '$tdate' AND sma_sales.date BETWEEN '$sdate' AND '$tdate'
		GROUP BY sma_payments.id) as t7")->result();
		
		//room bills cleared at reception by mobile money
		$this->data['receptionroommpesasales']=$this->db->query("SELECT sum(sma_reception_payments.amount) AS amnt 
		  FROM  sma_reception_payments
         WHERE sma_reception_payments.paid_by = 'mpesa' and sma_reception_payments.date BETWEEN '$sdate' and '$tdate'")->result();
		
		//roomscredit card
		 $this->data['receptionccsales']=$this->db->query("SELECT SUM(t7.amnt) AS amnt FROM (SELECT sma_payments.paid_by,SUM(sma_payments.amount) AS amnt FROM `sma_sales` 
		LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id 
		WHERE sma_payments.paid_by = 'CC' AND pos = '0' AND sma_payments.date BETWEEN '$sdate' AND '$tdate'
		GROUP BY sma_payments.id) as t7")->result();
		//roomscredit card current day sales
		 $this->data['receptionccsalescs']=$this->db->query("SELECT SUM(t7.amnt) AS amnt FROM (SELECT sma_payments.paid_by,SUM(sma_payments.amount) AS amnt FROM `sma_sales` 
		LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id 
		WHERE sma_payments.paid_by = 'CC' AND pos = '0' AND sma_payments.date BETWEEN '$sdate' AND '$tdate' AND sma_sales.date BETWEEN '$sdate' AND '$tdate'
		GROUP BY sma_payments.id) as t7")->result();
		//room bills cleared at reception by creditcard
		$this->data['receptionroomccsales']=$this->db->query("SELECT sum(sma_reception_payments.amount) AS amnt 
		  FROM  sma_reception_payments
         WHERE sma_reception_payments.paid_by = 'CC' and sma_reception_payments.date BETWEEN '$sdate' and '$tdate'")->result();
		// DEBT PAYMENT
		$this->data['receptiondebtpaymnt']=$this->db->query("SELECT SUM(t7.amnt) AS amnt FROM (SELECT sma_payments.paid_by,SUM(sma_payments.amount) AS amnt FROM `sma_sales` 
		LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id 
		WHERE (sma_payments.paid_by = 'mpesa' OR (sma_payments.paid_by = 'CC') OR (sma_payments.paid_by = 'cash') ) AND pos = '0' AND sma_payments.date BETWEEN '$sdate' AND '$tdate' AND sma_payments.date > sma_sales.date
		GROUP BY sma_payments.id) as t7")->result();
		$this->data['receptiondebtpaymnt_details']=$this->db->query("SELECT * FROM (SELECT sma_payments.paid_by,SUM(sma_payments.amount) AS amnt,sma_payments.date as ddate,sma_users.username as created_by,sma_payments.reference_no,sma_payments.sale_id FROM `sma_sales` 
		LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id 
		LEFT JOIN sma_users on sma_users.id = sma_payments.created_by 
		WHERE (sma_payments.paid_by = 'mpesa' OR (sma_payments.paid_by = 'CC') OR (sma_payments.paid_by = 'cash') ) AND pos = '0' AND sma_payments.date BETWEEN '$sdate' AND '$tdate' AND sma_payments.date > sma_sales.date
		GROUP BY sma_payments.id) as t7")->result();
		
		$this->data['receptionothersales']=$this->db->query("SELECT sma_payments.paid_by,SUM(sma_payments.amount) AS amnt FROM `sma_sales` 
		LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id 
		WHERE sma_payments.paid_by <> 'cash' AND pos = '0' AND sma_payments.date BETWEEN '$sdate' AND '$tdate' ")->result();
//bar
		
				
		$this->data['barsales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM (SELECT sma_sales.grand_total as amnt, sma_sales.id
FROM sma_sales 
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate'
and sma_products.category_id <> '57' AND sma_products.category_id > '53' AND pos = '1' AND pmethod <> 'nonchrg' AND pmethod <> 'compli'
GROUP BY sma_sales.id) as t7 ")->result();
//BAR DUE SALES
$this->data['barduesales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM (SELECT sma_sales.grand_total as amnt, sma_sales.id
FROM sma_sales 
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate'
and sma_products.category_id <> '57' AND sma_products.category_id > '53' AND pos = '1' AND ((pmethod ='')or (pmethod ='rooms') or (pmethod ='staff') or (pmethod='costcenter')  )
GROUP BY sma_sales.id) as t7 ")->result();

$this->data['barcashsales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM(SELECT sma_payments.amount as amnt, sma_sales.id
FROM sma_sales
LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id  
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate'
and sma_payments.paid_by = 'cash' and sma_products.category_id <> '57' AND sma_products.category_id > '53' AND pos = '1' AND pmethod <> 'nonchrg' AND pmethod <> 'compli' 
GROUP BY sma_payments.id ) as t7 ")->result();
//Bar Mpesa Sales
$this->data['barmpesasales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM(SELECT sma_payments.amount as amnt, sma_sales.id
FROM sma_sales
LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id  
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate'
and sma_payments.paid_by = 'mpesa' and sma_products.category_id <> '57' AND sma_products.category_id > '53' AND pos = '1' AND pmethod <> 'nonchrg' AND pmethod <> 'compli' 
GROUP BY sma_payments.id ) as t7 ")->result();
//Bar CC Sales
$this->data['barccsales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM(SELECT sma_payments.amount as amnt, sma_sales.id
FROM sma_sales
LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id  
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate'
and sma_payments.paid_by = 'CC' and sma_products.category_id <> '57' AND sma_products.category_id > '53' AND pos = '1' AND pmethod <> 'nonchrg' AND pmethod <> 'compli' 
GROUP BY sma_payments.id ) as t7 ")->result();
		//room bar bills cleared at reception by cash
$this->data['receptionroombarcashsales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM(SELECT sma_reception_payments.amount as amnt, sma_sales.id
FROM sma_sales
LEFT JOIN sma_reception_payments ON sma_sales.id = sma_reception_payments.sale_id  
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_reception_payments.date BETWEEN '$sdate' AND '$tdate' AND sma_reception_payments.date > sma_sales.date
and (sma_reception_payments.paid_by = 'cash' OR (sma_reception_payments.paid_by = 'mpesa') OR (sma_reception_payments.paid_by = 'CC'))and sma_products.category_id <> '57' AND sma_products.category_id > '53' AND pos = '1' AND pmethod = 'rooms' 
GROUP BY sma_reception_payments.id ) as t7 ")->result();

//Restaurant
		$this->data['restsales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM (SELECT sma_sales.grand_total as amnt, sma_sales.id
FROM sma_sales 
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate'
and sma_products.category_id = '57' AND pos = '1' AND pmethod <> 'nonchrg' AND pmethod <> 'compli'
GROUP BY sma_sales.id) as t7 ")->result();
//restaurant credit sales
$this->data['restcostcentersales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM(SELECT sma_sales.grand_total as amnt, sma_sales.id
FROM sma_sales
LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id  
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate'
and (sma_sales.pmethod = 'rooms' OR (sma_sales.pmethod  = 'costcenter') OR (sma_sales.pmethod  = 'reception')) and sma_products.category_id = '57'  AND pos = '1' AND pmethod <> 'nonchrg' AND pmethod <> 'compli' 
GROUP BY sma_sales.id ) as t7 ")->result();
//restaurant cash sales
$this->data['restcashsales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM(SELECT sma_payments.amount as amnt, sma_sales.id
FROM sma_sales
LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id  
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate'
and sma_payments.paid_by = 'cash' and sma_products.category_id = '57'  AND pos = '1' AND pmethod <> 'nonchrg' AND pmethod <> 'compli' 
GROUP BY sma_payments.id ) as t7 ")->result();
//Restaurant mpesa sales
$this->data['restmpesasales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM(SELECT sma_payments.amount as amnt, sma_sales.id
FROM sma_sales
LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id  
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate'
and sma_payments.paid_by = 'mpesa' and sma_products.category_id = '57'  AND pos = '1' AND pmethod <> 'nonchrg' AND pmethod <> 'compli' 
GROUP BY sma_payments.id ) as t7 ")->result();

//Restaurant cc sales
$this->data['restccsales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM(SELECT sma_payments.amount as amnt, sma_sales.id
FROM sma_sales
LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id  
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate'
and sma_payments.paid_by = 'CC' and sma_products.category_id = '57'  AND pos = '1' AND pmethod <> 'nonchrg' AND pmethod <> 'compli' 
GROUP BY sma_payments.id ) as t7 ")->result();

//room restaurant bills cleared at reception by cash
$this->data['receptionroomrestcashsales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM(SELECT sma_reception_payments.amount as amnt, sma_sales.id
FROM sma_sales
LEFT JOIN sma_reception_payments ON sma_sales.id = sma_reception_payments.sale_id  
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_reception_payments.date BETWEEN '$sdate' AND '$tdate' AND sma_reception_payments.date > sma_sales.date
and sma_products.category_id = '57'  AND pos = '1' AND pmethod = 'rooms' 
GROUP BY sma_reception_payments.id ) as t7 ")->result();

//Delivery/Room Service
		$this->data['servicesales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM (SELECT sma_sale_items.subtotal as amnt, sma_sales.id
FROM sma_sales
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate'
and sma_products.category_id = '53' AND pos = '1' AND pmethod <> 'nonchrg' AND pmethod <> 'compli'
GROUP BY sma_sales.id) as t7 ")->result();

$this->data['servicecashsales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM(SELECT sma_sale_items.subtotal as amnt, sma_sales.id
FROM sma_sales
LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id  
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate'
and sma_payments.paid_by = 'cash' and sma_products.category_id = '53'  AND pos = '1' AND pmethod <> 'nonchrg' AND pmethod <> 'compli' 
GROUP BY sma_payments.id ) as t7 ")->result();
//Service mpesa sales
$this->data['servicempesasales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM(SELECT sma_sale_items.subtotal as amnt, sma_sales.id
FROM sma_sales
LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id  
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate'
and sma_payments.paid_by = 'mpesa' and sma_products.category_id = '53'  AND pos = '1' AND pmethod <> 'nonchrg' AND pmethod <> 'compli' 
GROUP BY sma_payments.id ) as t7 ")->result();

//Service cc sales
$this->data['serviceccsales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM(SELECT sma_sale_items.subtotal as amnt, sma_sales.id
FROM sma_sales
LEFT JOIN sma_payments ON sma_sales.id = sma_payments.sale_id  
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_sales.date BETWEEN '$sdate' AND '$tdate'
and sma_payments.paid_by = 'CC' and sma_products.category_id = '53'  AND pos = '1' AND pmethod <> 'nonchrg' AND pmethod <> 'compli' 
GROUP BY sma_payments.id ) as t7 ")->result();
//Service debt payment cleared by cash at reception
$this->data['receptionroomservicecashsales']=$this->db->query("SELECT sum(t7.amnt) as amnt FROM(SELECT sma_sale_items.subtotal as amnt, sma_sales.id
FROM sma_sales
LEFT JOIN sma_reception_payments ON sma_sales.id = sma_reception_payments.sale_id  
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_reception_payments.date BETWEEN '$sdate' AND '$tdate' AND sma_reception_payments.date > sma_sales.date
and sma_products.category_id = '53'  AND pos = '1' AND pmethod = 'rooms' 
GROUP BY sma_reception_payments.id ) as t7 ")->result();
$this->data['fromdate'] = $sdate;
$this->data['todate'] = $tdate;
      //$this->load->view($this->theme . 'pos/viewsmrpt', $this->data);
		$this->load->view($this->theme . 'reports/viewsalesrpt', $this->data);
    }
    function getSales($warehouse_id = NULL)
    {
        $this->sma->checkPermissions('index',null,'sales');

        if (!$this->Owner && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        $complete_payment = anchor('sales/add_payment/$1', '<i class="fa fa-money" style="line-height: 2.5;"></i> ' . " Complete Payment", 'data-toggle="modal" data-target="#myModal"');
        $detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o" style="line-height: 2.5;"></i> ' . lang('sale_details'));
        $payments_link = anchor('sales/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money" style="line-height: 2.5;"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link1 = anchor('sales/checkinedit/$1', '<i class="fa fa-edit" style="line-height: 2.5;"></i> ' . lang('Edit_Checkin'), 'class="sledit"');
		$edit_link = anchor('sales/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        $pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $return_link = anchor('sales/return_sale/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_sale'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_sale") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_sale') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li >' . $complete_payment .'
			
			</li>
            <li>' . $detail_link . '</li>
            <li>' . $payments_link . '</li>
            <li>' . $add_payment_link . '</li>
			<li>' . $edit_link1 . '</li>
            <li>' . $pdf_link . '</li>
            <li>' . $email_link . '</li>
            <li>' . $delete_link . '</li>
        </ul>
    </div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

       // $this->load->library('datatables');
		$this->load->library('datatables');
      
       // if ($warehouse_id) {
      //      $this->datatables
       //         ->select("sma_sales.id as id, sma_sales.date, sma_sales.reference_no, sma_vouchers.group_name, sma_sales.customer, sma_sales.sale_status, sma_sales.grand_total, sma_sales.paid, (sma_sales.grand_total-sma_sales.paid) as balance, sma_sales.payment_status, sma_sales.currency, sma_vouchers.check_in, sma_vouchers.check_out")
       //         ->from('sma_sales')
       //         ->join('sma_vouchers', 'sma_sales.voucher_id = sma_vouchers.id')
       //         ->where('warehouse_id', $warehouse_id);
        //} else {
            $this->datatables
                ->select("sma_sales.id as id, sma_sales.date, sma_sales.reference_no, sma_rooms.name as group_name, sma_sales.customer, sma_sales.sale_status, sma_sales.grand_total, sma_sales.paid, (sma_sales.grand_total-sma_sales.paid) as balance, sma_sales.payment_status, sma_sales.currency,  sma_vouchers.check_in, sma_vouchers.check_out")
                ->from('sma_sales')
                ->join('sma_vouchers', 'sma_sales.voucher_id = sma_vouchers.id')
				 ->join('sma_rooms', 'sma_sales.room_id = sma_rooms.id')
				->where('sales.payment_status <> "paid"');
				//$this->datatables->order_by('date');
				//;
        //}
		
        $this->datatables->where('pos !=', 1);
       // if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin) {
       //     $this->datatables->where('created_by', $this->session->userdata('user_id'));
       // } elseif ($this->Customer) {
       //     $this->datatables->where('customer_id', $this->session->userdata('user_id'));
      //  }
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }

    function return_sales($warehouse_id = NULL)
    {
        $this->sma->checkPermissions('return_sales',null,'sales');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
        } else {
            $user = $this->site->getUser();
            $this->data['warehouses'] = NULL;
            $this->data['warehouse_id'] = $user->warehouse_id;
            $this->data['warehouse'] = $user->warehouse_id ? $this->site->getWarehouseByID($user->warehouse_id) : NULL;
        }

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('return_sales')));
        $meta = array('page_title' => lang('return_sales'), 'bc' => $bc);
        $this->page_construct('sales/return_sales', $meta, $this->data);
    }

    function getReturns($warehouse_id = NULL)
    {
        $this->sma->checkPermissions('return_sales',null,'sales');

        if (!$this->Owner && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        $detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i>');
        $edit_link = ''; //anchor('sales/edit/$1', '<i class="fa fa-edit"></i>', 'class="reedit"');
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_return_sale") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete_return/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a>";
        $action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $delete_link . '</div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
                ->select($this->db->dbprefix('return_sales') . ".date as date, " . $this->db->dbprefix('return_sales') . ".reference_no as ref, " . $this->db->dbprefix('sales') . ".reference_no as sal_ref, " . $this->db->dbprefix('return_sales') . ".biller, " . $this->db->dbprefix('return_sales') . ".customer, " . $this->db->dbprefix('return_sales') . ".surcharge, " . $this->db->dbprefix('return_sales') . ".grand_total, " . $this->db->dbprefix('return_sales') . ".id as id")
                ->join('sales', 'sales.id=return_sales.sale_id', 'left')
                ->from('return_sales')
                ->group_by('return_sales.id')
                ->where('return_sales.warehouse_id', $warehouse_id);
        } else {
            $this->datatables
                ->select($this->db->dbprefix('return_sales') . ".date as date, " . $this->db->dbprefix('return_sales') . ".reference_no as ref, " . $this->db->dbprefix('sales') . ".reference_no as sal_ref, " . $this->db->dbprefix('return_sales') . ".biller, " . $this->db->dbprefix('return_sales') . ".customer, " . $this->db->dbprefix('return_sales') . ".surcharge, " . $this->db->dbprefix('return_sales') . ".grand_total, " . $this->db->dbprefix('return_sales') . ".id as id")
                ->join('sales', 'sales.id=return_sales.sale_id', 'left')
                ->from('return_sales')
                ->group_by('return_sales.id');
        }
       // if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin) {
        //    $this->datatables->where('return_sales.created_by', $this->session->userdata('user_id'));
       // } elseif ($this->Customer) {
        //    $this->datatables->where('return_sales.customer_id', $this->session->userdata('customer_id'));
       // }
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }

    function modal_view($id = NULL)
    {
        $this->sma->checkPermissions('index',true,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getInvoiceByID($id);
        //$this->sma->view_rights($inv->created_by, TRUE);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);

        $this->load->view($this->theme.'sales/modal_view', $this->data);
    }

    function view($id = NULL)
    {
       $this->sma->checkPermissions('index',null,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getInvoiceByID($id);
        //$this->sma->view_rights($inv->created_by);
		$this->data['qcategory'] = $this->site->getCategoryByID($inv->category_id);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
		$this->data['pax'] = $this->sales_model->getPaxfrominvoice($id);
		
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
		$this->data['room'] = $this->site->getRoombyId($inv->room_id);
        $this->data['inv'] = $inv;
        $this->data['invoice_items'] = $this->sales_model->getSaleInvoiceItemsBySaleId($id);
        $voucher = $this->vouchers_model->getVoucherByID($inv->voucher_id);
        $this->data['voucher'] = $voucher;
        $this->data['biller'] = $this->companies_model->getBillerByID($voucher->biller_id);
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
        //$this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        $this->data['paypal'] = $this->sales_model->getPaypalSettings();
        $this->data['skrill'] = $this->sales_model->getSkrillSettings();

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('view')));
        $meta = array('page_title' => lang('view_sales_details'), 'bc' => $bc);
        $this->page_construct('sales/view', $meta, $this->data);
    }

    function view_return($id = NULL)
    {
        $this->sma->checkPermissions('return_sales',null,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getReturnByID($id);
       // $this->sma->view_rights($inv->created_by);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['rows'] = $this->sales_model->getAllReturnItems($id);
        $this->data['sale'] = $this->sales_model->getInvoiceByID($inv->sale_id);
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('view_return')));
        $meta = array('page_title' => lang('view_return_details'), 'bc' => $bc);
        $this->page_construct('sales/view_return', $meta, $this->data);
    }

    function pdf($id = NULL, $view = NULL, $save_bufffer = NULL)
    {
        $this->sma->checkPermissions('pdf',null,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $inv = $this->sales_model->getInvoiceByID($id);
        //$this->sma->view_rights($inv->created_by);
		$this->data['qcategory'] = $this->site->getCategoryByID($inv->category_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
		$this->data['pax'] = $this->sales_model->getPaxfrominvoice($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['invoice_items'] = $this->sales_model->getSaleInvoiceItemsBySaleId($id);
        $voucher = $this->vouchers_model->getVoucherByID($inv->voucher_id);
        $this->data['voucher'] = $voucher;
        $this->data['biller'] = $this->companies_model->getBillerByID($voucher->biller_id);
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        if($inv->sale_status=="pending"){
            $this->data['title'] ="Invoice";
        }  else {
            $this->data['title'] ="Sale";
        }
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
        $this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;

        $name = "Sales_Invoice" . "_" . str_replace('/', '_', $inv->reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'sales/pdf', $this->data, TRUE);
        if ($view) {
            $this->load->view($this->theme . 'sales/pdf', $this->data);
        } elseif ($save_bufffer) {
            return $this->sma->generate_pdf($html, $name, $save_bufffer, "Confirmed By ". $this->site->getUser($svoucher->booked_by)->first_name . ' ' . $this->site->getUser($svoucher->booked_by)->last_name .' '. $this->data['biller']->invoice_footer);
        } else {
            $this->sma->generate_pdf($html, $name, FALSE, "Confirmed By ". $this->site->getUser($svoucher->booked_by)->first_name . ' ' . $this->site->getUser($svoucher->booked_by)->last_name .' '. $this->data['biller']->invoice_footer);
        }
    }

    function pdf_payment($id = NULL, $view = NULL, $save_bufffer = NULL)
    {
        $this->sma->checkPermissions('payments',null,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $payment = $this->sales_model->getPaymentByID($id);
        $inv = $this->sales_model->getInvoiceByID($payment->sale_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $voucher = $this->vouchers_model->getVoucherByID($inv->voucher_id);
        $this->data['inv'] = $inv;
        $this->data['voucher'] = $voucher;
        $this->data['payment'] = $payment;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);


        $name = lang("Receipt") . "_" . str_replace('/', '_', $payment->reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'sales/pdf_payment', $this->data, TRUE);
        if ($view) {
            $this->load->view($this->theme . 'sales/pdf_payment', $this->data);
        } elseif ($save_bufffer) {
            return $this->sma->generate_pdf($html, $name, $save_bufffer, "Received with thanks.");
        } else {
            $this->sma->generate_pdf($html, $name, FALSE, "Received with thanks.");
        }
    }

    function email($id = NULL)
    {
        $this->sma->checkPermissions('email',true,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $inv = $this->sales_model->getInvoiceByID($id);
        $this->form_validation->set_rules('to', lang("to") . " " . lang("email"), 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', lang("subject"), 'trim|required');
        $this->form_validation->set_rules('cc', lang("cc"), 'trim');
        $this->form_validation->set_rules('bcc', lang("bcc"), 'trim');
        $this->form_validation->set_rules('note', lang("message"), 'trim');

        if ($this->form_validation->run() == true) {
           // $this->sma->view_rights($inv->created_by);
            $to = $this->input->post('to');
            $subject = $this->input->post('subject');
            if ($this->input->post('cc')) {
                $cc = $this->input->post('cc');
            } else {
                $cc = NULL;
            }
            if ($this->input->post('bcc')) {
                $bcc = $this->input->post('bcc');
            } else {
                $bcc = NULL;
            }
            $customer = $this->site->getCompanyByID($inv->customer_id);
            $this->load->library('parser');
            $parse_data = array(
                'reference_number' => $inv->reference_no,
                'contact_person' => $customer->name,
                'company' => $customer->company,
                'site_link' => base_url(),
                'site_name' => $this->Settings->site_name,
                'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',
                'footer' => '<img src="' . base_url() . 'assets/uploads/logos/footerimage.png" alt="' . $this->Settings->site_name . '"/>'
            );
            $msg = $this->input->post('note');
            $message = $this->parser->parse_string($msg, $parse_data);

            $biller = $this->site->getCompanyByID($inv->biller_id);
            $paypal = $this->sales_model->getPaypalSettings();
            $skrill = $this->sales_model->getSkrillSettings();
            $btn_code = '<div id="payment_buttons" class="text-center margin010"> </div>';
            $message = $message . $btn_code;

            $attachment = $this->pdf($id, NULL, 'S');
        } elseif ($this->input->post('send_email')) {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->session->set_flashdata('error', $this->data['error']);
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->sma->send_email($to, $subject, $message, NULL, NULL, $attachment, $cc, $bcc)) {
            delete_files($attachment);
            $this->session->set_flashdata('message', lang("email_sent"));
            redirect("sales");
        } else {

            if (file_exists('./themes/' . $this->theme . '/views/email_templates/sale.html')) {
                $sale_temp = file_get_contents('themes/' . $this->theme . '/views/email_templates/sale.html');
            } else {
                $sale_temp = file_get_contents('./themes/default/views/email_templates/sale.html');
            }

            $this->data['subject'] = array('name' => 'subject',
                'id' => 'subject',
                'type' => 'text',
                'value' => $this->form_validation->set_value('subject', lang('invoice').' (' . $inv->reference_no . ') '.lang('from').' ' . $this->Settings->site_name),
            );
            $this->data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'text',
                'value' => $this->form_validation->set_value('note', $sale_temp),
            );
            $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);

            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'sales/email', $this->data);
        }
    }

    /* ------------------------------------------------------------------ */


    function email_payment($id = NULL)
    {
        $this->sma->checkPermissions('pdf',true,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $payment = $this->sales_model->getPaymentByID($id);
        $inv = $this->sales_model->getInvoiceByID($payment->sale_id);
        $this->form_validation->set_rules('to', lang("to") . " " . lang("email"), 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', lang("subject"), 'trim|required');
        $this->form_validation->set_rules('cc', lang("cc"), 'trim');
        $this->form_validation->set_rules('bcc', lang("bcc"), 'trim');
        $this->form_validation->set_rules('note', lang("message"), 'trim');

        if ($this->form_validation->run() == true) {
            // $this->sma->view_rights($inv->created_by);
            $to = $this->input->post('to');
            $subject = $this->input->post('subject');
            if ($this->input->post('cc')) {
                $cc = $this->input->post('cc');
            } else {
                $cc = NULL;
            }
            if ($this->input->post('bcc')) {
                $bcc = $this->input->post('bcc');
            } else {
                $bcc = NULL;
            }
            $customer = $this->site->getCompanyByID($inv->customer_id);
            $this->load->library('parser');
            $parse_data = array(
                'reference_number' => $payment->reference_no,
                'contact_person' => $customer->name,
                'company' => $customer->company,
                'site_link' => base_url(),
                'site_name' => $this->Settings->site_name,
                'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',
                'footer' => '<img src="' . base_url() . 'assets/uploads/logos/footerimage.png" alt="' . $this->Settings->site_name . '"/>'
            );
            $msg = $this->input->post('note');
            $message = $this->parser->parse_string($msg, $parse_data);

            $biller = $this->site->getCompanyByID($inv->biller_id);
            $paypal = $this->sales_model->getPaypalSettings();
            $skrill = $this->sales_model->getSkrillSettings();
            $btn_code = '<div id="payment_buttons" class="text-center margin010"> </div>';
            $message = $message . $btn_code;

            $attachment = $this->pdf_payment($id, NULL, 'S');
        } elseif ($this->input->post('send_email')) {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->session->set_flashdata('error', $this->data['error']);
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->sma->send_email($to, $subject, $message, NULL, NULL, $attachment, $cc, $bcc)) {
            delete_files($attachment);
            $this->session->set_flashdata('message', lang("email_sent"));
            redirect("sales");
        } else {

            if (file_exists('./themes/' . $this->theme . '/views/email_templates/payment_temp.html')) {
                $sale_temp = file_get_contents('themes/' . $this->theme . '/views/email_templates/payment_temp.html');
            } else {
                $sale_temp = file_get_contents('./themes/default/views/email_templates/payment_temp.html');
            }

            $this->data['subject'] = array('name' => 'subject',
                'id' => 'subject',
                'type' => 'text',
                'value' => $this->form_validation->set_value('subject', lang('payment').' (' . $payment->reference_no . ') '.lang('from').' ' . $this->Settings->site_name),
            );
            $this->data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'text',
                'value' => $this->form_validation->set_value('note', $sale_temp),
            );
            $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);

            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'sales/email_payment', $this->data);
        }
    }

    /* -------------------------------------------------------------------------------------------------------------------------------- */

    function edit($id = NULL)
    {
        $this->sma->checkPermissions('edit',true,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->form_validation->set_rules('expiry_date', 'Expiry Date required', 'required');
        $this->form_validation->set_rules('currency', 'Currency required', 'required');
        $sale = $this->sales_model->getSaleByID($id);
        $voucher_data = $this->vouchers_model->getVoucherByID($sale->voucher_id);
        if ($this->form_validation->run() == true) {
            $customer = $this->companies_model->getCompanyByID($voucher_data->customer_id);
            $biller = $this->companies_model->getCompanyByID($voucher_data->biller_id);
            $total = $this->sma->formatDecimal($this->input->post('total')*$voucher_data->total_nights);
            $vat = $this->sma->formatDecimal(($total*24)/100);
            $service_charge = $this->sma->formatDecimal(($total*2)/100);
            $tourism_levy = $this->sma->formatDecimal(($total*2)/100);

			    $data = array(
                'customer_id'=> $this->input->post('chkincustomer'),
                'voucher_no'=> $sale->voucher_id,//$this->input->post('voucher_number'),
                'hotel_id'=> '4',//$this->input->post('warehouse')
                'biller_id'=> '259', //$this->input->post('biller')
                'status'=> 'Complete', //$this->input->post('status'
                'date_in'=> $this->input->post('chkindate_edit'),
                'check_in'=> $this->input->post('chkindate_edit'),
                'check_out'=> $this->input->post('check_out_edit'),
                'total_nights'=> $diff->format("%a")+1-1,
                'residence'=> 'Resident',//$this->input->post('residence'),
                'remarks'=> 'Remarks',//$this->input->post('remarks'),
                'no_children'=> $this->input->post('no_children_edit'),
                'no_adults'=> $this->input->post('no_adults_edit'),
                'executive_room'=> $this->input->post('executive_room60')?$this->input->post('executive_room60'):0,
                'superior_room'=> $this->input->post('superior_room61')?$this->input->post('superior_room61'):0,
                'deluxe_room'=> $this->input->post('deluxe_room62')?$this->input->post('deluxe_room62'):0,
                'standard_room'=> $this->input->post('standard_room63')?$this->input->post('standard_room63'):0,
                'singles_room'=> $this->input->post('singles_room64')?$this->input->post('singles_room64'):0,
                'twin_room'=> $this->input->post('twin_room65')?$this->input->post('twin_room65'):0,
                'meal_plan'=> $this->input->post('meal_plan'),
                'extra_bed'=> $this->input->post('extra_bed'),
                'contact_name'=> 'contact_name',//$this->input->post('contact_name'),
                'contact_phone'=> 'contact_phone',//$this->input->post('contact_phone'),
                'contact_email'=> 'contact_email',//$this->input->post('contact_email'),

            );
        }

        if ($this->form_validation->run() == true && $this->sales_model->deleteSaleLeavePayments($id)) {
            $sid = $this->sales_model->addSaleInvoice($data);
            $ref_data = array(
                'reference_no' => 'E00'.$sid,
            );
            $this->sales_model->updateSaleOnly($sid, $ref_data);
            if($this->input->post('single_qnty')){
                $this->input->post('single_pax');
                $this->input->post('single_price');
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'singles_room',
                    'quantity' => $this->input->post('single_qnty'),
                    'price' => $this->input->post('single_price'),
                    'no_adults' => $this->input->post('single_adults'),
                    'no_children' => $this->input->post('single_children'),
                    'pax' => $this->input->post('single_pax'),
                    'total' => $this->input->post('single_qnty')*$this->input->post('single_price'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('double_qnty')){
                $this->input->post('double_pax');
                $this->input->post('double_price');
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'executive_room',
                    'quantity' => $this->input->post('double_qnty'),
                    'price' => $this->input->post('double_price'),
                    'no_adults' => $this->input->post('double_adults'),
                    'no_children' => $this->input->post('double_children'),
                    'pax' => $this->input->post('double_pax'),
                    'total' => $this->input->post('double_qnty')*$this->input->post('double_price'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('twin_qnty')){
                $this->input->post('twin_pax');
                $this->input->post('twin_price');
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'twin_room',
                    'quantity' => $this->input->post('twin_qnty'),
                    'price' => $this->input->post('twin_price'),
                    'no_adults' => $this->input->post('twin_adults'),
                    'no_children' => $this->input->post('twin_children'),
                    'pax' => $this->input->post('twin_pax'),
                    'total' => $this->input->post('twin_qnty')*$this->input->post('twin_price'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('triple_qnty')){
                $this->input->post('triple_pax');
                $this->input->post('triple_price');
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'superior_room',
                    'quantity' => $this->input->post('triple_qnty'),
                    'price' => $this->input->post('triple_price'),
                    'no_adults' => $this->input->post('triple_adults'),
                    'no_children' => $this->input->post('triple_children'),
                    'pax' => $this->input->post('triple_pax'),
                    'total' => $this->input->post('triple_qnty')*$this->input->post('triple_price'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('honeymoon_qnty')){
                $this->input->post('honeymoon_pax');
                $this->input->post('honeymoon_price');
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'standard_room',
                    'quantity' => $this->input->post('honeymoon_qnty'),
                    'price' => $this->input->post('honeymoon_price'),
                    'no_adults' => $this->input->post('honeymoon_adults'),
                    'no_children' => $this->input->post('honeymoon_children'),
                    'pax' => $this->input->post('honeymoon_pax'),
                    'total' => $this->input->post('honeymoon_qnty')*$this->input->post('honeymoon_price'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            
            if($this->input->post('family_qnty')){
                $this->input->post('family_pax');
                $this->input->post('family_price');
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'deluxe_room',
                    'quantity' => $this->input->post('family_qnty'),
                    'price' => $this->input->post('family_price'),
                    'no_adults' => $this->input->post('family_adults'),
                    'no_children' => $this->input->post('family_children'),
                    'pax' => $this->input->post('family_pax'),
                    'total' => $this->input->post('family_qnty')*$this->input->post('family_price'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('extra_bed_qnty')){
                $this->input->post('extra_bed_pax');
                $this->input->post('extra_bed_price');
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'extrabed',
                    'quantity' => $this->input->post('extra_bed_qnty'),
                    'price' => $this->input->post('extra_bed_price'),
                    'no_adults' => $this->input->post('extra_bed_adults'),
                    'no_children' => $this->input->post('extra_bed_children'),
                    'pax' => $this->input->post('extra_bed_pax'),
                    'total' => $this->input->post('extra_bed_qnty')*$this->input->post('extra_bed_price'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }

            $this->session->set_flashdata('message', lang("sale_edited"));
            redirect("sales");
        } else {
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['voucher'] = $voucher_data;
            $this->data['sale'] = $sale;
            $this->data['customers'] = $this->vouchers_model->getCompanyByGroupID(5);
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['billers'] = $this->site->getAllCompanies("biller");
            $this->data['categories'] = $this->site->getAllCategories();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('sales')));
            $meta = array('page_title' => lang('sales'), 'bc' => $bc);
            $this->page_construct('sales/edit', $meta, $this->data);
        }
    }

    /* ------------------------------- */

	function checkinedit($id = NULL)
    {
        $this->sma->checkPermissions('edit',true,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
	
        $this->form_validation->set_rules('chkincustomer_edit', 'chkincustomer', 'required');
       
        $this->form_validation->set_rules('chkindate_edit', 'Check In Date', 'required');
        $this->form_validation->set_rules('check_out_edit', 'Check Out Date', 'required');
        $this->form_validation->set_rules('no_adults_edit', 'No of Adults', 'required');
		$this->form_validation->set_rules('billtoroom_edit', 'Bill to Room', 'required');
  
        $date1 = date_create($this->input->post('chkindate_edit'));
        $date2 = date_create($this->input->post('check_out_edit'));
        $diff = date_diff($date1,$date2);
        if($date1>$date2){
            $this->session->set_flashdata('error', 'Check in date cannot be after check out date');
            redirect('sales/checkinedit/'.$id);
        }
		$vcode = $this->site->getVouchermaxcode();
		
        $sale = $this->sales_model->getSaleByID($id);
        $voucher_data = $this->vouchers_model->getVoucherByID($sale->voucher_id);
        if ($this->form_validation->run() == true) {
            $customer = $this->companies_model->getCompanyByID($voucher_data->customer_id);
            $biller = $this->companies_model->getCompanyByID($voucher_data->biller_id);
            $total = $this->sma->formatDecimal($this->input->post('total')*$voucher_data->total_nights);
            $vat = $this->sma->formatDecimal(($total*18)/100);
			
            //$service_charge = $this->sma->formatDecimal(($total*2)/100);
            //$tourism_levy = $this->sma->formatDecimal(($total*2)/100);
            $data = array(
                'customer_id'=> $this->input->post('chkincustomer_edit'),
                'voucher_no'=> $sale->voucher_id,//$this->input->post('voucher_number'),
                'hotel_id'=> '4',//$this->input->post('warehouse')
                'biller_id'=> '259', //$this->input->post('biller')
                'status'=> 'Complete', //$this->input->post('status'
                'date_in'=> $this->input->post('chkindate_edit'),
                'check_in'=> $this->input->post('chkindate_edit'),
                'check_out'=> $this->input->post('check_out_edit'),
                'total_nights'=> $diff->format("%a")+1-1,
                'residence'=> 'Resident',//$this->input->post('residence'),
                'remarks'=> 'Remarks',//$this->input->post('remarks'),
                'no_children'=> $this->input->post('no_children_edit'),
                'no_adults'=> $this->input->post('no_adults_edit'),
                'executive_room'=> $this->input->post('executive_room60_edit')?$this->input->post('executive_room60_edit'):0,
				'executive_rate'=> $this->input->post('rate_edit60')?$this->input->post('rate_edit60'):0,
				'executive_roomnos'=> $this->input->post('chkinrms_edit60')?$this->input->post('chkinrms_edit60'):0,
				'executive_adults'=> $this->input->post('adult_edit60')?$this->input->post('adult_edit60'):0,
                'superior_room'=> $this->input->post('superior_room61_edit')?$this->input->post('superior_room61_edit'):0,
				'superior_rate'=> $this->input->post('rate_edit61')?$this->input->post('rate_edit61'):0,
				'superior_roomnos'=> $this->input->post('chkinrms_edit61')?$this->input->post('chkinrms_edit61'):0,
				'superior_adult'=> $this->input->post('adult_edit61')?$this->input->post('adult_edit61'):0,
                'deluxe_room'=> $this->input->post('deluxe_room62_edit')?$this->input->post('deluxe_room62_edit'):0,
				'deluxe_rate'=> $this->input->post('rate_edit62')?$this->input->post('rate_edit62'):0,
				'deluxe_roomnos'=> $this->input->post('chkinrms_edit62')?$this->input->post('chkinrms_edit62'):0,
				'deluxe_adults'=> $this->input->post('adult_edit62')?$this->input->post('adult_edit62'):0,
                'standard_room'=> $this->input->post('standard_room63_edit')?$this->input->post('standard_room63_edit'):0,
				'standard_rate'=> $this->input->post('rate_edit63')?$this->input->post('rate_edit63'):0,
				'standard_roomnos'=> $this->input->post('chkinrms_edit63')?$this->input->post('chkinrms_edit63'):0,
				'standard_adult'=> $this->input->post('adult_edit63')?$this->input->post('adult_edit63'):0,
                'singles_room'=> $this->input->post('singles_room64_edit')?$this->input->post('singles_room64_edit'):0,
				'singles_rate'=> $this->input->post('rate_edit64')?$this->input->post('rate_edit64'):0,
				'single_roomnos'=> $this->input->post('chkinrms_edit64')?$this->input->post('chkinrms_edit64'):0,
				'single_adult'=> $this->input->post('adult_edit64')?$this->input->post('adult_edit64'):0,
                'twin_room'=> $this->input->post('twin_room65_edit')?$this->input->post('twin_room65_edit'):0,
				'twin_rate'=> $this->input->post('rate_edit65')?$this->input->post('rate_edit65'):0,
				'twin_roomnos'=> $this->input->post('chkinrms_edit65')?$this->input->post('chkinrms_edit65'):0,
				'twin_adult'=> $this->input->post('adult_edit65')?$this->input->post('adult_edit65'):0,
                'meal_plan'=> $this->input->post('meal_plan_edit'),
                'extra_bed'=> $this->input->post('extra_bed'),
                'contact_name'=> $this->input->post('contact_name_edit'),//$this->input->post('contact_name'),
                'contact_phone'=> $this->input->post('contact_phone_edit'),//$this->input->post('contact_phone'),
                'contact_email'=> $this->input->post('contact_email_edit'),//$this->input->post('contact_email'),

            );
			
			 
		}
		
        
        if ($this->form_validation->run() == true && $this->vouchers_model->updateVoucher($sale->voucher_id, $data)) {
          //  $sid = $this->sales_model->addSaleInvoice($data);
		    
          $this->sales_model->deleteSaleLeavePayments($id, $this->input->post('chkindate_edit'));
			$voucher_data = $this->vouchers_model->getVoucherByID($sale->voucher_id);
            $customer = $this->companies_model->getCompanyByID($voucher_data->customer_id);
            $biller = $this->companies_model->getBillerByID($voucher_data->biller_id);
			$total = $this->sma->formatDecimal($this->input->post('totalrpday_edit')*$voucher_data->total_nights);
            $vat = $this->sma->formatDecimal(($total*18)/100);
            $data2 = array(
                'updated_at' => date('Y-m-d H:i:s'),
                'reference_no' => time(),
                'voucher_id' => $voucher_data->id,
                'customer_id' => $voucher_data->customer_id,
                'customer' => $customer->name,
                'currency' => $this->input->post('currency_edit'),
                'residence' => $voucher_data->residence,
                'biller_id' => $voucher_data->biller_id,
                'biller' => $biller->name,
				'room_id' => $this->input->post('billtoroom_edit'),
                'no_of_rooms' => $this->input->post('totalrms_edit'),
                'warehouse_id' => $voucher_data->hotel_id,
                'category_id' => 54,
                'chkindate' => $voucher_data->check_in,
                'chkoutdate' => $voucher_data->check_out,
                'note' => $voucher_data->remarks,
                'staff_note' =>  $voucher_data->remarks,
                'total' => $this->sma->formatDecimal($this->input->post('totalrpday_edit')*$voucher_data->total_nights),
                'product_discount' => $this->sma->formatDecimal(0),
                'order_discount_id' => null,
                'order_discount' => $sale->order_discount,
                'total_discount' => $sale->total_discount,
                'product_tax' => $this->sma->formatDecimal(0),
                'order_tax_id' => null,
                'order_tax' => $sale->order_tax,
                'total_tax' => $sale->total_tax,
                'shipping' => $this->sma->formatDecimal(0),
                'grand_total' => $this->sma->formatDecimal($this->input->post('totalrpday_edit')*$voucher_data->total_nights),
                'total_items' => $sale->total_items,
                'sale_status' => $sale->sale_status,
                'payment_status' => $sale->payment_status,
                'payment_term' => $sale->payment_term,
                'due_date' => $this->input->post('chkindate_edit'),
                'paid' => $sale->paid,
                'updated_by' => $this->session->userdata('user_id')
            );
			 $this->sales_model->updateSaleOnly($id, $data2);
			  
            if($this->input->post('deluxe_room62_edit') != NULL && $this->input->post('deluxe_room62_edit') !='0' ){
              
                $invoice_item = array(
                    'sale_id' => $id,
                    'name' => 'deluxe_room',
                    'quantity' => $this->input->post('deluxe_room62_edit'),
                    'price' => $this->input->post('rate_edit62'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate_edit62'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('singles_room64_edit') != NULL && $this->input->post('singles_room64_edit') !='0'){
               
                $invoice_item = array(
                    'sale_id' => $id,
                    'name' => 'singles_room',
                    'quantity' => $this->input->post('singles_room64_edit'),
                    'price' => $this->input->post('rate_edit64'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate_edit64'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
          
			
            if($this->input->post('standard_room63_edit') != NULL && $this->input->post('standard_room63_edit') !='0'){
               $invoice_item = array(
                    'sale_id' => $id,
                    'name' => 'standard_room',
                    'quantity' => $this->input->post('standard_room63_edit'),
                    'price' => $this->input->post('rate_edit63'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate_edit63'),

                );
				
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            
            if( $this->input->post('superior_room61_edit') != NULL && $this->input->post('superior_room61_edit') !='0'){
              $invoice_item = array(
                    'sale_id' => $id,
                    'name' => 'superior_room',
                    'quantity' => $this->input->post('superior_room61_edit'),
                    'price' => $this->input->post('rate_edit61'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate_edit61'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('twin_room65_edit') != NULL && $this->input->post('twin_room65_edit') != '0'){
               $invoice_item = array(
                    'sale_id' => $id,
                    'name' => 'twin_room',
                    'quantity' => $this->input->post('twin_room65_edit'),
                    'price' => $this->input->post('rate_edit65'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate_edit65'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            
            
		if($this->input->post('chkinrms_edit62') != NULL && $this->input->post('chkinrms_edit62') !='0'){ //deluxe
				$chkrm62 = $this->input->post('chkinrms_edit62');
				$chkindate = $this->input->post('chkindate_edit');
                $check_out= $this->input->post('check_out_edit');
				$customer_id= $this->input->post('chkincustomer_edit');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm62,$chkindate,$check_out,$customer_id,$id);
				}
		if($this->input->post('chkinrms_edit60') != NULL && $this->input->post('chkinrms_edit60') !='0'){ //deluxe
				$chkrm60 = $this->input->post('chkinrms_edit60');
				$chkindate = $this->input->post('chkindate_edit');
                $check_out= $this->input->post('check_out_edit');
				$customer_id= $this->input->post('chkincustomer_edit');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm60,$chkindate,$check_out,$customer_id,$id);
				}
		if($this->input->post('chkinrms_edit64') != NULL &&  $this->input->post('chkinrms_edit64') !='0'){ //deluxe
				$chkrm64 = $this->input->post('chkinrms_edit64');
				$chkindate = $this->input->post('chkindate_edit');
                $check_out= $this->input->post('check_out_edit');
				$customer_id= $this->input->post('chkincustomer_edit');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm64,$chkindate,$check_out,$customer_id,$id);
				}
		if($this->input->post('chkinrms_edit63') != NULL &&   $this->input->post('chkinrms_edit63') !='0'){ //deluxe
				$chkrm63 = $this->input->post('chkinrms_edit63');
				$chkindate = $this->input->post('chkindate_edit');
                $check_out= $this->input->post('check_out_edit');
				$customer_id= $this->input->post('chkincustomer_edit');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm63,$chkindate,$check_out,$customer_id,$id);
				}		
		if($this->input->post('chkinrms_edit61') != NULL && $this->input->post('chkinrms_edit61') !='0'){ //deluxe
				$chkrm61 = $this->input->post('chkinrms_edit61');
				$chkindate = $this->input->post('chkindate_edit');
                $check_out= $this->input->post('check_out_edit');
				$customer_id= $this->input->post('chkincustomer_edit');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm61,$chkindate,$check_out,$customer_id,$id);
				}	
		if($this->input->post('chkinrms_edit65') != NULL && $this->input->post('chkinrms_edit65') !='0'){ //deluxe
				$chkrm65 = $this->input->post('chkinrms_edit65');
				$chkindate = $this->input->post('chkindate_edit');
                $check_out= $this->input->post('check_out_edit');
				$customer_id= $this->input->post('chkincustomer_edit');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm65,$chkindate,$check_out,$customer_id,$id);
				}
            $this->session->set_flashdata('message', lang("checkin_added"));
            redirect('sales/view/'.$id);
		
        } else {
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['voucher'] = $voucher_data;
            $this->data['sale'] = $sale;
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['customers'] = $this->site->getAllCompanies("customer");
            $this->data['warehouses'] = $this->site->getAllWarehouses();
			$this->data['room'] = $this->site->getRoombyId($inv->room_id);
            $this->data['billers'] = $this->site->getAllCompanies("biller");
            $this->data['categories'] = $this->site->getAllInvCategories();
			$this->data['currencies'] = $this->site->getAllCurrencies();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('vouchers')));
            $meta = array('page_title' => lang('vouchers'), 'bc' => $bc);
            $this->page_construct('vouchers/checkinedit', $meta, $this->data);
        }
    }


	
    function return_sale($id = NULL)
    {
       $this->sma->checkPermissions('return_sales',null,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        // $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paying_by"), 'required');

        if ($this->form_validation->run() == true) {
            $sale = $this->sales_model->getInvoiceByID($id);
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('re');
            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }

            $return_surcharge = $this->input->post('return_surcharge') ? $this->input->post('return_surcharge') : 0;
            $note = $this->sma->clear_tags($this->input->post('note'));

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_type = $_POST['product_type'][$r];
                $item_code = $_POST['product_code'][$r];
                $item_name = $_POST['product_name'][$r];
                $sale_item_id = $_POST['sale_item_id'][$r];
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : NULL;
                //$option_details = $this->sales_model->getProductOptionByID($item_option);
                $real_unit_price = $this->sma->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price = $this->sma->formatDecimal($_POST['unit_price'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $item_serial = isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : NULL;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : NULL;

                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->sales_model->getProductByCode($item_code) : NULL;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = (($this->sma->formatDecimal($unit_price)) * (Float)($pds[0])) / 100;
                        } else {
                            $pr_discount = $this->sma->formatDecimal($discount);
                        }
                    } else {
                        $pr_discount = 0;
                    }
                    $unit_price = $this->sma->formatDecimal($unit_price - $pr_discount);
                    $pr_item_discount = $this->sma->formatDecimal($pr_discount * $item_quantity);
                    $product_discount += $pr_item_discount;

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if (!$product_details->tax_method) {
                                $item_tax = $this->sma->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->sma->formatDecimal((($unit_price) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            }

                        } elseif ($tax_details->type == 2) {

                            $item_tax = $this->sma->formatDecimal($tax_details->rate);
                            $tax = $tax_details->rate;

                        }
                        $pr_item_tax = $this->sma->formatDecimal($item_tax * $item_quantity);

                    } else {
                        $pr_tax = 0;
                        $pr_item_tax = 0;
                        $tax = "";
                    }

                    $item_net_price = $product_details->tax_method ? $this->sma->formatDecimal($unit_price-$pr_discount) : $this->sma->formatDecimal($unit_price-$item_tax-$pr_discount);
                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_price * $item_quantity) + $pr_item_tax);

                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_price' => $item_net_price,
                        // 'unit_price' => $this->sma->formatDecimal($item_net_price + $item_tax),
                        'quantity' => $item_quantity,
                        'warehouse_id' => $sale->warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->sma->formatDecimal($subtotal),
                        'serial_no' => $item_serial,
                        'real_unit_price' => $real_unit_price,
                        'sale_item_id' => $sale_item_id
                    );

                    $total += $item_net_price * $item_quantity;
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            if ($this->input->post('discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->sma->formatDecimal((($total + $product_tax) * (Float)($ods[0])) / 100);
                } else {
                    $order_discount = $this->sma->formatDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = NULL;
            }
            $total_discount = $order_discount + $product_discount;

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->sma->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->sma->formatDecimal((($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = NULL;
            }

            $total_tax = $this->sma->formatDecimal($product_tax + $order_tax);
            $grand_total = $this->sma->formatDecimal($this->sma->formatDecimal($total) + $total_tax - $this->sma->formatDecimal($return_surcharge) - $order_discount);
            $data = array('date' => $date,
                'sale_id' => $id,
                'reference_no' => $reference,
                'customer_id' => $sale->customer_id,
                'customer' => $sale->customer,
                'biller_id' => $sale->biller_id,
                'biller' => $sale->biller,
                'warehouse_id' => $sale->warehouse_id,
                'note' => $note,
                'total' => $this->sma->formatDecimal($total),
                'product_discount' => $this->sma->formatDecimal($product_discount),
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $this->sma->formatDecimal($product_tax),
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'surcharge' => $this->sma->formatDecimal($return_surcharge),
                'grand_total' => $grand_total,
                'created_by' => $this->session->userdata('user_id'),
            );
            if ($this->input->post('amount-paid') && $this->input->post('amount-paid') != 0) {
                $payment = array(
                    'date' => $date,
                    'reference_no' => $this->input->post('payment_reference_no'),
                    'amount' => $this->sma->formatDecimal($this->input->post('amount-paid')),
                    'paid_by' => $this->input->post('paid_by'),
                    'cheque_no' => $this->input->post('cheque_no'),
                    'cc_no' => $this->input->post('pcc_no'),
                    'cc_holder' => $this->input->post('pcc_holder'),
                    'cc_month' => $this->input->post('pcc_month'),
                    'cc_year' => $this->input->post('pcc_year'),
                    'cc_type' => $this->input->post('pcc_type'),
                    'created_by' => $this->session->userdata('user_id'),
                    'type' => 'returned'
                );
            } else {
                $payment = array();
            }

            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }

            // $this->sma->print_arrays($data, $products, $payment);
        }

        if ($this->form_validation->run() == true && $this->sales_model->returnSale($data, $products, $payment)) {
            $this->session->set_flashdata('message', lang("return_sale_added"));
            redirect("sales/return_sales");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['inv'] = $this->sales_model->getInvoiceByID($id);
            if ($this->data['inv']->sale_status != 'completed') {
                $this->session->set_flashdata('error', lang("sale_status_x_competed"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
            $inv_items = $this->sales_model->getAllInvoiceItems($id);
            $c = rand(100000, 9999999);
            foreach ($inv_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
                if (!$row) {
                    $row = json_decode('{}');
                    $row->tax_method = 0;
                    $row->quantity = 0;
                } else {
                    unset($row->details, $row->product_details, $row->cost, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                }
                $pis = $this->sales_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                $row->id = $item->product_id;
                $row->sale_item_id = $item->id;
                $row->code = $item->product_code;
                $row->name = $item->product_name;
                $row->type = $item->product_type;
                $row->qty = $item->quantity;
                $row->oqty = $item->quantity;
                $row->discount = $item->discount ? $item->discount : '0';
                $row->price = $this->sma->formatDecimal($item->net_unit_price+$this->sma->formatDecimal($item->item_discount/$item->quantity));
                $row->unit_price = $row->tax_method ? $item->unit_price+$this->sma->formatDecimal($item->item_discount/$item->quantity)+$this->sma->formatDecimal($item->item_tax/$item->quantity) : $item->unit_price+($item->item_discount/$item->quantity);
                $row->real_unit_price = $item->real_unit_price;
                $row->tax_rate = $item->tax_rate_id;
                $row->serial = $item->serial_no;
                $row->option = $item->option_id;
                $options = $this->sales_model->getProductOptions($row->id, $item->warehouse_id, TRUE);
                $ri = $this->Settings->item_addition ? $row->id : $c;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options);
                } else {
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options);
                }
                $c++;
            }
            $this->data['inv_items'] = json_encode($pr);
            $this->data['id'] = $id;
            $this->data['payment_ref'] = $this->site->getReference('pay');
            $this->data['reference'] = ''; // $this->site->getReference('re');
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('return_sale')));
            $meta = array('page_title' => lang('return_sale'), 'bc' => $bc);
            $this->page_construct('sales/return_sale', $meta, $this->data);
        }
    }


    /* ------------------------------- */

    function delete($id = NULL)
    {
        $this->sma->checkPermissions('delete',true,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->sales_model->deleteSale($id)) {
            if($this->input->is_ajax_request()) {
                echo lang("sale_deleted"); die();
            }
            $this->session->set_flashdata('message', lang('sale_deleted'));
            redirect('welcome');
        }
    }

    function delete_return($id = NULL)
    {
        $this->sma->checkPermissions('delete',true,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->sales_model->deleteReturn($id)) {
            if($this->input->is_ajax_request()) {
                echo lang("return_sale_deleted"); die();
            }
            $this->session->set_flashdata('message', lang('return_sale_deleted'));
            redirect('welcome');
        }
    }

    function sale_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');
//die(print_r($_POST));
        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->sales_model->deleteSale($id);
                    }
                    $this->session->set_flashdata('message', lang("sales_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
if ($this->input->post('form_action') == 'bulk_payment') {
  
    if($this->input->post('paid_by')=="selectmethod"){
       $this->session->set_flashdata('error', lang('select_payment_method'));
            redirect($_SERVER["HTTP_REFERER"]);  
    }
     $totalamount=0;
      $date = date('Y-m-d H:i:s');
      $paymentmethod=$this->input->post('paid_by');
      if(strtolower($paymentmethod)=="mpesa"){
           $mpesareference=$this->input->post('reference');
      }else if(strtolower($paymentmethod)=="costcenter"){
            $costcenterref=$this->input->post('reference');
      }
      else if(strtolower($paymentmethod)=="cheque"){
            $chequeref=$this->input->post('reference');
      }
        else if(strtolower($paymentmethod)=="cc"){
            $ccref=$this->input->post('reference');
      }
     
                    foreach ($_POST['val'] as $id) {
                        $salesinvoice=$this->sales_model->getInvoiceByID($id);
                       
                        if($salesinvoice->payment_status=="due"){
                     $payment = array(
                'date' => $date,
                'sale_id' => $id,
                'reference_no' => $salesinvoice->reference_no,
                'amount' =>$salesinvoice->grand_total,
                'bill_change' =>0 ,
                'paid_by' => $this->input->post('paid_by'),
                
                'chef_id' => $salesinvoice->chef_id,
                'chef' => $salesinvoice->chef,
                
                'cashier_id' => $salesinvoice->cashier_id,
                'cashier' => $salesinvoice->cashier,
                
                'mpesa_transaction_no' => @$mpesareference,
                'cost_center_no' => @$costcenterref,
                'cheque_no' =>@$chequeref,
                'cc_no' =>@$ccref,// $this->input->post('paid_by') == 'gift_card' ? $this->input->post('gift_card_no') : $this->input->post('pcc_no'),
                'cc_holder' => "",
                'cc_month' => "",
                'cc_year' => "",
                'cc_type' => "",
               // 'cc_cvv2' =>"",
                'note' =>"",
                'created_by' => $this->session->userdata('user_id'),
                'type' => 'received'
            );
                     
                     $msg = $this->sales_model->addPayment($payment);
                        
                    }
                    }
                    $this->session->set_flashdata('message', lang("payments_confirmed"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                if ($this->input->post('form_action') == 'export_excel' || 
                        $this->input->post('form_action') == 'export_pdf'|| 
                        $this->input->post('form_action') == 'bulk_payment') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('sales'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('biller'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('grand_total'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('paid'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('payment_status'));
                     $bulk_array=array();
                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sale = $this->sales_model->getInvoiceByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->sma->hrld($sale->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sale->reference_no);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sale->biller);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $sale->customer);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $sale->grand_total);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $sale->paid);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $sale->payment_status);
                        array_push($bulk_array,$sale);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'sales_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');
                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }
                   
                    if ($this->input->post('form_action') == 'bulk_payment'){
                        
                       //print_r($bulk_array);
                      
                     }
                    
                    
                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_sale_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

//     function bulk_payment($sale_id = NULL, $modal = NULL)
//    {
      
//                       
//                        print_r($bulk_rows);
//                      // die(); 
//                       $this->session->set_flashdata('error',  $_POST['val']);
//                //redirect($_SERVER["HTTP_REFERER"]); 
//                        return 'sales/bulk_payment/'.$_POST['val'];
//                    }
//         
//        $this->load->helper('text');
//        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
//        $this->data['message'] = $this->session->flashdata('message');
//        $this->data['rows'] = $this->pos_model->getAllInvoiceItems($sale_id);
//        $inv = $this->pos_model->getInvoiceByID($sale_id);
//        $biller_id = $inv->biller_id;
//        $customer_id = $inv->customer_id;
//        $this->data['biller'] = $this->pos_model->getCompanyByID($biller_id);
//        $this->data['customer'] = $this->pos_model->getCompanyByID($customer_id);
//        $this->data['payments'] = $this->pos_model->getInvoicePayments($sale_id);
//        $this->data['pos'] = $this->pos_model->getSetting();
//        $this->data['barcode'] = $this->barcode($inv->reference_no, 'code39', 30);
//        $this->data['inv'] = $inv;
//        $this->data['sid'] = $sale_id;
//        $this->data['modal'] = $modal;
//        $this->data['page_title'] = $this->lang->line("invoice");
//     $this->load->view($this->theme . 'pos/view_complete', $this->data);
//    }
    /* ------------------------------- */

    function deliveries()
    {
        $this->sma->checkPermissions('deliveries',null,'sales');

        $data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('deliveries')));
        $meta = array('page_title' => lang('deliveries'), 'bc' => $bc);
        $this->page_construct('sales/deliveries', $meta, $this->data);

    }

    function getDeliveries()
    {
        $this->sma->checkPermissions('deliveries',null,'sales');

        $detail_link = anchor('sales/view_delivery/$1', '<i class="fa fa-file-text-o"></i> ' . lang('delivery_details'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('sales/email_delivery/$1', '<i class="fa fa-envelope"></i> ' . lang('email_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('sales/edit_delivery/$1', '<i class="fa fa-edit"></i> ' . lang('edit_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $pdf_link = anchor('sales/pdf_delivery/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_delivery") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete_delivery/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_delivery') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
    <ul class="dropdown-menu pull-right" role="menu">
        <li>' . $detail_link . '</li>
        <li>' . $edit_link . '</li>
        <li>' . $pdf_link . '</li>
        <li>' . $delete_link . '</li>
    </ul>
</div></div>';

        $this->load->library('datatables');
        //GROUP_CONCAT(CONCAT('Name: ', sale_items.product_name, ' Qty: ', sale_items.quantity ) SEPARATOR '<br>')
        $this->datatables
            ->select("deliveries.id as id, date, do_reference_no, sale_reference_no, customer, address")
            ->from('deliveries')
            ->join('sale_items', 'sale_items.sale_id=deliveries.sale_id', 'left')
            ->group_by('deliveries.id');
        $this->datatables->add_column("Actions", $action, "id");

        echo $this->datatables->generate();
    }

    function pdf_delivery($id = NULL, $view = NULL, $save_bufffer = NULL)
    {
        $this->sma->checkPermissions('pdf-delivery',null,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $deli = $this->sales_model->getDeliveryByID($id);

        $this->data['delivery'] = $deli;
        $sale = $this->sales_model->getInvoiceByID($deli->sale_id);
        $this->data['biller'] = $this->site->getCompanyByID($sale->biller_id);
        $this->data['rows'] = $this->sales_model->getAllInvoiceItemsWithDetails($deli->sale_id);
        $this->data['user'] = $this->site->getUser($deli->created_by);


        $name = lang("delivery") . "_" . str_replace('/', '_', $deli->do_reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'sales/pdf_delivery', $this->data, TRUE);
        if ($view) {
            $this->load->view($this->theme . 'sales/pdf_delivery', $this->data);
        } elseif ($save_bufffer) {
            return $this->sma->generate_pdf($html, $name, $save_bufffer);
        } else {
            $this->sma->generate_pdf($html, $name);
        }
    }

    function view_delivery($id = NULL)
    {
        $this->sma->checkPermissions('deliveries',null,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $deli = $this->sales_model->getDeliveryByID($id);

        $this->data['delivery'] = $deli;
        $sale = $this->sales_model->getInvoiceByID($deli->sale_id);
        $this->data['biller'] = $this->site->getCompanyByID($sale->biller_id);
        $this->data['rows'] = $this->sales_model->getAllInvoiceItemsWithDetails($deli->sale_id);
        $this->data['user'] = $this->site->getUser($deli->created_by);
        $this->data['page_title'] = lang("delivery_order");

        $this->load->view($this->theme . 'sales/view_delivery', $this->data);
    }

    function add_delivery($id = NULL)
    {
        $this->sma->checkPermissions('add_delivery',null,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        //$this->form_validation->set_rules('do_reference_no', lang("do_reference_no"), 'required');
        $this->form_validation->set_rules('sale_reference_no', lang("sale_reference_no"), 'required');
        $this->form_validation->set_rules('customer', lang("customer"), 'required');
        $this->form_validation->set_rules('address', lang("address"), 'required');

        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $dlDetails = array(
                'date' => $date,
                'sale_id' => $this->input->post('sale_id'),
                'do_reference_no' => $this->input->post('do_reference_no') ? $this->input->post('do_reference_no') : $this->site->getReference('do'),
                'sale_reference_no' => $this->input->post('sale_reference_no'),
                'customer' => $this->input->post('customer'),
                'address' => $this->input->post('address'),
                'note' => $this->sma->clear_tags($this->input->post('note')),
                'created_by' => $this->session->userdata('user_id')
            );
        } elseif ($this->input->post('add_delivery')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }


        if ($this->form_validation->run() == true && $this->sales_model->addDelivery($dlDetails)) {
            $this->session->set_flashdata('message', lang("delivery_added"));
            redirect("sales/deliveries");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $sale = $this->sales_model->getInvoiceByID($id);
            $this->data['customer'] = $this->site->getCompanyByID($sale->customer_id);
            $this->data['inv'] = $sale;
            $this->data['do_reference_no'] = ''; //$this->site->getReference('do');
            $this->data['modal_js'] = $this->site->modal_js();

            $this->load->view($this->theme . 'sales/add_delivery', $this->data);
        }
    }

    function edit_delivery($id = NULL)
    {
        $this->sma->checkPermissions('edit_delivery',null,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('do_reference_no', lang("do_reference_no"), 'required');
        $this->form_validation->set_rules('sale_reference_no', lang("sale_reference_no"), 'required');
        $this->form_validation->set_rules('customer', lang("customer"), 'required');
        $this->form_validation->set_rules('address', lang("address"), 'required');
        //$this->form_validation->set_rules('note', lang("note"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            $dlDetails = array(
                'sale_id' => $this->input->post('sale_id'),
                'do_reference_no' => $this->input->post('do_reference_no'),
                'sale_reference_no' => $this->input->post('sale_reference_no'),
                'customer' => $this->input->post('customer'),
                'address' => $this->input->post('address'),
                'note' => $this->sma->clear_tags($this->input->post('note')),
                'created_by' => $this->session->userdata('user_id')
            );

            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
                $dlDetails['date'] = $date;
            }
        } elseif ($this->input->post('edit_delivery')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }


        if ($this->form_validation->run() == true && $this->sales_model->updateDelivery($id, $dlDetails)) {
            $this->session->set_flashdata('message', lang("delivery_updated"));
            redirect("sales/deliveries");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));


            $this->data['delivery'] = $this->sales_model->getDeliveryByID($id);
            $this->data['modal_js'] = $this->site->modal_js();

            $this->load->view($this->theme . 'sales/edit_delivery', $this->data);
        }
    }

    function delete_delivery($id = NULL)
    {
        $this->sma->checkPermissions('delete_delivery',true,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->sales_model->deleteDelivery($id)) {
            echo lang("delivery_deleted");
        }

    }

    function delivery_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->sales_model->deleteDelivery($id);
                    }
                    $this->session->set_flashdata('message', lang("deliveries_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('deliveries'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('do_reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('sale_reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('address'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $delivery = $this->sales_model->getDeliveryByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->sma->hrld($delivery->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $delivery->do_reference_no);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $delivery->sale_reference_no);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $delivery->customer);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $delivery->address);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);

                    $filename = 'deliveries_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_delivery_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    /* -------------------------------------------------------------------------------- */


    function payments($id = NULL)
    {
        $this->sma->checkPermissions('payments',true,'sales');
        $this->data['payments'] = $this->sales_model->getInvoicePayments($id);
        $this->load->view($this->theme . 'sales/payments', $this->data);
    }

    function payment_note($id = NULL)
    {
        $payment = $this->sales_model->getPaymentByID($id);
        $inv = $this->sales_model->getInvoiceByID($payment->sale_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $voucher = $this->vouchers_model->getVoucherByID($inv->voucher_id);
        $this->data['inv'] = $inv;
        $this->data['voucher'] = $voucher;
        $this->data['payment'] = $payment;
        $this->data['page_title'] = $this->lang->line("payment_note");

        $this->load->view($this->theme . 'sales/payment_notee', $this->data);
    }

    function add_payment($id = NULL)
    {
        $this->sma->checkPermissions('payments',true,'sales');
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        //$this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $inv = $this->sales_model->getInvoiceByID($this->input->post('sale_id'));
            $payment = array(
                'date' => $date,
                'sale_id' => $this->input->post('sale_id'),
                'reference_no' => $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('pay'),
                'amount' => $this->input->post('amount-paid'),
                'currency' => $inv->currency,
                'paid_by' => $this->input->post('paid_by'),
                'cheque_no' => $this->input->post('cheque_no'),
                'cc_no' => $this->input->post('paid_by') == 'gift_card' ? $this->input->post('gift_card_no') : $this->input->post('pcc_no'),
                'cc_holder' => $this->input->post('pcc_holder'),
                'cc_month' => $this->input->post('pcc_month'),
                'cc_year' => $this->input->post('pcc_year'),
                'cc_type' => $this->input->post('pcc_type'),
                'note' => $this->input->post('note'),
                'balance' => ($inv->grand_total-$inv->paid)-$this->input->post('amount-paid'),
                'created_by' => $this->session->userdata('user_id'),
                'type' => 'received'
            );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $payment['attachment'] = $photo;
            }

            //$this->sma->print_arrays($payment);

        } elseif ($this->input->post('add_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
		$biller_id = $inv->biller_id;

        if ($this->form_validation->run() == true && $this->sales_model->addPayment($payment)) {
            //$this->session->set_flashdata('message', lang("payment_added"));
            //redirect($_SERVER["HTTP_REFERER"]);
			$saleid = $this->input->post('sale_id');
			$result=$this->db->query("SELECT sma_payments.*,sma_sales.grand_total,sma_users.username,sma_rooms.name as roomname,sma_categories.name as catname, sma_vouchers.total_nights,sma_vouchers.meal_plan,sma_sales.customer FROM sma_payments 
				LEFT JOIN sma_users
				ON sma_users.id = sma_payments.created_by
				LEFT JOIN sma_sales 
				ON sma_sales.id = sma_payments.sale_id
				LEFT JOIN sma_rooms
				ON sma_rooms.id = sma_sales.room_id
				LEFT JOIN sma_categories
				ON sma_categories.id = sma_rooms.category_type
				LEFT JOIN sma_vouchers
				ON sma_vouchers.voucher_no = sma_sales.voucher_id
			WHERE sma_payments.id = (SELECT MAX(sma_payments.id) FROM sma_payments) AND sale_id = '$saleid' ")->result();
			
			$this->data['payment'] = $result;
			$this->data['biller'] = $this->pos_model->getCompanyByID($biller_id);
			$this->load->view($this->theme . 'sales/payment_notee', $this->data);
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $sale = $this->sales_model->getInvoiceByID($id);
            $this->data['inv'] = $sale;
            $this->data['payment_ref'] = ''; //$this->site->getReference('pay');
            $this->data['modal_js'] = $this->site->modal_js();

            $this->load->view($this->theme . 'sales/add_payment', $this->data);
        }
    }

    function checkin_out($id = NULL)
    {
        $this->sma->checkPermissions('payments',true,'sales');
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        //$this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $payment = array(
                'date' => $date,
                'sale_id' => $this->input->post('sale_id'),
                'reference_no' => $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('pay'),
                'amount' => $this->input->post('amount-paid'),
                'paid_by' => $this->input->post('paid_by'),
                'cheque_no' => $this->input->post('cheque_no'),
                'cc_no' => $this->input->post('paid_by') == 'gift_card' ? $this->input->post('gift_card_no') : $this->input->post('pcc_no'),
                'cc_holder' => $this->input->post('pcc_holder'),
                'cc_month' => $this->input->post('pcc_month'),
                'cc_year' => $this->input->post('pcc_year'),
                'cc_type' => $this->input->post('pcc_type'),
                'note' => $this->input->post('note'),
                'created_by' => $this->session->userdata('user_id'),
                'type' => 'received'
            );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $payment['attachment'] = $photo;
            }

            //$this->sma->print_arrays($payment);

        } elseif ($this->input->post('add_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }


        if ($this->form_validation->run() == true && $this->sales_model->addPayment($payment)) {
            $this->session->set_flashdata('message', lang("payment_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $sale = $this->sales_model->getInvoiceByID($id);
            $this->data['inv'] = $sale;
            $this->data['payment_ref'] = ''; //$this->site->getReference('pay');
            $this->data['modal_js'] = $this->site->modal_js();

            $this->load->view($this->theme . 'sales/checkin_out', $this->data);
        }
    }

    function edit_payment($id = NULL)
    {
        $this->sma->checkPermissions('payments',true,'sales');
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        //$this->form_validation->set_rules('note', lang("note"), 'xss_clean');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $payment = array(
                'date' => $date,
                'sale_id' => $this->input->post('sale_id'),
                'reference_no' => $this->input->post('reference_no'),
                'amount' => $this->input->post('amount-paid'),
                'paid_by' => $this->input->post('paid_by'),
                'cheque_no' => $this->input->post('cheque_no'),
                'cc_no' => $this->input->post('pcc_no'),
                'cc_holder' => $this->input->post('pcc_holder'),
                'cc_month' => $this->input->post('pcc_month'),
                'cc_year' => $this->input->post('pcc_year'),
                'cc_type' => $this->input->post('pcc_type'),
                'note' => $this->input->post('note'),
                'created_by' => $this->session->userdata('user_id')
            );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $payment['attachment'] = $photo;
            }

            //$this->sma->print_arrays($payment);

        } elseif ($this->input->post('edit_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }


        if ($this->form_validation->run() == true && $this->sales_model->updatePayment($id, $payment)) {
            $this->session->set_flashdata('message', lang("payment_updated"));
            redirect("sales");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['payment'] = $this->sales_model->getPaymentByID($id);
            $this->data['modal_js'] = $this->site->modal_js();

            $this->load->view($this->theme . 'sales/edit_payment', $this->data);
        }
    }

    function delete_payment($id = NULL)
    {
        $this->sma->checkPermissions('delete',true,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->sales_model->deletePayment($id)) {
            //echo lang("payment_deleted");
            $this->session->set_flashdata('message', lang("payment_deleted"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    /* --------------------------------------------------------------------------------------------- */

    function suggestions()
    {
        $term = $this->input->get('term', TRUE);
        $warehouse_id = $this->input->get('warehouse_id', TRUE);
        $customer_id = $this->input->get('customer_id', TRUE);

        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");
        }

        $spos = strpos($term, ' ');
        if ($spos !== false) {
            $st = explode(" ", $term);
            $sr = trim($st[0]);
            $option = trim($st[1]);
        } else {
            $sr = $term;
            $option = '';
        }
        $customer = $this->site->getCompanyByID($customer_id);
        $customer_group = $this->site->getCustomerGroupByID($customer->customer_group_id);
        $rows = $this->sales_model->getProductNames($sr, $warehouse_id);
		//print_r($rows);
		//die();
        if ($rows) {
            foreach ($rows as $row) {
                $option = FALSE;
                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
                $row->serial = '';
                $options = $this->sales_model->getProductOptions($row->id, $warehouse_id);
                if ($options) {
                    $opt = $options[0];
                    if (!$option) {
                        $option = $opt->id;
                    }
                } else {
                    $opt = json_decode('{}');
                    $opt->price = 0;
                }
                $row->option = $option;
                $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                        if($pis){
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }
                if ($opt->price != 0) {
                    $row->price = $opt->price + (($opt->price * $customer_group->percent) / 100);
                } else {
                    $row->price = $row->price + (($row->price * $customer_group->percent) / 100);
                }
                $row->real_unit_price = $row->price;
                $row->r_type =  $customer->rtype;
                $combo_items = FALSE;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $warehouse_id);
                    }
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options);
                } else {
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options);
                }
            }

            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

    /* ------------------------------------ Gift Cards ---------------------------------- */

    function gift_cards()
    {
        $this->sma->checkPermissions('gift_cards',null,'sales');

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('gift_cards')));
        $meta = array('page_title' => lang('gift_cards'), 'bc' => $bc);
        $this->page_construct('sales/gift_cards', $meta, $this->data);
    }

    function getGiftCards()
    {
        $this->sma->checkPermissions('gift_cards',null,'sales');
        $this->load->library('datatables');
        $this->datatables
            ->select($this->db->dbprefix('gift_cards') . ".id as id, card_no, value, balance, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name) as created_by, customer, expiry", FALSE)
            ->join('users', 'users.id=gift_cards.created_by', 'left')
            ->from("gift_cards")
            ->add_column("Actions", "<center><a href='" . site_url('sales/edit_gift_card/$1') . "' class='tip' title='" . lang("edit_gift_card") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_gift_card") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete_gift_card/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }

    function validate_gift_card($no)
    {
        //$this->sma->checkPermissions();
        if ($gc = $this->site->getGiftCardByNO($no)) {
            if ($gc->expiry) {
                if ($gc->expiry >= date('Y-m-d')) {
                    echo json_encode($gc);
                } else {
                    echo json_encode(false);
                }
            } else {
                echo json_encode($gc);
            }
        } else {
            echo json_encode(false);
        }
    }

    function add_gift_card()
    {
        $this->sma->checkPermissions('add_gift_card',true,'sales');

        $this->form_validation->set_rules('card_no', lang("card_no"), 'trim|is_unique[gift_cards.card_no]|required');
        $this->form_validation->set_rules('value', lang("value"), 'required');

        if ($this->form_validation->run() == true) {
            $customer_details = $this->input->post('customer') ? $this->site->getCompanyByID($this->input->post('customer')) : NULL;
            $customer = $customer_details ? $customer_details->company : NULL;
            $data = array('card_no' => $this->input->post('card_no'),
                'value' => $this->input->post('value'),
                'customer_id' => $this->input->post('customer') ? $this->input->post('customer') : NULL,
                'customer' => $customer,
                'balance' => $this->input->post('value'),
                'expiry' => $this->input->post('expiry') ? $this->sma->fsd($this->input->post('expiry')) : NULL,
                'created_by' => $this->session->userdata('user_id')
            );
            $sa_data = array();
            $ca_data = array();
            if ($this->input->post('staff_points')) {
                $sa_points = $this->input->post('sa_points');
                $user = $this->site->getUser($this->input->post('user'));
                if ($user->award_points < $sa_points) {
                    $this->session->set_flashdata('error', lang("award_points_wrong"));
                    redirect("sales/gift_cards");
                }
                $sa_data = array('user' => $user->id, 'points' => ($user->award_points - $sa_points));
            } elseif ($customer_details && $this->input->post('use_points')) {
                $ca_points = $this->input->post('ca_points');
                if ($customer_details->award_points < $ca_points) {
                    $this->session->set_flashdata('error', lang("award_points_wrong"));
                    redirect("sales/gift_cards");
                }
                $ca_data = array('customer' => $customer->id, 'points' => ($customer_details->award_points - $ca_points));
            }
        } elseif ($this->input->post('add_gift_card')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("sales/gift_cards");
        }

        if ($this->form_validation->run() == true && $this->sales_model->addGiftCard($data, $ca_data, $sa_data)) {
            $this->session->set_flashdata('message', lang("gift_card_added"));
            redirect("sales/gift_cards");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['users'] = $this->sales_model->getStaff();
            $this->data['page_title'] = lang("new_gift_card");
            $this->load->view($this->theme . 'sales/add_gift_card', $this->data);
        }
    }

    function edit_gift_card($id = NULL)
    {
        $this->sma->checkPermissions('edit_gift_card',true,'sales');

        $this->form_validation->set_rules('card_no', lang("card_no"), 'trim|required');
        $gc_details = $this->site->getGiftCardByID($id);
        if ($this->input->post('card_no') != $gc_details->card_no) {
            $this->form_validation->set_rules('card_no', lang("card_no"), 'is_unique[gift_cards.card_no]');
        }
        $this->form_validation->set_rules('value', lang("value"), 'required');
        //$this->form_validation->set_rules('customer', lang("customer"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $gift_card = $this->site->getGiftCardByID($id);
            $customer_details = $this->input->post('customer') ? $this->site->getCompanyByID($this->input->post('customer')) : NULL;
            $customer = $customer_details ? $customer_details->company : NULL;
            $data = array('card_no' => $this->input->post('card_no'),
                'value' => $this->input->post('value'),
                'customer_id' => $this->input->post('customer') ? $this->input->post('customer') : NULL,
                'customer' => $customer,
                'balance' => ($this->input->post('value') - $gift_card->value) + $gift_card->balance,
                'expiry' => $this->input->post('expiry') ? $this->sma->fsd($this->input->post('expiry')) : NULL,
            );
        } elseif ($this->input->post('edit_gift_card')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("sales/gift_cards");
        }

        if ($this->form_validation->run() == true && $this->sales_model->updateGiftCard($id, $data)) {
            $this->session->set_flashdata('message', lang("gift_card_updated"));
            redirect("sales/gift_cards");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['gift_card'] = $this->site->getGiftCardByID($id);
            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'sales/edit_gift_card', $this->data);
        }
    }

    function sell_gift_card()
    {
        $this->sma->checkPermissions('gift_card',null,'sales');
        $error = NULL;
        $gcData = $this->input->get('gcdata');
        if (empty($gcData[0])) {
            $error = lang("value") . " " . lang("is_required");
        }
        if (empty($gcData[1])) {
            $error = lang("card_no") . " " . lang("is_required");
        }


        $customer_details = (!empty($gcData[2])) ? $this->site->getCompanyByID($gcData[2]) : NULL;
        $customer = $customer_details ? $customer_details->company : NULL;
        $data = array('card_no' => $gcData[0],
            'value' => $gcData[1],
            'customer_id' => (!empty($gcData[2])) ? $gcData[2] : NULL,
            'customer' => $customer,
            'balance' => $gcData[1],
            'expiry' => (!empty($gcData[3])) ? $this->sma->fsd($gcData[3]) : NULL,
            'created_by' => $this->session->userdata('username')
        );

        if (!$error) {
            if ($this->sales_model->addGiftCard($data)) {
                echo json_encode(array('result' => 'success', 'message' => lang("gift_card_added")));
            }
        } else {
            echo json_encode(array('result' => 'failed', 'message' => $error));
        }

    }

    function delete_gift_card($id = NULL)
    {
        $this->sma->checkPermissions('delete_gift_cards',true,'sales');

        if ($this->sales_model->deleteGiftCard($id)) {
            echo lang("gift_card_deleted");
        }
    }

    function gift_card_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->sales_model->deleteGiftCard($id);
                    }
                    $this->session->set_flashdata('message', lang("gift_cards_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('gift_cards'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('card_no'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('value'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('customer'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sc = $this->site->getGiftCardByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $sc->card_no);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sc->value);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sc->customer);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'gift_cards_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_gift_card_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function get_award_points($id = NULL)
    {
        $this->sma->checkPermissions('index',null,'sales');

        $row = $this->site->getUser($id);
        echo json_encode(array('sa_points' => $row->award_points));
    }

    /* -------------------------------------------------------------------------------------- */

    function sale_by_csv()
    {
       
        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', $this->lang->line("upload_file"), 'xss_clean');
        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        $this->form_validation->set_rules('customer', lang("customer"), 'required');
        $this->form_validation->set_rules('biller', lang("biller"), 'required');
        $this->form_validation->set_rules('sale_status', lang("sale_status"), 'required');
        $this->form_validation->set_rules('payment_status', lang("payment_status"), 'required');

        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('so');
            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
            $biller_id = $this->input->post('biller');
            $total_items = $this->input->post('total_items');
            $sale_status = $this->input->post('sale_status');
            $payment_status = $this->input->post('payment_status');
            $payment_term = $this->input->post('payment_term');
            $due_date = $payment_term ? date('Y-m-d', strtotime('+' . $payment_term . ' days')) : NULL;
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = $customer_details->company ? $customer_details->company : $customer_details->name;
            $biller_details = $this->site->getCompanyByID($biller_id);
            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note = $this->sma->clear_tags($this->input->post('note'));
            $staff_note = $this->sma->clear_tags($this->input->post('staff_note'));

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');

                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("sales/sale_by_csv");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen($this->digital_upload_path . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('code', 'net_unit_price', 'quantity', 'variant', 'item_tax_rate', 'discount', 'serial');
                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                $rw = 2;
                foreach ($final as $csv_pr) {

                    if (isset($csv_pr['code']) && isset($csv_pr['net_unit_price']) && isset($csv_pr['quantity'])) {

                        if ($product_details = $this->sales_model->getProductByCode($csv_pr['code'])) {

                            if ($csv_pr['variant']) {
                                $item_option = $this->sales_model->getProductVariantByName($csv_pr['variant'], $product_details->id);
                                if (!$item_option) {
                                    $this->session->set_flashdata('error', lang("pr_not_found") . " ( " . $product_details->name . " - " . $csv_pr['variant'] . " ). " . lang("line_no") . " " . $rw);
                                    redirect($_SERVER["HTTP_REFERER"]);
                                }
                            } else {
                                $item_option = json_decode('{}');
                                $item_option->id = NULL;
                            }

                                $item_id = $product_details->id;
                                $item_type = $product_details->type;
                                $item_code = $product_details->code;
                                $item_name = $product_details->name;
                                $item_net_price = $this->sma->formatDecimal($csv_pr['net_unit_price']);
                                $item_quantity = $csv_pr['quantity'];
                                $item_tax_rate = $csv_pr['item_tax_rate'];
                                $item_discount = $csv_pr['discount'];
                                $item_serial = $csv_pr['serial'];

                                if (isset($item_code) && isset($item_net_price) && isset($item_quantity)) {
                                    $product_details = $this->sales_model->getProductByCode($item_code);

                                    if (isset($item_discount)) {
                                        $discount = $item_discount;
                                        $dpos = strpos($discount, $percentage);
                                        if ($dpos !== false) {
                                            $pds = explode("%", $discount);
                                            $pr_discount = (($this->sma->formatDecimal($item_net_price)) * (Float)($pds[0])) / 100;
                                        } else {
                                            $pr_discount = $this->sma->formatDecimal($discount);
                                        }
                                    } else {
                                        $pr_discount = 0;
                                    }
                                    $item_net_price = $this->sma->formatDecimal($item_net_price - $pr_discount);
                                    $pr_item_discount = $this->sma->formatDecimal($pr_discount * $item_quantity);
                                    $product_discount += $pr_item_discount;

                                    if (isset($item_tax_rate) && $item_tax_rate != 0) {

                                        if($tax_details = $this->sales_model->getTaxRateByName($item_tax_rate)) {
                                            $pr_tax = $tax_details->id;
                                            if ($tax_details->type == 1) {

                                                $item_tax = $this->sma->formatDecimal((($item_net_price) * $tax_details->rate) / 100);
                                                $tax = $tax_details->rate . "%";

                                            } elseif ($tax_details->type == 2) {
                                                $item_tax = $this->sma->formatDecimal($tax_details->rate);
                                                $tax = $tax_details->rate;
                                            }
                                            $pr_item_tax = $this->sma->formatDecimal($item_tax * $item_quantity);
                                        } else {
                                            $this->session->set_flashdata('error', lang("tax_not_found") . " ( " . $item_tax_rate . " ). " . lang("line_no") . " " . $rw);
                                            redirect($_SERVER["HTTP_REFERER"]);
                                        }

                                    } elseif ($product_details->tax_rate) {

                                        $pr_tax = $product_details->tax_rate;
                                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                                        if ($tax_details->type == 1) {

                                            $item_tax = $this->sma->formatDecimal((($item_net_price) * $tax_details->rate) / 100);
                                            $tax = $tax_details->rate . "%";

                                        } elseif ($tax_details->type == 2) {

                                            $item_tax = $this->sma->formatDecimal($tax_details->rate);
                                            $tax = $tax_details->rate;

                                        }
                                        $pr_item_tax = $this->sma->formatDecimal($item_tax * $item_quantity);

                                    } else {
                                        $item_tax = 0;
                                        $pr_tax = 0;
                                        $pr_item_tax = 0;
                                        $tax = "";
                                    }
                                    $product_tax += $pr_item_tax;

                                    $subtotal = (($item_net_price * $item_quantity) + $pr_item_tax);
                                    $products[] = array(
                                        'product_id' => $item_id,
                                        'product_code' => $item_code,
                                        'product_name' => $item_name,
                                        'product_type' => $item_type,
                                        'option_id' => $item_option->id,
                                        'net_unit_price' => $item_net_price,
                                        'unit_price' => $this->sma->formatDecimal($item_net_price + $item_tax),
                                        'quantity' => $item_quantity,
                                        'warehouse_id' => $warehouse_id,
                                        'item_tax' => $pr_item_tax,
                                        'tax_rate_id' => $pr_tax,
                                        'tax' => $tax,
                                        'discount' => $item_discount,
                                        'item_discount' => $pr_item_discount,
                                        'subtotal' => $this->sma->formatDecimal($subtotal),
                                        'serial_no' => $item_serial,
                                        'unit_price' => $this->sma->formatDecimal($item_net_price + $item_tax + $pr_discount),
                                    );

                                    $total += $item_net_price * $item_quantity;
                                }

                        } else {
                            $this->session->set_flashdata('error', $this->lang->line("pr_not_found") . " ( " . $csv_pr['code'] . " ). " . $this->lang->line("line_no") . " " . $rw);
                            redirect($_SERVER["HTTP_REFERER"]);
                        }
                        $rw++;
                    }

                }
            }

            if ($this->input->post('order_discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->sma->formatDecimal((($total + $product_tax) * (Float)($ods[0])) / 100);
                } else {
                    $order_discount = $this->sma->formatDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = NULL;
            }
            $total_discount = $this->sma->formatDecimal($order_discount + $product_discount);

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->sma->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->sma->formatDecimal((($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = NULL;
            }

            $total_tax = $this->sma->formatDecimal($product_tax + $order_tax);
            $grand_total = $this->sma->formatDecimal($this->sma->formatDecimal($total) + $total_tax + $this->sma->formatDecimal($shipping) - $order_discount);
            $data = array('date' => $date,
                'reference_no' => $reference,
                'customer_id' => $customer_id,
                'customer' => $customer,
                'biller_id' => $biller_id,
                'biller' => $biller,
                'warehouse_id' => $warehouse_id,
                'note' => $note,
                'staff_note' => $staff_note,
                'total' => $this->sma->formatDecimal($total),
                'product_discount' => $this->sma->formatDecimal($product_discount),
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $this->sma->formatDecimal($product_tax),
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping' => $this->sma->formatDecimal($shipping),
                'grand_total' => $grand_total,
                'total_items' => $total_items,
                'sale_status' => $sale_status,
                'payment_status' => $payment_status,
                'payment_term' => $payment_term,
                'due_date' => $due_date,
                'paid' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            if ($payment_status == 'paid') {

                $payment = array(
                    'date' => $date,
                    'reference_no' => $this->site->getReference('pay'),
                    'amount' => $grand_total,
                    'paid_by' => 'cash',
                    'cheque_no' => '',
                    'cc_no' => '',
                    'cc_holder' => '',
                    'cc_month' => '',
                    'cc_year' => '',
                    'cc_type' => '',
                    'created_by' => $this->session->userdata('user_id'),
                    'note' => lang('auto_added_for_sale_by_csv').' ('.lang('sale_reference_no').' '.$reference.')',
                    'type' => 'received'
                    );

            } else {
                $payment = array();
            }

            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }

            //$this->sma->print_arrays($data, $products, $payment);
        }


        if ($this->form_validation->run() == true && $this->sales_model->addSale($data, $products, $payment)) {
            $this->session->set_userdata('remove_slls', 1);
            $this->session->set_flashdata('message', $this->lang->line("sale_added"));
            redirect("sales");
        } else {

            $data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['slnumber'] = $this->site->getReference('so');

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('add_sale_by_csv')));
            $meta = array('page_title' => lang('add_sale_by_csv'), 'bc' => $bc);
            $this->page_construct('sales/sale_by_csv', $meta, $this->data);

        }
    }

    function add2($quote_id = NULL)
    {
        $this->sma->checkPermissions('add',true,'sales');

        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        //$this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('customer', lang("customer"), 'required');
        $this->form_validation->set_rules('biller', lang("biller"), 'required');
        $this->form_validation->set_rules('sale_status', lang("sale_status"), 'required');
        $this->form_validation->set_rules('payment_status', lang("payment_status"), 'required');

        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('so');
            // if ($this->Owner || $this->Admin) {
            $date = $this->sma->fld(trim($this->input->post('date')));
            // } else {
            //     $date = date('Y-m-d H:i:s');
            // }
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
            $slrtype = $this->input->post('slrtype');
            $biller_id = $this->input->post('biller');
            $total_items = $this->input->post('total_items');
            $sale_status = $this->input->post('sale_status');
            $payment_status = $this->input->post('payment_status');
            $category_id = $this->input->post('qcategory');
            $payment_term = $this->input->post('payment_term');
            $due_date = $payment_term ? date('Y-m-d', strtotime('+' . $payment_term . ' days')) : NULL;
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = $customer_details->company ? $customer_details->company : $customer_details->name;
            $biller_details = $this->site->getCompanyByID($biller_id);
            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $chkindate = $this->sma->fld(trim($this->input->post('chkindate')));
            $chkoutdate = $this->sma->fld(trim($this->input->post('chkoutdate')));
            $note = $this->sma->clear_tags($this->input->post('note'));
            $staff_note = $this->sma->clear_tags($this->input->post('staff_note'));
            $quote_id = $this->input->post('quote_id') ? $this->input->post('quote_id') : NULL;

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_type = $_POST['product_type'][$r];
                $item_code = $_POST['product_code'][$r];
                $item_name = $_POST['product_name'][$r];
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : NULL;
                //$option_details = $this->sales_model->getProductOptionByID($item_option);
                $real_unit_price = $this->sma->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price = $this->sma->formatDecimal($_POST['unit_price'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $item_serial = isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : NULL;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : NULL;

                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->sales_model->getProductByCode($item_code) : NULL;
                    $unit_price = $real_unit_price;
                    $pr_discount = 0;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = (($this->sma->formatDecimal($unit_price)) * (Float)($pds[0])) / 100;
                        } else {
                            $pr_discount = $this->sma->formatDecimal($discount);
                        }
                    }

                    $unit_price = $this->sma->formatDecimal($unit_price - $pr_discount);
                    $item_net_price = $unit_price;
                    $pr_item_discount = $this->sma->formatDecimal($pr_discount * $item_quantity);
                    $product_discount += $pr_item_discount;
                    $pr_tax = 0; $pr_item_tax = 0; $item_tax = 0; $tax = "";

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->sma->formatDecimal((($unit_price) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->sma->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }

                        } elseif ($tax_details->type == 2) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->sma->formatDecimal((($unit_price) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->sma->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }

                            $item_tax = $this->sma->formatDecimal($tax_details->rate);
                            $tax = $tax_details->rate;

                        }
                        $pr_item_tax = $this->sma->formatDecimal($item_tax * $item_quantity);

                    }

                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_price * $item_quantity) + $pr_item_tax);

                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_price' => $item_net_price,
                        'unit_price' => $this->sma->formatDecimal($item_net_price + $item_tax),
                        'quantity' => $item_quantity,
                        'warehouse_id' => $warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->sma->formatDecimal($subtotal),
                        'serial_no' => $item_serial,
                        'real_unit_price' => $real_unit_price
                    );

                    $total += $item_net_price * $item_quantity;
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            if ($this->input->post('order_discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->sma->formatDecimal((($total + $product_tax) * (Float)($ods[0])) / 100);
                } else {
                    $order_discount = $this->sma->formatDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = NULL;
            }
            $total_discount = $this->sma->formatDecimal($order_discount + $product_discount);

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->sma->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->sma->formatDecimal((($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = NULL;
            }

            $total_tax = $this->sma->formatDecimal($product_tax + $order_tax);
            $grand_total = $this->sma->formatDecimal($this->sma->formatDecimal($total) + $total_tax + $this->sma->formatDecimal($shipping) - $order_discount);
            $data = array('date' => $date,
                'reference_no' => $reference,
                'customer_id' => $customer_id,
                'customer' => $customer,
                'residence' => $slrtype,
                'biller_id' => $biller_id,
                'biller' => $biller,
                'warehouse_id' => $warehouse_id,
                'category_id'=> $category_id,
                'chkindate' => $chkindate,
                'chkoutdate' => $chkoutdate,
                'note' => $note,
                'staff_note' => $staff_note,
                'total' => $this->sma->formatDecimal($total),
                'product_discount' => $this->sma->formatDecimal($product_discount),
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $this->sma->formatDecimal($product_tax),
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping' => $this->sma->formatDecimal($shipping),
                'grand_total' => $grand_total,
                'total_items' => $total_items,
                'sale_status' => $sale_status,
                'payment_status' => $payment_status,
                'payment_term' => $payment_term,
                'due_date' => $due_date,
                'paid' => 0,
                'created_by' => $this->session->userdata('user_id')
            );

            if ($payment_status == 'partial' || $payment_status == 'paid') {
                if ($this->input->post('paid_by') == 'gift_card') {
                    $gc = $this->site->getGiftCardByNO($this->input->post('gift_card_no'));
                    $amount_paying = $grand_total >= $gc->balance ? $gc->balance : $grand_total;
                    $gc_balance = $gc->balance - $amount_paying;
                    $payment = array(
                        'date' => $date,
                        'reference_no' => $this->input->post('payment_reference_no'),
                        'amount' => $this->sma->formatDecimal($amount_paying),
                        'paid_by' => $this->input->post('paid_by'),
                        'cheque_no' => $this->input->post('cheque_no'),
                        'cc_no' => $this->input->post('gift_card_no'),
                        'cc_holder' => $this->input->post('pcc_holder'),
                        'cc_month' => $this->input->post('pcc_month'),
                        'cc_year' => $this->input->post('pcc_year'),
                        'cc_type' => $this->input->post('pcc_type'),
                        'created_by' => $this->session->userdata('user_id'),
                        'note' => $this->input->post('payment_note'),
                        'type' => 'received',
                        'gc_balance' => $gc_balance
                    );
                } else {
                    $payment = array(
                        'date' => $date,
                        'reference_no' => $this->input->post('payment_reference_no'),
                        'amount' => $this->sma->formatDecimal($this->input->post('amount-paid')),
                        'paid_by' => $this->input->post('paid_by'),
                        'cheque_no' => $this->input->post('cheque_no'),
                        'cc_no' => $this->input->post('pcc_no'),
                        'cc_holder' => $this->input->post('pcc_holder'),
                        'cc_month' => $this->input->post('pcc_month'),
                        'cc_year' => $this->input->post('pcc_year'),
                        'cc_type' => $this->input->post('pcc_type'),
                        'created_by' => $this->session->userdata('user_id'),
                        'note' => $this->input->post('payment_note'),
                        'type' => 'received'
                    );
                }
            } else {
                $payment = array();
            }

            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }

            // $this->sma->print_arrays($data, $products, $payment);
        }


        if ($this->form_validation->run() == true && $this->sales_model->addSale($data, $products, $payment)) {
            $this->session->set_userdata('remove_slls', 1);
            if ($quote_id) {
                $this->db->update('quotes', array('status' => 'completed'), array('id' => $quote_id));
            }
            //send admin email
            // $subject="Approve Sale-".$reference;
            // $message="A sale for customer ".$customer." has been added on".date('d/m/Y H:i').".Please <a target='_blank' href='".base_url()."'>login</a> and complete it";
            // $cc="cto@techsavanna.technology";
            // $this->sma->send_email("krufed@gmail.com", $subject, $message, NULL, NULL, NULL, $cc,NULL);
            $this->session->set_flashdata('message', lang("sale_added"));

            redirect("sales");
        } else {

            if ($quote_id) {
                $this->data['quote'] = $this->sales_model->getQuoteByID($quote_id);
                $items = $this->sales_model->getAllQuoteItems($quote_id);
                $c = rand(100000, 9999999);
                foreach ($items as $item) {
                    $row = $this->site->getProductByID($item->product_id);
                    if (!$row) {
                        $row = json_decode('{}');
                        $row->tax_method = 0;
                    } else {
                        unset($row->cost, $row->details, $row->product_details, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                    }
                    $row->quantity = 0;
                    $pis = $this->sales_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                    if($pis){
                        foreach ($pis as $pi) {
                            $row->quantity += $pi->quantity_balance;
                        }
                    }
                    $row->id = $item->product_id;
                    $row->code = $item->product_code;
                    $row->name = $item->product_name;
                    $row->type = $item->product_type;
                    $row->qty = $item->quantity;
                    $row->discount = $item->discount ? $item->discount : '0';
                    $row->price = $this->sma->formatDecimal($item->net_unit_price+$this->sma->formatDecimal($item->item_discount/$item->quantity));
                    $row->unit_price = $row->tax_method ? $item->unit_price+$this->sma->formatDecimal($item->item_discount/$item->quantity)+$this->sma->formatDecimal($item->item_tax/$item->quantity) : $item->unit_price+($item->item_discount/$item->quantity);
                    $row->real_unit_price = $item->real_unit_price;
                    $row->tax_rate = $item->tax_rate_id;
                    $row->serial = $item->no_of_rooms;
                    $row->option = $item->option_id;
                    $options = $this->sales_model->getProductOptions($row->id, $item->warehouse_id);
                    if ($options) {
                        $option_quantity = 0;
                        foreach ($options as $option) {
                            $pis = $this->sales_model->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
                            if($pis){
                                foreach ($pis as $pi) {
                                    $option_quantity += $pi->quantity_balance;
                                }
                            }
                            if($option->quantity > $option_quantity) {
                                $option->quantity = $option_quantity;
                            }
                        }
                    }
                    $combo_items = FALSE;
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $item->warehouse_id);
                    }
                    $ri = $this->Settings->item_addition ? $row->id : $c;
                    if ($row->tax_rate) {
                        $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options);
                    } else {
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options);
                    }
                    $c++;
                }
                $this->data['quote_items'] = json_encode($pr);
            }

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['quote_id'] = $quote_id;
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            //$this->data['currencies'] = $this->sales_model->getAllCurrencies();
            $this->data['slnumber'] = ''; //$this->site->getReference('so');
            $this->data['payment_ref'] = $this->site->getReference('pay');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('add_sale')));
            $meta = array('page_title' => lang('add_sale'), 'bc' => $bc);
            $this->page_construct('sales/add', $meta, $this->data);
        }



    }

    function add(){
        $this->sma->checkPermissions('add',true,'sales');
        $this->form_validation->set_rules('expiry_date', 'Expiry Date required', 'required');
        $this->form_validation->set_rules('currency', 'Currency required', 'required');

        if ($this->form_validation->run() == true) {
            $voucher_data = $this->vouchers_model->getVoucherByID($this->input->post('voucher'));
            $customer = $this->companies_model->getCompanyByID($voucher_data->customer_id);
            $biller = $this->companies_model->getBillerByID($voucher_data->biller_id);
			$total = $this->sma->formatDecimal($this->input->post('total')*$voucher_data->total_nights);
            $vat = $this->sma->formatDecimal(($total*24)/100);
            $service_charge = $this->sma->formatDecimal(($total*2)/100);
            $tourism_levy = $this->sma->formatDecimal(($total*2)/100);
            $data = array(
                'date' => date('Y-m-d H:i:s'),
                'reference_no' => time(),
                'voucher_id' => $voucher_data->id,
                'customer_id' => $voucher_data->customer_id,
                'customer' => $customer->name,
                'currency' => $this->input->post('currency'),
                'residence' => $voucher_data->residence,
                'biller_id' => $voucher_data->biller_id,
                'biller' => $biller->name,
                'no_of_rooms' => $this->input->post('total_qnty'),
                'warehouse_id' => $voucher_data->hotel_id,
                'category_id' => 54,
                'chkindate' => $voucher_data->check_in,
                'chkoutdate' => $voucher_data->check_out,
                'note' => $voucher_data->remarks,
                'staff_note' =>  $voucher_data->remarks,
                'total' => $this->sma->formatDecimal($this->input->post('total')*$voucher_data->total_nights),
                'product_discount' => $this->sma->formatDecimal(0),
                'order_discount_id' => null,
                'order_discount' => 0,
                'total_discount' => 0,
                'product_tax' => $this->sma->formatDecimal(0),
                'order_tax_id' => null,
                'order_tax' => 0,
                'total_tax' => 0,
                'shipping' => $this->sma->formatDecimal(0),
                'grand_total' => $this->sma->formatDecimal($this->input->post('total')*$voucher_data->total_nights),
                'total_items' => 2,
                'sale_status' => "completed",
                'payment_status' => "pending",
                'payment_term' => 0,
                'due_date' => $this->input->post('expiry_date'),
                'paid' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
        }

        if ($this->form_validation->run() == true && $sid = $this->sales_model->addSaleInvoice($data)) {
            $ref_data = array(
                'reference_no' => 'E00'.$sid,
            );
            $this->sales_model->updateSaleOnly($sid, $ref_data);
            if($this->input->post('single_qnty')){
                $this->input->post('single_pax');
                $this->input->post('single_price');
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'singles_room',
                    'quantity' => $this->input->post('single_qnty'),
                    'price' => $this->input->post('single_price'),
                    'no_adults' => $this->input->post('single_adults'),
                    'no_children' => $this->input->post('single_children'),
                    'pax' => $this->input->post('single_pax'),
                    'total' => $this->input->post('single_qnty')*$this->input->post('single_price'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('double_qnty')){
                $this->input->post('double_pax');
                $this->input->post('double_price');
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'executive_room',
                    'quantity' => $this->input->post('double_qnty'),
                    'price' => $this->input->post('double_price'),
                    'no_adults' => $this->input->post('double_adults'),
                    'no_children' => $this->input->post('double_children'),
                    'pax' => $this->input->post('double_pax'),
                    'total' => $this->input->post('double_qnty')*$this->input->post('double_price'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            
            if($this->input->post('family_qnty')){
                $this->input->post('family_pax');
                $this->input->post('family_price');
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'deluxe_room',
                    'quantity' => $this->input->post('family_qnty'),
                    'price' => $this->input->post('family_price'),
                    'no_adults' => $this->input->post('family_adults'),
                    'no_children' => $this->input->post('family_children'),
                    'pax' => $this->input->post('family_pax'),
                    'total' => $this->input->post('family_qnty')*$this->input->post('family_price'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            
            if($this->input->post('twin_qnty')){
                $this->input->post('twin_pax');
                $this->input->post('twin_price');
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'twin_room',
                    'quantity' => $this->input->post('twin_qnty'),
                    'price' => $this->input->post('twin_price'),
                    'no_adults' => $this->input->post('twin_adults'),
                    'no_children' => $this->input->post('twin_children'),
                    'pax' => $this->input->post('twin_pax'),
                    'total' => $this->input->post('twin_qnty')*$this->input->post('twin_price'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('triple_qnty')){
                $this->input->post('triple_pax');
                $this->input->post('triple_price');
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'superior_room',
                    'quantity' => $this->input->post('triple_qnty'),
                    'price' => $this->input->post('triple_price'),
                    'no_adults' => $this->input->post('triple_adults'),
                    'no_children' => $this->input->post('triple_children'),
                    'pax' => $this->input->post('triple_pax'),
                    'total' => $this->input->post('triple_qnty')*$this->input->post('triple_price'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('honeymoon_qnty')){
                $this->input->post('honeymoon_pax');
                $this->input->post('honeymoon_price');
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'standard_room',
                    'quantity' => $this->input->post('honeymoon_qnty'),
                    'price' => $this->input->post('honeymoon_price'),
                    'no_adults' => $this->input->post('honeymoon_adults'),
                    'no_children' => $this->input->post('honeymoon_children'),
                    'pax' => $this->input->post('honeymoon_pax'),
                    'total' => $this->input->post('honeymoon_qnty')*$this->input->post('honeymoon_price'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('extra_bed_qnty')){
                $this->input->post('extra_bed_pax');
                $this->input->post('extra_bed_price');
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'extrabed',
                    'quantity' => $this->input->post('extra_bed_qnty'),
                    'price' => $this->input->post('extra_bed_price'),
                    'no_adults' => $this->input->post('extra_bed_adults'),
                    'no_children' => $this->input->post('extra_bed_children'),
                    'pax' => $this->input->post('extra_bed_pax'),
                    'total' => $this->input->post('extra_bed_qnty')*$this->input->post('extra_bed_price'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }

            $this->session->set_flashdata('message', lang("sale_added"));
            redirect("sales");
        } else {
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['vouchers'] = $this->vouchers_model->getAllVouchers();
            $this->data['customers'] = $this->vouchers_model->getCompanyByGroupID(5);
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['billers'] = $this->site->getAllCompanies("biller");
            $this->data['categories'] = $this->site->getAllCategories();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('sales')));
            $meta = array('page_title' => lang('sales'), 'bc' => $bc);
            $this->page_construct('sales/add_invoice', $meta, $this->data);
        }

    }

    function getVoucher(){
        $this->sma->checkPermissions('index',true,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $voucher = $this->vouchers_model->getVoucherByID($id);

        echo json_encode($voucher);
    }

    function getSaleItems(){
        $this->sma->checkPermissions('index',true,'sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $voucher = $this->vouchers_model->getInvItemsByInvId($id);

        echo json_encode($voucher);
    }

}
