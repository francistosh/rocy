<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vouchers extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
             $this->session->set_userdata('requested_page', $this->uri->uri_string());
             redirect('login');
         }
        if ($this->Customer || $this->Supplier) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->lang->load('vouchers', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('sales_model');
        $this->load->model('vouchers_model');
        $this->load->model('companies_model');
    }

    function index($action = NULL)
    {
        $this->sma->checkPermissions('index',null,'vouchers');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('vouchers')));
        $meta = array('page_title' => lang('vouchers'), 'bc' => $bc);
        $this->page_construct('vouchers/index', $meta, $this->data);
    }

    function getVouchers()
    {
        $this->sma->checkPermissions('index',null,'vouchers');
        $this->load->library('datatables');
        $this->datatables
            ->select("sma_vouchers.id as id,sma_companies.name,sma_vouchers.voucher_no,sma_vouchers.status,sma_vouchers.group_name,sma_vouchers.check_in,sma_vouchers.check_out,sma_vouchers.total_nights,(sum(executive_room)+sum(superior_room)+sum(deluxe_room)+sum(standard_room)+sum(singles_room)+sum(twin_room)) AS total_rooms",false)
            ->from("sma_vouchers")
            ->join('sma_companies', 'sma_vouchers.customer_id = sma_companies.id', 'left')
            ->group_by("sma_vouchers.voucher_no")
            ->add_column("Actions", "<center>
                <a class=\"tip\" title='" . $this->lang->line("view_voucher") . "' href='" . site_url('vouchers/show/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-eye\"></i></a> 
                <a class=\"tip\" title='" . $this->lang->line("edit_voucher") . "' href='" . site_url('vouchers/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-pencil\"></i></a>
                <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_voucher") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('vouchers/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
        //->unset_column('id');
        echo $this->datatables->generate();

    }

    function vouchers_date($date=null){

        $date = $this->input->get('voucher_date');

        $this->data['vouchers'] = $this->vouchers_model->getVouchersByDate($date);
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('vouchers')));
        $meta = array('page_title' => lang('vouchers'), 'bc' => $bc);
        $this->page_construct('vouchers/index2', $meta, $this->data);

    }
    function add()
    {
        $this->sma->checkPermissions('add',null,'vouchers');

        $this->form_validation->set_rules('voucher_number', 'Voucher Number', 'required');
       // $this->form_validation->set_rules('warehouse', 'Hotel', 'required');
        $this->form_validation->set_rules('customer', 'Customer', 'required');
        //$this->form_validation->set_rules('biller', 'Biller', 'required');
        //$this->form_validation->set_rules('group_name', 'Group Name', 'required');
        $this->form_validation->set_rules('no_adults', 'Number of adults', 'required');
        $this->form_validation->set_rules('check_in', 'Check In Date', 'required');
        $this->form_validation->set_rules('date_in', 'Date', 'required');
        $this->form_validation->set_rules('check_out', 'Check Out Date', 'required');
        //$this->form_validation->set_rules('residence', 'Residence', 'required');
        //$this->form_validation->set_rules('remarks', 'Remarks', 'required');
        $this->form_validation->set_rules('meal_plan', 'Meal Plan', 'required');
        $date1 = date_create($this->input->post('check_in'));
        $date2 = date_create($this->input->post('check_out'));
        $diff = date_diff($date1,$date2);
        if($date1>$date2){
            $this->session->set_flashdata('error', 'Check in date cannot be after check out date');
            redirect('vouchers/add');
        }
        if($this->vouchers_model->getVoucherByNumber($this->input->post('voucher_number'))){
           $this->session->set_flashdata('error', 'A voucher already exists with that number');
           redirect('vouchers/add');
        }
        if ($this->form_validation->run('vouchers/add') == true) {
           
            $data = array(
                'customer_id'=> $this->input->post('customer'),
                'voucher_no'=> $this->input->post('voucher_number'),
                'hotel_id'=> '4',//$this->input->post('warehouse')
                'biller_id'=> '4', //$this->input->post('biller')
                'status'=> 'Reserved', //$this->input->post('status'
                'group_name'=> $this->input->post('group_name'),
                'date_in'=> $this->input->post('date_in'),
                'check_in'=> $this->input->post('check_in'),
                'check_out'=> $this->input->post('check_out'),
                'total_nights'=> $diff->format("%a")+1-1,
                'residence'=> $this->input->post('residence'),
                'remarks'=> $this->input->post('remarks'),
                'no_children'=> $this->input->post('no_children'),
                'no_adults'=> $this->input->post('no_adults'),
                'executive_room'=> $this->input->post('executive_room')?$this->input->post('executive_room'):0,
                'superior_room'=> $this->input->post('superior_room')?$this->input->post('superior_room'):0,
                'deluxe_room'=> $this->input->post('deluxe_room')?$this->input->post('deluxe_room'):0,
                'standard_room'=> $this->input->post('standard_room')?$this->input->post('standard_room'):0,
                'singles_room'=> $this->input->post('singles_room')?$this->input->post('singles_room'):0,
                'twin_room'=> $this->input->post('twin_room')?$this->input->post('twin_room'):0,
                'meal_plan'=> $this->input->post('meal_plan'),
                'extra_bed'=> $this->input->post('extra_bed'),
                'contact_name'=> $this->input->post('contact_name'),
                'contact_phone'=> $this->input->post('contact_phone'),
                'contact_email'=> $this->input->post('contact_email'),

            );

        } elseif ($this->input->post('add_voucher')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('vouchers/add');
        }

        if ($this->form_validation->run() == true && $rid = $this->vouchers_model->addVoucher($data)) {
            $actual_data = $this->input->post('data');
            if (count($actual_data)>0){
                for($i =0;$i<count($actual_data);$i++){
                    $voucher_children = array(
                        'voucher_id'=> $rid,
                        'age'=> $actual_data[$i],
                    );
                    $this->vouchers_model->addVoucherChildren($voucher_children);
                }
            }

            $this->session->set_flashdata('message', $this->lang->line("voucher_added"));
            redirect('vouchers/add');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customers'] = $this->site->getCompanyByGroupID(5);
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['billers'] = $this->site->getAllCompanies("biller");
            $this->data['categories'] = $this->site->getAllInvCategories();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('vouchers')));
            $meta = array('page_title' => lang('vouchers'), 'bc' => $bc);
            $this->page_construct('vouchers/add', $meta, $this->data);
        }
    }

    function edit($id = NULL)
    {
        $this->sma->checkPermissions('edit',true,'vouchers');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $voucher_children = $this->vouchers_model->getVoucherChildrenByVoucherID($id);
        $sale = $this->vouchers_model->getInvByVoucherID($id);
        $voucher_original = $this->vouchers_model->getVoucherByID($id);
        //$this->form_validation->set_rules('voucher_number', 'Voucher Number', 'required');
        //$this->form_validation->set_rules('warehouse', 'Hotel', 'required');
        $this->form_validation->set_rules('customer', 'Customer', 'required');
        //$this->form_validation->set_rules('biller', 'Biller', 'required');
        //$this->form_validation->set_rules('group_name', 'Group Name', 'required');
        $this->form_validation->set_rules('no_adults', 'Number of adults', 'required');
        $this->form_validation->set_rules('check_in', 'Check In Date', 'required');
        $this->form_validation->set_rules('check_out', 'Check Out Date', 'required');
        //$this->form_validation->set_rules('residence', 'Residence', 'required');
        //$this->form_validation->set_rules('remarks', 'Remarks', 'required');
        $this->form_validation->set_rules('meal_plan', 'Meal Plan', 'required');

        if ($this->form_validation->run('vouchers/edit') == true) {
            $date1 = date_create($this->input->post('check_in'));
            $date2 = date_create($this->input->post('check_out'));
            $diff = date_diff($date1,$date2);
            $data = array(
                'customer_id'=> $this->input->post('customer'),
                'voucher_no'=> $this->input->post('voucher_number'),
                'hotel_id'=> '4',//$this->input->post('warehouse')
                'biller_id'=> '4',//$this->input->post('biller')
                'status'=> $this->input->post('status'),
                'group_name'=> $this->input->post('group_name'),
                'date_in'=> $this->input->post('date_in'),
                'check_in'=> $this->input->post('check_in'),
                'check_out'=> $this->input->post('check_out'),
                'total_nights'=> $diff->format( "%a")+1-1,
                'residence'=> $this->input->post('residence'),
                'remarks'=> $this->input->post('remarks'),
                'no_children'=> $this->input->post('no_children'),
                'no_adults'=> $this->input->post('no_adults'),
                'executive_room'=> $this->input->post('executive_room')?$this->input->post('executive_room'):0,
                'superior_room'=> $this->input->post('superior_room')?$this->input->post('superior_room'):0,
                'deluxe_room'=> $this->input->post('deluxe_room')?$this->input->post('deluxe_room'):0,
                'standard_room'=> $this->input->post('standard_room')?$this->input->post('standard_room'):0,
                'singles_room'=> $this->input->post('singles_room')?$this->input->post('singles_room'):0,
                'twin_room'=> $this->input->post('twin_room')?$this->input->post('twin_room'):0,
                'meal_plan'=> $this->input->post('meal_plan'),
                'extra_bed'=> $this->input->post('extra_bed'),
                'contact_name'=> $this->input->post('contact_name'),
                'contact_phone'=> $this->input->post('contact_phone'),
                'contact_email'=> $this->input->post('contact_email'),
            );
        } elseif ($this->input->post('edit_voucher')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->vouchers_model->updateVoucher($id, $data)) {

            $voucher = $this->vouchers_model->getVoucherByID($id);
            $actual_data = $this->input->post('data');
            if (count($actual_data)>0){
                $this->vouchers_model->deleteAllChildren($id);
                for($i =0;$i<count($actual_data);$i++){
                    $voucher_children = array(
                        'voucher_id'=> $id,
                        'age'=> $actual_data[$i],
                    );
                    $this->vouchers_model->addVoucherChildren($voucher_children);
                }
            }
            $customer = $this->companies_model->getCompanyByID($voucher->customer_id);
            $biller = $this->companies_model->getBillerByID($voucher->biller_id);
            if(count($sale)>0){
                $saledata_o = array(
                    'customer_id' => $voucher->customer_id,
                    'customer' => $customer->name,
                    'residence' => $voucher->residence,
                    'biller_id' => $voucher->biller_id,
                    'biller' => $biller->name,
                    'warehouse_id' => $voucher->hotel_id,
                    'chkindate' => $data['check_in'],
                    'chkoutdate' => $data['check_out'],
                    'note' => $voucher->remarks,
                    'staff_note' =>  $voucher->remarks,
                );
                $this->sales_model->updateSaleOnly($sale->id, $saledata_o);
                /*executive_room
                superior_room
                deluxe_room
                standard_room
                singles_room
                twin_room
                extra_bed*/

                if($voucher_original->total_nights==$data['total_nights']){//check if no of nights has changed
                    if($voucher_original->executive_room!==$data['executive_room']){
                        //if changed check if equal to zero and remove that item from invoice items otherwise add the items to invoice item
                        if($data['executive_room']==0){
                            $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("executive_room",$sale->id);
                            $grand_total = $sale->grand_total-($saleitem->price*$saleitem->quantity*$data['total_nights']);
                            //get current item total and minus from the sale grand total and total then update sale and delete invoice item
                            $saledata = array(
                                'voucher_id' => $voucher->id,
                                'customer_id' => $voucher->customer_id,
                                'customer' => $customer->name,
                                'currency' => $sale->currency,
                                'residence' => $voucher->residence,
                                'biller_id' => $voucher->biller_id,
                                'biller' => $biller->name,
                                'no_of_rooms' => $sale->no_of_rooms-1,
                                'warehouse_id' => $voucher->hotel_id,
                                'category_id' => 54,
                                'chkindate' => $voucher->check_in,
                                'chkoutdate' => $voucher->check_out,
                                'note' => $voucher->remarks,
                                'staff_note' =>  $voucher->remarks,
                                'total' => $grand_total,
                                'product_discount' => $this->sma->formatDecimal(0),
                                'order_discount_id' => null,
                                'order_discount' => 0,
                                'total_discount' => 0,
                                'product_tax' => $this->sma->formatDecimal(0),
                                'order_tax_id' => null,
                                'order_tax' => 0,
                                'total_tax' => 0,
                                'shipping' => $this->sma->formatDecimal(0),
                                'grand_total' => $grand_total,
                                'total_items' => 2,
                                'sale_status' => "completed",
                                'payment_status' => "pending",
                                'payment_term' => 0,
                                'due_date' => $sale->due_date,
                                'paid' => $sale->paid,
                                'created_by' => $this->session->userdata('user_id')
                            );
                            $this->sales_model->updateSaleOnly($sale->id, $saledata);
                            $this->vouchers_model->deleteInvItemById($saleitem->id);
                        }else{
                            if($voucher_original->executive_room==0){
                                //add the item to the sales invoice as it was not existing before
                                //dont update the invoice grand total and total because we dont have the item price yet this will be done in sale edit
                                $invoice_item = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'executive_room',
                                    'quantity' => $data['executive_room'],
                                    'price' => 0,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => 0,
                                );
                                $this->sales_model->addSaleInvoiceItem($invoice_item);
                                $saledata_p = array(
                                    'no_of_rooms' => $sale->no_of_rooms+1,
                                    'total_items' => $sale->no_of_rooms+1,
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_p);
                            }else{
                                //get current item and multiply the price by the item quantity and update the item total
                                //get current sale total and grand total and update them
                                $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("executive_room",$sale->id);
                                $sale_item_total = $saleitem->price*$data['executive_room'];
                                $grand_total = ($sale->grand_total-($saleitem->total*$data['total_nights']))+($sale_item_total*$data['total_nights']);
                                $invoice_item_o = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'executive_room',
                                    'quantity' => $data['executive_room'],
                                    'price' => $saleitem->price,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => $sale_item_total,
                                );
                                $this->sales_model->updateInvoiceItem($saleitem->id, $invoice_item_o);
                                $saledata_q = array(
                                    'voucher_id' => $voucher->id,
                                    'customer_id' => $voucher->customer_id,
                                    'customer' => $customer->name,
                                    'currency' => $sale->currency,
                                    'residence' => $voucher->residence,
                                    'biller_id' => $voucher->biller_id,
                                    'biller' => $biller->name,
                                    'no_of_rooms' => $sale->no_of_rooms,
                                    'warehouse_id' => $voucher->hotel_id,
                                    'category_id' => 54,
                                    'chkindate' => $voucher->check_in,
                                    'chkoutdate' => $voucher->check_out,
                                    'note' => $voucher->remarks,
                                    'staff_note' =>  $voucher->remarks,
                                    'total' => $grand_total,
                                    'product_discount' => $this->sma->formatDecimal(0),
                                    'order_discount_id' => null,
                                    'order_discount' => 0,
                                    'total_discount' => 0,
                                    'product_tax' => $this->sma->formatDecimal(0),
                                    'order_tax_id' => null,
                                    'order_tax' => 0,
                                    'total_tax' => 0,
                                    'shipping' => $this->sma->formatDecimal(0),
                                    'grand_total' => $grand_total,
                                    'total_items' => $sale->no_of_rooms,
                                    'sale_status' => "completed",
                                    'payment_status' => "pending",
                                    'payment_term' => 0,
                                    'due_date' => $sale->due_date,
                                    'paid' => $sale->paid,
                                    'created_by' => $this->session->userdata('user_id')
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_q);
                            }

                        }

                    }
                    if($voucher_original->superior_room!==$data['superior_room']){
                        //if changed check if equal to zero and remove that item from invoice items otherwise add the items to invoice item
                        if($data['superior_room']==0){
                            $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("superior_room",$sale->id);
                            $grand_total = $sale->grand_total-($saleitem->price*$saleitem->quantity*$data['total_nights']);
                            //get current item total and minus from the sale grand total and total then update sale and delete invoice item
                            $saledata = array(
                                'voucher_id' => $voucher->id,
                                'customer_id' => $voucher->customer_id,
                                'customer' => $customer->name,
                                'currency' => $sale->currency,
                                'residence' => $voucher->residence,
                                'biller_id' => $voucher->biller_id,
                                'biller' => $biller->name,
                                'no_of_rooms' => $sale->no_of_rooms-1,
                                'warehouse_id' => $voucher->hotel_id,
                                'category_id' => 54,
                                'chkindate' => $voucher->check_in,
                                'chkoutdate' => $voucher->check_out,
                                'note' => $voucher->remarks,
                                'staff_note' =>  $voucher->remarks,
                                'total' => $grand_total,
                                'product_discount' => $this->sma->formatDecimal(0),
                                'order_discount_id' => null,
                                'order_discount' => 0,
                                'total_discount' => 0,
                                'product_tax' => $this->sma->formatDecimal(0),
                                'order_tax_id' => null,
                                'order_tax' => 0,
                                'total_tax' => 0,
                                'shipping' => $this->sma->formatDecimal(0),
                                'grand_total' => $grand_total,
                                'total_items' => 2,
                                'sale_status' => "completed",
                                'payment_status' => "pending",
                                'payment_term' => 0,
                                'due_date' => $sale->due_date,
                                'paid' => $sale->paid,
                                'created_by' => $this->session->userdata('user_id')
                            );
                            $this->sales_model->updateSaleOnly($sale->id, $saledata);
                            $this->vouchers_model->deleteInvItemById($saleitem->id);
                        }else{
                            if($voucher_original->superior_room==0){
                                //add the item to the sales invoice as it was not existing before
                                //dont update the invoice grand total and total because we dont have the item price yet this will be done in sale edit
                                $invoice_item = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'superior_room',
                                    'quantity' => $data['superior_room'],
                                    'price' => 0,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => 0,
                                );
                                $this->sales_model->addSaleInvoiceItem($invoice_item);
                                $saledata_p = array(
                                    'no_of_rooms' => $sale->no_of_rooms+1,
                                    'total_items' => $sale->no_of_rooms+1,
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_p);
                            }else{
                                //get current item and multiply the price by the item quantity and update the item total
                                //get current sale total and grand total and update them
                                $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("superior_room",$sale->id);
                                $sale_item_total = $saleitem->price*$data['superior_room'];
                                $grand_total = ($sale->grand_total-($saleitem->total*$data['total_nights']))+($sale_item_total*$data['total_nights']);
                                $invoice_item_o = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'superior_room',
                                    'quantity' => $data['superior_room'],
                                    'price' => $saleitem->price,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => $sale_item_total,
                                );
                                $this->sales_model->updateInvoiceItem($saleitem->id, $invoice_item_o);
                                $saledata_q = array(
                                    'voucher_id' => $voucher->id,
                                    'customer_id' => $voucher->customer_id,
                                    'customer' => $customer->name,
                                    'currency' => $sale->currency,
                                    'residence' => $voucher->residence,
                                    'biller_id' => $voucher->biller_id,
                                    'biller' => $biller->name,
                                    'no_of_rooms' => $sale->no_of_rooms,
                                    'warehouse_id' => $voucher->hotel_id,
                                    'category_id' => 54,
                                    'chkindate' => $voucher->check_in,
                                    'chkoutdate' => $voucher->check_out,
                                    'note' => $voucher->remarks,
                                    'staff_note' =>  $voucher->remarks,
                                    'total' => $grand_total,
                                    'product_discount' => $this->sma->formatDecimal(0),
                                    'order_discount_id' => null,
                                    'order_discount' => 0,
                                    'total_discount' => 0,
                                    'product_tax' => $this->sma->formatDecimal(0),
                                    'order_tax_id' => null,
                                    'order_tax' => 0,
                                    'total_tax' => 0,
                                    'shipping' => $this->sma->formatDecimal(0),
                                    'grand_total' => $grand_total,
                                    'total_items' => $sale->no_of_rooms,
                                    'sale_status' => "completed",
                                    'payment_status' => "pending",
                                    'payment_term' => 0,
                                    'due_date' => $sale->due_date,
                                    'paid' => $sale->paid,
                                    'created_by' => $this->session->userdata('user_id')
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_q);
                            }

                        }

                    }
                    if($voucher_original->deluxe_room!==$data['deluxe_room']){
                        //if changed check if equal to zero and remove that item from invoice items otherwise add the items to invoice item
                        if($data['deluxe_room']==0){
                            $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("deluxe_room",$sale->id);
                            $grand_total = $sale->grand_total-($saleitem->price*$saleitem->quantity*$data['total_nights']);
                            //get current item total and minus from the sale grand total and total then update sale and delete invoice item
                            $saledata = array(
                                'voucher_id' => $voucher->id,
                                'customer_id' => $voucher->customer_id,
                                'customer' => $customer->name,
                                'currency' => $sale->currency,
                                'residence' => $voucher->residence,
                                'biller_id' => $voucher->biller_id,
                                'biller' => $biller->name,
                                'no_of_rooms' => $sale->no_of_rooms-1,
                                'warehouse_id' => $voucher->hotel_id,
                                'category_id' => 54,
                                'chkindate' => $voucher->check_in,
                                'chkoutdate' => $voucher->check_out,
                                'note' => $voucher->remarks,
                                'staff_note' =>  $voucher->remarks,
                                'total' => $grand_total,
                                'product_discount' => $this->sma->formatDecimal(0),
                                'order_discount_id' => null,
                                'order_discount' => 0,
                                'total_discount' => 0,
                                'product_tax' => $this->sma->formatDecimal(0),
                                'order_tax_id' => null,
                                'order_tax' => 0,
                                'total_tax' => 0,
                                'shipping' => $this->sma->formatDecimal(0),
                                'grand_total' => $grand_total,
                                'total_items' => 2,
                                'sale_status' => "completed",
                                'payment_status' => "pending",
                                'payment_term' => 0,
                                'due_date' => $sale->due_date,
                                'paid' => $sale->paid,
                                'created_by' => $this->session->userdata('user_id')
                            );
                            $this->sales_model->updateSaleOnly($sale->id, $saledata);
                            $this->vouchers_model->deleteInvItemById($saleitem->id);
                        }else{
                            if($voucher_original->deluxe_room==0){
                                //add the item to the sales invoice as it was not existing before
                                //dont update the invoice grand total and total because we dont have the item price yet this will be done in sale edit
                                $invoice_item = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'deluxe_room',
                                    'quantity' => $data['deluxe_room'],
                                    'price' => 0,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => 0,
                                );
                                $this->sales_model->addSaleInvoiceItem($invoice_item);
                                $saledata_p = array(
                                    'no_of_rooms' => $sale->no_of_rooms+1,
                                    'total_items' => $sale->no_of_rooms+1,
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_p);
                            }else{
                                //get current item and multiply the price by the item quantity and update the item total
                                //get current sale total and grand total and update them
                                $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("deluxe_room",$sale->id);
                                $sale_item_total = $saleitem->price*$data['deluxe_room'];
                                $grand_total = ($sale->grand_total-($saleitem->total*$data['total_nights']))+($sale_item_total*$data['total_nights']);
                                $invoice_item_o = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'deluxe_room',
                                    'quantity' => $data['deluxe_room'],
                                    'price' => $saleitem->price,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => $sale_item_total,
                                );
                                $this->sales_model->updateInvoiceItem($saleitem->id, $invoice_item_o);
                                $saledata_q = array(
                                    'voucher_id' => $voucher->id,
                                    'customer_id' => $voucher->customer_id,
                                    'customer' => $customer->name,
                                    'currency' => $sale->currency,
                                    'residence' => $voucher->residence,
                                    'biller_id' => $voucher->biller_id,
                                    'biller' => $biller->name,
                                    'no_of_rooms' => $sale->no_of_rooms,
                                    'warehouse_id' => $voucher->hotel_id,
                                    'category_id' => 54,
                                    'chkindate' => $voucher->check_in,
                                    'chkoutdate' => $voucher->check_out,
                                    'note' => $voucher->remarks,
                                    'staff_note' =>  $voucher->remarks,
                                    'total' => $grand_total,
                                    'product_discount' => $this->sma->formatDecimal(0),
                                    'order_discount_id' => null,
                                    'order_discount' => 0,
                                    'total_discount' => 0,
                                    'product_tax' => $this->sma->formatDecimal(0),
                                    'order_tax_id' => null,
                                    'order_tax' => 0,
                                    'total_tax' => 0,
                                    'shipping' => $this->sma->formatDecimal(0),
                                    'grand_total' => $grand_total,
                                    'total_items' => $sale->no_of_rooms,
                                    'sale_status' => "completed",
                                    'payment_status' => "pending",
                                    'payment_term' => 0,
                                    'due_date' => $sale->due_date,
                                    'paid' => $sale->paid,
                                    'created_by' => $this->session->userdata('user_id')
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_q);
                            }

                        }

                    }
                    if($voucher_original->standard_room!==$data['standard_room']){
                        //if changed check if equal to zero and remove that item from invoice items otherwise add the items to invoice item
                        if($data['standard_room']==0){
                            $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("standard_room",$sale->id);
                            $grand_total = $sale->grand_total-($saleitem->price*$saleitem->quantity*$data['total_nights']);
                            //get current item total and minus from the sale grand total and total then update sale and delete invoice item
                            $saledata = array(
                                'voucher_id' => $voucher->id,
                                'customer_id' => $voucher->customer_id,
                                'customer' => $customer->name,
                                'currency' => $sale->currency,
                                'residence' => $voucher->residence,
                                'biller_id' => $voucher->biller_id,
                                'biller' => $biller->name,
                                'no_of_rooms' => $sale->no_of_rooms-1,
                                'warehouse_id' => $voucher->hotel_id,
                                'category_id' => 54,
                                'chkindate' => $voucher->check_in,
                                'chkoutdate' => $voucher->check_out,
                                'note' => $voucher->remarks,
                                'staff_note' =>  $voucher->remarks,
                                'total' => $grand_total,
                                'product_discount' => $this->sma->formatDecimal(0),
                                'order_discount_id' => null,
                                'order_discount' => 0,
                                'total_discount' => 0,
                                'product_tax' => $this->sma->formatDecimal(0),
                                'order_tax_id' => null,
                                'order_tax' => 0,
                                'total_tax' => 0,
                                'shipping' => $this->sma->formatDecimal(0),
                                'grand_total' => $grand_total,
                                'total_items' => 2,
                                'sale_status' => "completed",
                                'payment_status' => "pending",
                                'payment_term' => 0,
                                'due_date' => $sale->due_date,
                                'paid' => $sale->paid,
                                'created_by' => $this->session->userdata('user_id')
                            );
                            $this->sales_model->updateSaleOnly($sale->id, $saledata);
                            $this->vouchers_model->deleteInvItemById($saleitem->id);
                        }else{
                            if($voucher_original->standard_room==0){
                                //add the item to the sales invoice as it was not existing before
                                //dont update the invoice grand total and total because we dont have the item price yet this will be done in sale edit
                                $invoice_item = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'standard_room',
                                    'quantity' => $data['standard_room'],
                                    'price' => 0,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => 0,
                                );
                                $this->sales_model->addSaleInvoiceItem($invoice_item);
                                $saledata_p = array(
                                    'no_of_rooms' => $sale->no_of_rooms+1,
                                    'total_items' => $sale->no_of_rooms+1,
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_p);
                            }else{
                                //get current item and multiply the price by the item quantity and update the item total
                                //get current sale total and grand total and update them
                                $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("standard_room",$sale->id);
                                $sale_item_total = $saleitem->price*$data['standard_room'];
                                $grand_total = ($sale->grand_total-($saleitem->total*$data['total_nights']))+($sale_item_total*$data['total_nights']);
                                $invoice_item_o = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'standard_room',
                                    'quantity' => $data['standard_room'],
                                    'price' => $saleitem->price,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => $sale_item_total,
                                );
                                $this->sales_model->updateInvoiceItem($saleitem->id, $invoice_item_o);
                                $saledata_q = array(
                                    'voucher_id' => $voucher->id,
                                    'customer_id' => $voucher->customer_id,
                                    'customer' => $customer->name,
                                    'currency' => $sale->currency,
                                    'residence' => $voucher->residence,
                                    'biller_id' => $voucher->biller_id,
                                    'biller' => $biller->name,
                                    'no_of_rooms' => $sale->no_of_rooms,
                                    'warehouse_id' => $voucher->hotel_id,
                                    'category_id' => 54,
                                    'chkindate' => $voucher->check_in,
                                    'chkoutdate' => $voucher->check_out,
                                    'note' => $voucher->remarks,
                                    'staff_note' =>  $voucher->remarks,
                                    'total' => $grand_total,
                                    'product_discount' => $this->sma->formatDecimal(0),
                                    'order_discount_id' => null,
                                    'order_discount' => 0,
                                    'total_discount' => 0,
                                    'product_tax' => $this->sma->formatDecimal(0),
                                    'order_tax_id' => null,
                                    'order_tax' => 0,
                                    'total_tax' => 0,
                                    'shipping' => $this->sma->formatDecimal(0),
                                    'grand_total' => $grand_total,
                                    'total_items' => $sale->no_of_rooms,
                                    'sale_status' => "completed",
                                    'payment_status' => "pending",
                                    'payment_term' => 0,
                                    'due_date' => $sale->due_date,
                                    'paid' => $sale->paid,
                                    'created_by' => $this->session->userdata('user_id')
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_q);
                            }

                        }

                    }
                    if($voucher_original->singles_room!==$data['singles_room']){
                        //if changed check if equal to zero and remove that item from invoice items otherwise add the items to invoice item
                        if($data['singles_room']==0){
                            $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("singles_room",$sale->id);
                            $grand_total = $sale->grand_total-($saleitem->price*$saleitem->quantity*$data['total_nights']);
                            //get current item total and minus from the sale grand total and total then update sale and delete invoice item
                            $saledata = array(
                                'voucher_id' => $voucher->id,
                                'customer_id' => $voucher->customer_id,
                                'customer' => $customer->name,
                                'currency' => $sale->currency,
                                'residence' => $voucher->residence,
                                'biller_id' => $voucher->biller_id,
                                'biller' => $biller->name,
                                'no_of_rooms' => $sale->no_of_rooms-1,
                                'warehouse_id' => $voucher->hotel_id,
                                'category_id' => 54,
                                'chkindate' => $voucher->check_in,
                                'chkoutdate' => $voucher->check_out,
                                'note' => $voucher->remarks,
                                'staff_note' =>  $voucher->remarks,
                                'total' => $grand_total,
                                'product_discount' => $this->sma->formatDecimal(0),
                                'order_discount_id' => null,
                                'order_discount' => 0,
                                'total_discount' => 0,
                                'product_tax' => $this->sma->formatDecimal(0),
                                'order_tax_id' => null,
                                'order_tax' => 0,
                                'total_tax' => 0,
                                'shipping' => $this->sma->formatDecimal(0),
                                'grand_total' => $grand_total,
                                'total_items' => 2,
                                'sale_status' => "completed",
                                'payment_status' => "pending",
                                'payment_term' => 0,
                                'due_date' => $sale->due_date,
                                'paid' => $sale->paid,
                                'created_by' => $this->session->userdata('user_id')
                            );
                            $this->sales_model->updateSaleOnly($sale->id, $saledata);
                            $this->vouchers_model->deleteInvItemById($saleitem->id);
                        }else{
                            if($voucher_original->singles_room==0){
                                //add the item to the sales invoice as it was not existing before
                                //dont update the invoice grand total and total because we dont have the item price yet this will be done in sale edit
                                $invoice_item = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'singles_room',
                                    'quantity' => $data['singles_room'],
                                    'price' => 0,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => 0,
                                );
                                $this->sales_model->addSaleInvoiceItem($invoice_item);
                                $saledata_p = array(
                                    'no_of_rooms' => $sale->no_of_rooms+1,
                                    'total_items' => $sale->no_of_rooms+1,
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_p);
                            }else{
                                //get current item and multiply the price by the item quantity and update the item total
                                //get current sale total and grand total and update them
                                $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("singles_room",$sale->id);
                                $sale_item_total = $saleitem->price*$data['singles_room'];
                                $grand_total = ($sale->grand_total-($saleitem->total*$data['total_nights']))+($sale_item_total*$data['total_nights']);
                                $invoice_item_o = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'singles_room',
                                    'quantity' => $data['singles_room'],
                                    'price' => $saleitem->price,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => $sale_item_total,
                                );
                                $this->sales_model->updateInvoiceItem($saleitem->id, $invoice_item_o);
                                $saledata_q = array(
                                    'voucher_id' => $voucher->id,
                                    'customer_id' => $voucher->customer_id,
                                    'customer' => $customer->name,
                                    'currency' => $sale->currency,
                                    'residence' => $voucher->residence,
                                    'biller_id' => $voucher->biller_id,
                                    'biller' => $biller->name,
                                    'no_of_rooms' => $sale->no_of_rooms,
                                    'warehouse_id' => $voucher->hotel_id,
                                    'category_id' => 54,
                                    'chkindate' => $voucher->check_in,
                                    'chkoutdate' => $voucher->check_out,
                                    'note' => $voucher->remarks,
                                    'staff_note' =>  $voucher->remarks,
                                    'total' => $grand_total,
                                    'product_discount' => $this->sma->formatDecimal(0),
                                    'order_discount_id' => null,
                                    'order_discount' => 0,
                                    'total_discount' => 0,
                                    'product_tax' => $this->sma->formatDecimal(0),
                                    'order_tax_id' => null,
                                    'order_tax' => 0,
                                    'total_tax' => 0,
                                    'shipping' => $this->sma->formatDecimal(0),
                                    'grand_total' => $grand_total,
                                    'total_items' => $sale->no_of_rooms,
                                    'sale_status' => "completed",
                                    'payment_status' => "pending",
                                    'payment_term' => 0,
                                    'due_date' => $sale->due_date,
                                    'paid' => $sale->paid,
                                    'created_by' => $this->session->userdata('user_id')
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_q);
                            }

                        }

                    }
                    if($voucher_original->twin_room!==$data['twin_room']){
                        //if changed check if equal to zero and remove that item from invoice items otherwise add the items to invoice item
                        if($data['twin_room']==0){
                            $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("twin_room",$sale->id);
                            $grand_total = $sale->grand_total-($saleitem->price*$saleitem->quantity*$data['total_nights']);
                            //get current item total and minus from the sale grand total and total then update sale and delete invoice item
                            $saledata = array(
                                'voucher_id' => $voucher->id,
                                'customer_id' => $voucher->customer_id,
                                'customer' => $customer->name,
                                'currency' => $sale->currency,
                                'residence' => $voucher->residence,
                                'biller_id' => $voucher->biller_id,
                                'biller' => $biller->name,
                                'no_of_rooms' => $sale->no_of_rooms-1,
                                'warehouse_id' => $voucher->hotel_id,
                                'category_id' => 54,
                                'chkindate' => $voucher->check_in,
                                'chkoutdate' => $voucher->check_out,
                                'note' => $voucher->remarks,
                                'staff_note' =>  $voucher->remarks,
                                'total' => $grand_total,
                                'product_discount' => $this->sma->formatDecimal(0),
                                'order_discount_id' => null,
                                'order_discount' => 0,
                                'total_discount' => 0,
                                'product_tax' => $this->sma->formatDecimal(0),
                                'order_tax_id' => null,
                                'order_tax' => 0,
                                'total_tax' => 0,
                                'shipping' => $this->sma->formatDecimal(0),
                                'grand_total' => $grand_total,
                                'total_items' => 2,
                                'sale_status' => "completed",
                                'payment_status' => "pending",
                                'payment_term' => 0,
                                'due_date' => $sale->due_date,
                                'paid' => $sale->paid,
                                'created_by' => $this->session->userdata('user_id')
                            );
                            $this->sales_model->updateSaleOnly($sale->id, $saledata);
                            $this->vouchers_model->deleteInvItemById($saleitem->id);
                        }else{
                            if($voucher_original->twin_room==0){
                                //add the item to the sales invoice as it was not existing before
                                //dont update the invoice grand total and total because we dont have the item price yet this will be done in sale edit
                                $invoice_item = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'twin_room',
                                    'quantity' => $data['twin_room'],
                                    'price' => 0,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => 0,
                                );
                                $this->sales_model->addSaleInvoiceItem($invoice_item);
                                $saledata_p = array(
                                    'no_of_rooms' => $sale->no_of_rooms+1,
                                    'total_items' => $sale->no_of_rooms+1,
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_p);
                            }else{
                                //get current item and multiply the price by the item quantity and update the item total
                                //get current sale total and grand total and update them
                                $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("twin_room",$sale->id);
                                $sale_item_total = $saleitem->price*$data['twin_room'];
                                $grand_total = ($sale->grand_total-($saleitem->total*$data['total_nights']))+($sale_item_total*$data['total_nights']);
                                $invoice_item_o = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'twin_room',
                                    'quantity' => $data['twin_room'],
                                    'price' => $saleitem->price,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => $sale_item_total,
                                );
                                $this->sales_model->updateInvoiceItem($saleitem->id, $invoice_item_o);
                                $saledata_q = array(
                                    'voucher_id' => $voucher->id,
                                    'customer_id' => $voucher->customer_id,
                                    'customer' => $customer->name,
                                    'currency' => $sale->currency,
                                    'residence' => $voucher->residence,
                                    'biller_id' => $voucher->biller_id,
                                    'biller' => $biller->name,
                                    'no_of_rooms' => $sale->no_of_rooms,
                                    'warehouse_id' => $voucher->hotel_id,
                                    'category_id' => 54,
                                    'chkindate' => $voucher->check_in,
                                    'chkoutdate' => $voucher->check_out,
                                    'note' => $voucher->remarks,
                                    'staff_note' =>  $voucher->remarks,
                                    'total' => $grand_total,
                                    'product_discount' => $this->sma->formatDecimal(0),
                                    'order_discount_id' => null,
                                    'order_discount' => 0,
                                    'total_discount' => 0,
                                    'product_tax' => $this->sma->formatDecimal(0),
                                    'order_tax_id' => null,
                                    'order_tax' => 0,
                                    'total_tax' => 0,
                                    'shipping' => $this->sma->formatDecimal(0),
                                    'grand_total' => $grand_total,
                                    'total_items' => $sale->no_of_rooms,
                                    'sale_status' => "completed",
                                    'payment_status' => "pending",
                                    'payment_term' => 0,
                                    'due_date' => $sale->due_date,
                                    'paid' => $sale->paid,
                                    'created_by' => $this->session->userdata('user_id')
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_q);
                            }

                        }

                    }
                    if($voucher_original->extra_bed!==$data['extra_bed']){
                        //extrabed
                        //if changed check if equal to zero and remove that item from invoice items otherwise add the items to invoice item
                        if($data['extra_bed']==0){
                            $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("extrabed",$sale->id);
                            $grand_total = $sale->grand_total-($saleitem->price*$saleitem->quantity*$data['total_nights']);
                            //get current item total and minus from the sale grand total and total then update sale and delete invoice item
                            $saledata = array(
                                'voucher_id' => $voucher->id,
                                'customer_id' => $voucher->customer_id,
                                'customer' => $customer->name,
                                'currency' => $sale->currency,
                                'residence' => $voucher->residence,
                                'biller_id' => $voucher->biller_id,
                                'biller' => $biller->name,
                                'no_of_rooms' => $sale->no_of_rooms-1,
                                'warehouse_id' => $voucher->hotel_id,
                                'category_id' => 54,
                                'chkindate' => $voucher->check_in,
                                'chkoutdate' => $voucher->check_out,
                                'note' => $voucher->remarks,
                                'staff_note' =>  $voucher->remarks,
                                'total' => $grand_total,
                                'product_discount' => $this->sma->formatDecimal(0),
                                'order_discount_id' => null,
                                'order_discount' => 0,
                                'total_discount' => 0,
                                'product_tax' => $this->sma->formatDecimal(0),
                                'order_tax_id' => null,
                                'order_tax' => 0,
                                'total_tax' => 0,
                                'shipping' => $this->sma->formatDecimal(0),
                                'grand_total' => $grand_total,
                                'total_items' => 2,
                                'sale_status' => "completed",
                                'payment_status' => "pending",
                                'payment_term' => 0,
                                'due_date' => $sale->due_date,
                                'paid' => $sale->paid,
                                'created_by' => $this->session->userdata('user_id')
                            );
                            $this->sales_model->updateSaleOnly($sale->id, $saledata);
                            $this->vouchers_model->deleteInvItemById($saleitem->id);
                        }else{
                            if($voucher_original->extra_bed==0){
                                //add the item to the sales invoice as it was not existing before
                                //dont update the invoice grand total and total because we dont have the item price yet this will be done in sale edit
                                $invoice_item = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'extrabed',
                                    'quantity' => $data['extra_bed'],
                                    'price' => 0,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => 0,
                                );
                                $this->sales_model->addSaleInvoiceItem($invoice_item);
                                $saledata_p = array(
                                    'no_of_rooms' => $sale->no_of_rooms+1,
                                    'total_items' => $sale->no_of_rooms+1,
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_p);
                            }else{
                                //get current item and multiply the price by the item quantity and update the item total
                                //get current sale total and grand total and update them
                                $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("extrabed",$sale->id);
                                $sale_item_total = $saleitem->price*$data['extra_bed'];
                                $grand_total = ($sale->grand_total-($saleitem->total*$data['total_nights']))+($sale_item_total*$data['total_nights']);
                                $invoice_item_o = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'extrabed',
                                    'quantity' => $data['extra_bed'],
                                    'price' => $saleitem->price,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => $sale_item_total,
                                );
                                $this->sales_model->updateInvoiceItem($saleitem->id, $invoice_item_o);
                                $saledata_q = array(
                                    'voucher_id' => $voucher->id,
                                    'customer_id' => $voucher->customer_id,
                                    'customer' => $customer->name,
                                    'currency' => $sale->currency,
                                    'residence' => $voucher->residence,
                                    'biller_id' => $voucher->biller_id,
                                    'biller' => $biller->name,
                                    'no_of_rooms' => $sale->no_of_rooms,
                                    'warehouse_id' => $voucher->hotel_id,
                                    'category_id' => 54,
                                    'chkindate' => $voucher->check_in,
                                    'chkoutdate' => $voucher->check_out,
                                    'note' => $voucher->remarks,
                                    'staff_note' =>  $voucher->remarks,
                                    'total' => $grand_total,
                                    'product_discount' => $this->sma->formatDecimal(0),
                                    'order_discount_id' => null,
                                    'order_discount' => 0,
                                    'total_discount' => 0,
                                    'product_tax' => $this->sma->formatDecimal(0),
                                    'order_tax_id' => null,
                                    'order_tax' => 0,
                                    'total_tax' => 0,
                                    'shipping' => $this->sma->formatDecimal(0),
                                    'grand_total' => $grand_total,
                                    'total_items' => $sale->no_of_rooms,
                                    'sale_status' => "completed",
                                    'payment_status' => "pending",
                                    'payment_term' => 0,
                                    'due_date' => $sale->due_date,
                                    'paid' => $sale->paid,
                                    'created_by' => $this->session->userdata('user_id')
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_q);
                            }

                        }

                    }
                }else{
                    if($voucher_original->executive_room!==$data['executive_room']){
                        //if changed check if equal to zero and remove that item from invoice items otherwise add the items to invoice item
                        if($data['executive_room']==0){
                            $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("executive_room",$sale->id);
                            $this->vouchers_model->deleteInvItemById($saleitem->id);
                            $saleitems = $this->sales_model->getAllInvoiceItemsByInvoiceId($sale->id);
                            $grand_total = 0;
                            foreach  ($saleitems as $item){
                                $grand_total+=$item->total;
                            }

                            //get current item total and minus from the sale grand total and total then update sale and delete invoice item
                            $saledata_r = array(
                                'voucher_id' => $voucher->id,
                                'customer_id' => $voucher->customer_id,
                                'customer' => $customer->name,
                                'currency' => $sale->currency,
                                'residence' => $voucher->residence,
                                'biller_id' => $voucher->biller_id,
                                'biller' => $biller->name,
                                'no_of_rooms' => $sale->no_of_rooms-1,
                                'warehouse_id' => $voucher->hotel_id,
                                'category_id' => 54,
                                'chkindate' => $voucher->check_in,
                                'chkoutdate' => $voucher->check_out,
                                'note' => $voucher->remarks,
                                'staff_note' =>  $voucher->remarks,
                                'total' => $grand_total*$data['total_nights'],
                                'product_discount' => $this->sma->formatDecimal(0),
                                'order_discount_id' => null,
                                'order_discount' => 0,
                                'total_discount' => 0,
                                'product_tax' => $this->sma->formatDecimal(0),
                                'order_tax_id' => null,
                                'order_tax' => 0,
                                'total_tax' => 0,
                                'shipping' => $this->sma->formatDecimal(0),
                                'grand_total' => $grand_total*$data['total_nights'],
                                'total_items' => $sale->no_of_rooms-1,
                                'sale_status' => "completed",
                                'payment_status' => "pending",
                                'payment_term' => 0,
                                'due_date' => $sale->due_date,
                                'paid' => $sale->paid,
                                'created_by' => $this->session->userdata('user_id')
                            );
                            $this->sales_model->updateSaleOnly($sale->id, $saledata_r);

                        }else{
                            if($voucher_original->executive_room==0){
                                //add the item to the sales invoice as it was not existing before
                                //dont update the invoice grand total and total because we dont have the item price yet this will be done in sale edit
                                $invoice_item_p = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'executive_room',
                                    'quantity' => $data['executive_room'],
                                    'price' => 0,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => 0,
                                );
                                $this->sales_model->addSaleInvoiceItem($invoice_item_p);
                                $saledata_z = array(
                                    'no_of_rooms' => $sale->no_of_rooms+1,
                                    'total_items' => $sale->no_of_rooms+1,
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_z);
                            }else{
                                //get current item and multiply the price by the item quantity and update the item total
                                //get current sale total and grand total and update them
                                $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("executive_room",$sale->id);
                                $sale_item_total = $saleitem->price*$data['executive_room'];
                                $invoice_item_q = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'executive_room',
                                    'quantity' => $data['executive_room'],
                                    'price' => $saleitem->price,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => $sale_item_total,
                                );
                                $this->sales_model->updateInvoiceItem($saleitem->id, $invoice_item_q);
                                $saleitems = $this->sales_model->getAllInvoiceItemsByInvoiceId($sale->id);
                                $grand_total = 0;
                                foreach  ($saleitems as $item){
                                    $grand_total+=$item->total;
                                }
                                $saledata_x = array(
                                    'voucher_id' => $voucher->id,
                                    'customer_id' => $voucher->customer_id,
                                    'customer' => $customer->name,
                                    'currency' => $sale->currency,
                                    'residence' => $voucher->residence,
                                    'biller_id' => $voucher->biller_id,
                                    'biller' => $biller->name,
                                    'no_of_rooms' => $sale->no_of_rooms,
                                    'warehouse_id' => $voucher->hotel_id,
                                    'category_id' => 54,
                                    'chkindate' => $voucher->check_in,
                                    'chkoutdate' => $voucher->check_out,
                                    'note' => $voucher->remarks,
                                    'staff_note' =>  $voucher->remarks,
                                    'total' => $grand_total*$data['total_nights'],
                                    'product_discount' => $this->sma->formatDecimal(0),
                                    'order_discount_id' => null,
                                    'order_discount' => 0,
                                    'total_discount' => 0,
                                    'product_tax' => $this->sma->formatDecimal(0),
                                    'order_tax_id' => null,
                                    'order_tax' => 0,
                                    'total_tax' => 0,
                                    'shipping' => $this->sma->formatDecimal(0),
                                    'grand_total' => $grand_total*$data['total_nights'],
                                    'total_items' => $sale->no_of_rooms,
                                    'sale_status' => "completed",
                                    'payment_status' => "pending",
                                    'payment_term' => 0,
                                    'due_date' => $sale->due_date,
                                    'paid' => $sale->paid,
                                    'created_by' => $this->session->userdata('user_id')
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_x);
                            }

                        }

                    }
                    if($voucher_original->superior_room!==$data['superior_room']){
                        //if changed check if equal to zero and remove that item from invoice items otherwise add the items to invoice item
                        if($data['superior_room']==0){
                            $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("superior_room",$sale->id);
                            $this->vouchers_model->deleteInvItemById($saleitem->id);
                            $saleitems = $this->sales_model->getAllInvoiceItemsByInvoiceId($sale->id);
                            $grand_total = 0;
                            foreach  ($saleitems as $item){
                                $grand_total+=$item->total;
                            }

                            //get current item total and minus from the sale grand total and total then update sale and delete invoice item
                            $saledata_r = array(
                                'voucher_id' => $voucher->id,
                                'customer_id' => $voucher->customer_id,
                                'customer' => $customer->name,
                                'currency' => $sale->currency,
                                'residence' => $voucher->residence,
                                'biller_id' => $voucher->biller_id,
                                'biller' => $biller->name,
                                'no_of_rooms' => $sale->no_of_rooms-1,
                                'warehouse_id' => $voucher->hotel_id,
                                'category_id' => 54,
                                'chkindate' => $voucher->check_in,
                                'chkoutdate' => $voucher->check_out,
                                'note' => $voucher->remarks,
                                'staff_note' =>  $voucher->remarks,
                                'total' => $grand_total*$data['total_nights'],
                                'product_discount' => $this->sma->formatDecimal(0),
                                'order_discount_id' => null,
                                'order_discount' => 0,
                                'total_discount' => 0,
                                'product_tax' => $this->sma->formatDecimal(0),
                                'order_tax_id' => null,
                                'order_tax' => 0,
                                'total_tax' => 0,
                                'shipping' => $this->sma->formatDecimal(0),
                                'grand_total' => $grand_total*$data['total_nights'],
                                'total_items' => $sale->no_of_rooms-1,
                                'sale_status' => "completed",
                                'payment_status' => "pending",
                                'payment_term' => 0,
                                'due_date' => $sale->due_date,
                                'paid' => $sale->paid,
                                'created_by' => $this->session->userdata('user_id')
                            );
                            $this->sales_model->updateSaleOnly($sale->id, $saledata_r);

                        }else{
                            if($voucher_original->superior_room==0){
                                //add the item to the sales invoice as it was not existing before
                                //dont update the invoice grand total and total because we dont have the item price yet this will be done in sale edit
                                $invoice_item_p = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'superior_room',
                                    'quantity' => $data['superior_room'],
                                    'price' => 0,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => 0,
                                );
                                $this->sales_model->addSaleInvoiceItem($invoice_item_p);
                                $saledata_z = array(
                                    'no_of_rooms' => $sale->no_of_rooms+1,
                                    'total_items' => $sale->no_of_rooms+1,
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_z);
                            }else{
                                //get current item and multiply the price by the item quantity and update the item total
                                //get current sale total and grand total and update them
                                $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("superior_room",$sale->id);
                                $sale_item_total = $saleitem->price*$data['superior_room'];
                                $invoice_item_q = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'superior_room',
                                    'quantity' => $data['superior_room'],
                                    'price' => $saleitem->price,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => $sale_item_total,
                                );
                                $this->sales_model->updateInvoiceItem($saleitem->id, $invoice_item_q);
                                $saleitems = $this->sales_model->getAllInvoiceItemsByInvoiceId($sale->id);
                                $grand_total = 0;
                                foreach  ($saleitems as $item){
                                    $grand_total+=$item->total;
                                }
                                $saledata_x = array(
                                    'voucher_id' => $voucher->id,
                                    'customer_id' => $voucher->customer_id,
                                    'customer' => $customer->name,
                                    'currency' => $sale->currency,
                                    'residence' => $voucher->residence,
                                    'biller_id' => $voucher->biller_id,
                                    'biller' => $biller->name,
                                    'no_of_rooms' => $sale->no_of_rooms,
                                    'warehouse_id' => $voucher->hotel_id,
                                    'category_id' => 54,
                                    'chkindate' => $voucher->check_in,
                                    'chkoutdate' => $voucher->check_out,
                                    'note' => $voucher->remarks,
                                    'staff_note' =>  $voucher->remarks,
                                    'total' => $grand_total*$data['total_nights'],
                                    'product_discount' => $this->sma->formatDecimal(0),
                                    'order_discount_id' => null,
                                    'order_discount' => 0,
                                    'total_discount' => 0,
                                    'product_tax' => $this->sma->formatDecimal(0),
                                    'order_tax_id' => null,
                                    'order_tax' => 0,
                                    'total_tax' => 0,
                                    'shipping' => $this->sma->formatDecimal(0),
                                    'grand_total' => $grand_total*$data['total_nights'],
                                    'total_items' => $sale->no_of_rooms,
                                    'sale_status' => "completed",
                                    'payment_status' => "pending",
                                    'payment_term' => 0,
                                    'due_date' => $sale->due_date,
                                    'paid' => $sale->paid,
                                    'created_by' => $this->session->userdata('user_id')
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_x);
                            }

                        }

                    }
                    if($voucher_original->deluxe_room!==$data['deluxe_room']){
                        //if changed check if equal to zero and remove that item from invoice items otherwise add the items to invoice item
                        if($data['deluxe_room']==0){
                            $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("deluxe_room",$sale->id);
                            $this->vouchers_model->deleteInvItemById($saleitem->id);
                            $saleitems = $this->sales_model->getAllInvoiceItemsByInvoiceId($sale->id);
                            $grand_total = 0;
                            foreach  ($saleitems as $item){
                                $grand_total+=$item->total;
                            }

                            //get current item total and minus from the sale grand total and total then update sale and delete invoice item
                            $saledata_r = array(
                                'voucher_id' => $voucher->id,
                                'customer_id' => $voucher->customer_id,
                                'customer' => $customer->name,
                                'currency' => $sale->currency,
                                'residence' => $voucher->residence,
                                'biller_id' => $voucher->biller_id,
                                'biller' => $biller->name,
                                'no_of_rooms' => $sale->no_of_rooms-1,
                                'warehouse_id' => $voucher->hotel_id,
                                'category_id' => 54,
                                'chkindate' => $voucher->check_in,
                                'chkoutdate' => $voucher->check_out,
                                'note' => $voucher->remarks,
                                'staff_note' =>  $voucher->remarks,
                                'total' => $grand_total*$data['total_nights'],
                                'product_discount' => $this->sma->formatDecimal(0),
                                'order_discount_id' => null,
                                'order_discount' => 0,
                                'total_discount' => 0,
                                'product_tax' => $this->sma->formatDecimal(0),
                                'order_tax_id' => null,
                                'order_tax' => 0,
                                'total_tax' => 0,
                                'shipping' => $this->sma->formatDecimal(0),
                                'grand_total' => $grand_total*$data['total_nights'],
                                'total_items' => $sale->no_of_rooms-1,
                                'sale_status' => "completed",
                                'payment_status' => "pending",
                                'payment_term' => 0,
                                'due_date' => $sale->due_date,
                                'paid' => $sale->paid,
                                'created_by' => $this->session->userdata('user_id')
                            );
                            $this->sales_model->updateSaleOnly($sale->id, $saledata_r);

                        }else{
                            if($voucher_original->deluxe_room==0){
                                //add the item to the sales invoice as it was not existing before
                                //dont update the invoice grand total and total because we dont have the item price yet this will be done in sale edit
                                $invoice_item_p = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'deluxe_room',
                                    'quantity' => $data['deluxe_room'],
                                    'price' => 0,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => 0,
                                );
                                $this->sales_model->addSaleInvoiceItem($invoice_item_p);
                                $saledata_z = array(
                                    'no_of_rooms' => $sale->no_of_rooms+1,
                                    'total_items' => $sale->no_of_rooms+1,
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_z);
                            }else{
                                //get current item and multiply the price by the item quantity and update the item total
                                //get current sale total and grand total and update them
                                $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("deluxe_room",$sale->id);
                                $sale_item_total = $saleitem->price*$data['deluxe_room'];
                                $invoice_item_q = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'deluxe_room',
                                    'quantity' => $data['deluxe_room'],
                                    'price' => $saleitem->price,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => $sale_item_total,
                                );
                                $this->sales_model->updateInvoiceItem($saleitem->id, $invoice_item_q);
                                $saleitems = $this->sales_model->getAllInvoiceItemsByInvoiceId($sale->id);
                                $grand_total = 0;
                                foreach  ($saleitems as $item){
                                    $grand_total+=$item->total;
                                }
                                $saledata_x = array(
                                    'voucher_id' => $voucher->id,
                                    'customer_id' => $voucher->customer_id,
                                    'customer' => $customer->name,
                                    'currency' => $sale->currency,
                                    'residence' => $voucher->residence,
                                    'biller_id' => $voucher->biller_id,
                                    'biller' => $biller->name,
                                    'no_of_rooms' => $sale->no_of_rooms,
                                    'warehouse_id' => $voucher->hotel_id,
                                    'category_id' => 54,
                                    'chkindate' => $voucher->check_in,
                                    'chkoutdate' => $voucher->check_out,
                                    'note' => $voucher->remarks,
                                    'staff_note' =>  $voucher->remarks,
                                    'total' => $grand_total*$data['total_nights'],
                                    'product_discount' => $this->sma->formatDecimal(0),
                                    'order_discount_id' => null,
                                    'order_discount' => 0,
                                    'total_discount' => 0,
                                    'product_tax' => $this->sma->formatDecimal(0),
                                    'order_tax_id' => null,
                                    'order_tax' => 0,
                                    'total_tax' => 0,
                                    'shipping' => $this->sma->formatDecimal(0),
                                    'grand_total' => $grand_total*$data['total_nights'],
                                    'total_items' => $sale->no_of_rooms,
                                    'sale_status' => "completed",
                                    'payment_status' => "pending",
                                    'payment_term' => 0,
                                    'due_date' => $sale->due_date,
                                    'paid' => $sale->paid,
                                    'created_by' => $this->session->userdata('user_id')
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_x);
                            }

                        }

                    }
                    if($voucher_original->standard_room!==$data['standard_room']){
                        //if changed check if equal to zero and remove that item from invoice items otherwise add the items to invoice item
                        if($data['standard_room']==0){
                            $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("standard_room",$sale->id);
                            $this->vouchers_model->deleteInvItemById($saleitem->id);
                            $saleitems = $this->sales_model->getAllInvoiceItemsByInvoiceId($sale->id);
                            $grand_total = 0;
                            foreach  ($saleitems as $item){
                                $grand_total+=$item->total;
                            }

                            //get current item total and minus from the sale grand total and total then update sale and delete invoice item
                            $saledata_r = array(
                                'voucher_id' => $voucher->id,
                                'customer_id' => $voucher->customer_id,
                                'customer' => $customer->name,
                                'currency' => $sale->currency,
                                'residence' => $voucher->residence,
                                'biller_id' => $voucher->biller_id,
                                'biller' => $biller->name,
                                'no_of_rooms' => $sale->no_of_rooms-1,
                                'warehouse_id' => $voucher->hotel_id,
                                'category_id' => 54,
                                'chkindate' => $voucher->check_in,
                                'chkoutdate' => $voucher->check_out,
                                'note' => $voucher->remarks,
                                'staff_note' =>  $voucher->remarks,
                                'total' => $grand_total*$data['total_nights'],
                                'product_discount' => $this->sma->formatDecimal(0),
                                'order_discount_id' => null,
                                'order_discount' => 0,
                                'total_discount' => 0,
                                'product_tax' => $this->sma->formatDecimal(0),
                                'order_tax_id' => null,
                                'order_tax' => 0,
                                'total_tax' => 0,
                                'shipping' => $this->sma->formatDecimal(0),
                                'grand_total' => $grand_total*$data['total_nights'],
                                'total_items' => $sale->no_of_rooms-1,
                                'sale_status' => "completed",
                                'payment_status' => "pending",
                                'payment_term' => 0,
                                'due_date' => $sale->due_date,
                                'paid' => $sale->paid,
                                'created_by' => $this->session->userdata('user_id')
                            );
                            $this->sales_model->updateSaleOnly($sale->id, $saledata_r);

                        }else{
                            if($voucher_original->standard_room==0){
                                //add the item to the sales invoice as it was not existing before
                                //dont update the invoice grand total and total because we dont have the item price yet this will be done in sale edit
                                $invoice_item_p = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'standard_room',
                                    'quantity' => $data['standard_room'],
                                    'price' => 0,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => 0,
                                );
                                $this->sales_model->addSaleInvoiceItem($invoice_item_p);
                                $saledata_z = array(
                                    'no_of_rooms' => $sale->no_of_rooms+1,
                                    'total_items' => $sale->no_of_rooms+1,
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_z);
                            }else{
                                //get current item and multiply the price by the item quantity and update the item total
                                //get current sale total and grand total and update them
                                $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("standard_room",$sale->id);
                                $sale_item_total = $saleitem->price*$data['standard_room'];
                                $invoice_item_q = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'standard_room',
                                    'quantity' => $data['standard_room'],
                                    'price' => $saleitem->price,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => $sale_item_total,
                                );
                                $this->sales_model->updateInvoiceItem($saleitem->id, $invoice_item_q);
                                $saleitems = $this->sales_model->getAllInvoiceItemsByInvoiceId($sale->id);
                                $grand_total = 0;
                                foreach  ($saleitems as $item){
                                    $grand_total+=$item->total;
                                }
                                $saledata_x = array(
                                    'voucher_id' => $voucher->id,
                                    'customer_id' => $voucher->customer_id,
                                    'customer' => $customer->name,
                                    'currency' => $sale->currency,
                                    'residence' => $voucher->residence,
                                    'biller_id' => $voucher->biller_id,
                                    'biller' => $biller->name,
                                    'no_of_rooms' => $sale->no_of_rooms,
                                    'warehouse_id' => $voucher->hotel_id,
                                    'category_id' => 54,
                                    'chkindate' => $voucher->check_in,
                                    'chkoutdate' => $voucher->check_out,
                                    'note' => $voucher->remarks,
                                    'staff_note' =>  $voucher->remarks,
                                    'total' => $grand_total*$data['total_nights'],
                                    'product_discount' => $this->sma->formatDecimal(0),
                                    'order_discount_id' => null,
                                    'order_discount' => 0,
                                    'total_discount' => 0,
                                    'product_tax' => $this->sma->formatDecimal(0),
                                    'order_tax_id' => null,
                                    'order_tax' => 0,
                                    'total_tax' => 0,
                                    'shipping' => $this->sma->formatDecimal(0),
                                    'grand_total' => $grand_total*$data['total_nights'],
                                    'total_items' => $sale->no_of_rooms,
                                    'sale_status' => "completed",
                                    'payment_status' => "pending",
                                    'payment_term' => 0,
                                    'due_date' => $sale->due_date,
                                    'paid' => $sale->paid,
                                    'created_by' => $this->session->userdata('user_id')
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_x);
                            }

                        }

                    }
                    if($voucher_original->singles_room!==$data['singles_room']){
                        //if changed check if equal to zero and remove that item from invoice items otherwise add the items to invoice item
                        if($data['singles_room']==0){
                            $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("singles_room",$sale->id);
                            $this->vouchers_model->deleteInvItemById($saleitem->id);
                            $saleitems = $this->sales_model->getAllInvoiceItemsByInvoiceId($sale->id);
                            $grand_total = 0;
                            foreach  ($saleitems as $item){
                                $grand_total+=$item->total;
                            }

                            //get current item total and minus from the sale grand total and total then update sale and delete invoice item
                            $saledata_r = array(
                                'voucher_id' => $voucher->id,
                                'customer_id' => $voucher->customer_id,
                                'customer' => $customer->name,
                                'currency' => $sale->currency,
                                'residence' => $voucher->residence,
                                'biller_id' => $voucher->biller_id,
                                'biller' => $biller->name,
                                'no_of_rooms' => $sale->no_of_rooms-1,
                                'warehouse_id' => $voucher->hotel_id,
                                'category_id' => 54,
                                'chkindate' => $voucher->check_in,
                                'chkoutdate' => $voucher->check_out,
                                'note' => $voucher->remarks,
                                'staff_note' =>  $voucher->remarks,
                                'total' => $grand_total*$data['total_nights'],
                                'product_discount' => $this->sma->formatDecimal(0),
                                'order_discount_id' => null,
                                'order_discount' => 0,
                                'total_discount' => 0,
                                'product_tax' => $this->sma->formatDecimal(0),
                                'order_tax_id' => null,
                                'order_tax' => 0,
                                'total_tax' => 0,
                                'shipping' => $this->sma->formatDecimal(0),
                                'grand_total' => $grand_total*$data['total_nights'],
                                'total_items' => $sale->no_of_rooms-1,
                                'sale_status' => "completed",
                                'payment_status' => "pending",
                                'payment_term' => 0,
                                'due_date' => $sale->due_date,
                                'paid' => $sale->paid,
                                'created_by' => $this->session->userdata('user_id')
                            );
                            $this->sales_model->updateSaleOnly($sale->id, $saledata_r);

                        }else{
                            if($voucher_original->singles_room==0){
                                //add the item to the sales invoice as it was not existing before
                                //dont update the invoice grand total and total because we dont have the item price yet this will be done in sale edit
                                $invoice_item_p = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'singles_room',
                                    'quantity' => $data['singles_room'],
                                    'price' => 0,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => 0,
                                );
                                $this->sales_model->addSaleInvoiceItem($invoice_item_p);
                                $saledata_z = array(
                                    'no_of_rooms' => $sale->no_of_rooms+1,
                                    'total_items' => $sale->no_of_rooms+1,
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_z);
                            }else{
                                //get current item and multiply the price by the item quantity and update the item total
                                //get current sale total and grand total and update them
                                $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("singles_room",$sale->id);
                                $sale_item_total = $saleitem->price*$data['singles_room'];
                                $invoice_item_q = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'singles_room',
                                    'quantity' => $data['singles_room'],
                                    'price' => $saleitem->price,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => $sale_item_total,
                                );
                                $this->sales_model->updateInvoiceItem($saleitem->id, $invoice_item_q);
                                $saleitems = $this->sales_model->getAllInvoiceItemsByInvoiceId($sale->id);
                                $grand_total = 0;
                                foreach  ($saleitems as $item){
                                    $grand_total+=$item->total;
                                }
                                $saledata_x = array(
                                    'voucher_id' => $voucher->id,
                                    'customer_id' => $voucher->customer_id,
                                    'customer' => $customer->name,
                                    'currency' => $sale->currency,
                                    'residence' => $voucher->residence,
                                    'biller_id' => $voucher->biller_id,
                                    'biller' => $biller->name,
                                    'no_of_rooms' => $sale->no_of_rooms,
                                    'warehouse_id' => $voucher->hotel_id,
                                    'category_id' => 54,
                                    'chkindate' => $voucher->check_in,
                                    'chkoutdate' => $voucher->check_out,
                                    'note' => $voucher->remarks,
                                    'staff_note' =>  $voucher->remarks,
                                    'total' => $grand_total*$data['total_nights'],
                                    'product_discount' => $this->sma->formatDecimal(0),
                                    'order_discount_id' => null,
                                    'order_discount' => 0,
                                    'total_discount' => 0,
                                    'product_tax' => $this->sma->formatDecimal(0),
                                    'order_tax_id' => null,
                                    'order_tax' => 0,
                                    'total_tax' => 0,
                                    'shipping' => $this->sma->formatDecimal(0),
                                    'grand_total' => $grand_total*$data['total_nights'],
                                    'total_items' => $sale->no_of_rooms,
                                    'sale_status' => "completed",
                                    'payment_status' => "pending",
                                    'payment_term' => 0,
                                    'due_date' => $sale->due_date,
                                    'paid' => $sale->paid,
                                    'created_by' => $this->session->userdata('user_id')
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_x);
                            }

                        }

                    }
                    if($voucher_original->twin_room!==$data['twin_room']){
                        //if changed check if equal to zero and remove that item from invoice items otherwise add the items to invoice item
                        if($data['twin_room']==0){
                            $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("twin_room",$sale->id);
                            $this->vouchers_model->deleteInvItemById($saleitem->id);
                            $saleitems = $this->sales_model->getAllInvoiceItemsByInvoiceId($sale->id);
                            $grand_total = 0;
                            foreach  ($saleitems as $item){
                                $grand_total+=$item->total;
                            }

                            //get current item total and minus from the sale grand total and total then update sale and delete invoice item
                            $saledata_r = array(
                                'voucher_id' => $voucher->id,
                                'customer_id' => $voucher->customer_id,
                                'customer' => $customer->name,
                                'currency' => $sale->currency,
                                'residence' => $voucher->residence,
                                'biller_id' => $voucher->biller_id,
                                'biller' => $biller->name,
                                'no_of_rooms' => $sale->no_of_rooms-1,
                                'warehouse_id' => $voucher->hotel_id,
                                'category_id' => 54,
                                'chkindate' => $voucher->check_in,
                                'chkoutdate' => $voucher->check_out,
                                'note' => $voucher->remarks,
                                'staff_note' =>  $voucher->remarks,
                                'total' => $grand_total*$data['total_nights'],
                                'product_discount' => $this->sma->formatDecimal(0),
                                'order_discount_id' => null,
                                'order_discount' => 0,
                                'total_discount' => 0,
                                'product_tax' => $this->sma->formatDecimal(0),
                                'order_tax_id' => null,
                                'order_tax' => 0,
                                'total_tax' => 0,
                                'shipping' => $this->sma->formatDecimal(0),
                                'grand_total' => $grand_total*$data['total_nights'],
                                'total_items' => $sale->no_of_rooms-1,
                                'sale_status' => "completed",
                                'payment_status' => "pending",
                                'payment_term' => 0,
                                'due_date' => $sale->due_date,
                                'paid' => $sale->paid,
                                'created_by' => $this->session->userdata('user_id')
                            );
                            $this->sales_model->updateSaleOnly($sale->id, $saledata_r);

                        }else{
                            if($voucher_original->twin_room==0){
                                //add the item to the sales invoice as it was not existing before
                                //dont update the invoice grand total and total because we dont have the item price yet this will be done in sale edit
                                $invoice_item_p = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'twin_room',
                                    'quantity' => $data['twin_room'],
                                    'price' => 0,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => 0,
                                );
                                $this->sales_model->addSaleInvoiceItem($invoice_item_p);
                                $saledata_z = array(
                                    'no_of_rooms' => $sale->no_of_rooms+1,
                                    'total_items' => $sale->no_of_rooms+1,
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_z);
                            }else{
                                //get current item and multiply the price by the item quantity and update the item total
                                //get current sale total and grand total and update them
                                $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("twin_room",$sale->id);
                                $sale_item_total = $saleitem->price*$data['twin_room'];
                                $invoice_item_q = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'twin_room',
                                    'quantity' => $data['twin_room'],
                                    'price' => $saleitem->price,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => $sale_item_total,
                                );
                                $this->sales_model->updateInvoiceItem($saleitem->id, $invoice_item_q);
                                $saleitems = $this->sales_model->getAllInvoiceItemsByInvoiceId($sale->id);
                                $grand_total = 0;
                                foreach  ($saleitems as $item){
                                    $grand_total+=$item->total;
                                }
                                $saledata_x = array(
                                    'voucher_id' => $voucher->id,
                                    'customer_id' => $voucher->customer_id,
                                    'customer' => $customer->name,
                                    'currency' => $sale->currency,
                                    'residence' => $voucher->residence,
                                    'biller_id' => $voucher->biller_id,
                                    'biller' => $biller->name,
                                    'no_of_rooms' => $sale->no_of_rooms,
                                    'warehouse_id' => $voucher->hotel_id,
                                    'category_id' => 54,
                                    'chkindate' => $voucher->check_in,
                                    'chkoutdate' => $voucher->check_out,
                                    'note' => $voucher->remarks,
                                    'staff_note' =>  $voucher->remarks,
                                    'total' => $grand_total*$data['total_nights'],
                                    'product_discount' => $this->sma->formatDecimal(0),
                                    'order_discount_id' => null,
                                    'order_discount' => 0,
                                    'total_discount' => 0,
                                    'product_tax' => $this->sma->formatDecimal(0),
                                    'order_tax_id' => null,
                                    'order_tax' => 0,
                                    'total_tax' => 0,
                                    'shipping' => $this->sma->formatDecimal(0),
                                    'grand_total' => $grand_total*$data['total_nights'],
                                    'total_items' => $sale->no_of_rooms,
                                    'sale_status' => "completed",
                                    'payment_status' => "pending",
                                    'payment_term' => 0,
                                    'due_date' => $sale->due_date,
                                    'paid' => $sale->paid,
                                    'created_by' => $this->session->userdata('user_id')
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_x);
                            }

                        }

                    }
                    if($voucher_original->extra_bed!==$data['extra_bed']){
                        //extrabed
                        //if changed check if equal to zero and remove that item from invoice items otherwise add the items to invoice item
                        if($data['extra_bed']==0){
                            $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("extrabed",$sale->id);
                            $this->vouchers_model->deleteInvItemById($saleitem->id);
                            $saleitems = $this->sales_model->getAllInvoiceItemsByInvoiceId($sale->id);
                            $grand_total = 0;
                            foreach  ($saleitems as $item){
                                $grand_total+=$item->total;
                            }

                            //get current item total and minus from the sale grand total and total then update sale and delete invoice item
                            $saledata_r = array(
                                'voucher_id' => $voucher->id,
                                'customer_id' => $voucher->customer_id,
                                'customer' => $customer->name,
                                'currency' => $sale->currency,
                                'residence' => $voucher->residence,
                                'biller_id' => $voucher->biller_id,
                                'biller' => $biller->name,
                                'no_of_rooms' => $sale->no_of_rooms-1,
                                'warehouse_id' => $voucher->hotel_id,
                                'category_id' => 54,
                                'chkindate' => $voucher->check_in,
                                'chkoutdate' => $voucher->check_out,
                                'note' => $voucher->remarks,
                                'staff_note' =>  $voucher->remarks,
                                'total' => $grand_total*$data['total_nights'],
                                'product_discount' => $this->sma->formatDecimal(0),
                                'order_discount_id' => null,
                                'order_discount' => 0,
                                'total_discount' => 0,
                                'product_tax' => $this->sma->formatDecimal(0),
                                'order_tax_id' => null,
                                'order_tax' => 0,
                                'total_tax' => 0,
                                'shipping' => $this->sma->formatDecimal(0),
                                'grand_total' => $grand_total*$data['total_nights'],
                                'total_items' => $sale->no_of_rooms-1,
                                'sale_status' => "completed",
                                'payment_status' => "pending",
                                'payment_term' => 0,
                                'due_date' => $sale->due_date,
                                'paid' => $sale->paid,
                                'created_by' => $this->session->userdata('user_id')
                            );
                            $this->sales_model->updateSaleOnly($sale->id, $saledata_r);

                        }else{
                            if($voucher_original->extra_bed==0){
                                //add the item to the sales invoice as it was not existing before
                                //dont update the invoice grand total and total because we dont have the item price yet this will be done in sale edit
                                $invoice_item_p = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'extrabed',
                                    'quantity' => $data['extra_bed'],
                                    'price' => 0,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => 0,
                                );
                                $this->sales_model->addSaleInvoiceItem($invoice_item_p);
                                $saledata_z = array(
                                    'no_of_rooms' => $sale->no_of_rooms+1,
                                    'total_items' => $sale->no_of_rooms+1,
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_z);
                            }else{
                                //get current item and multiply the price by the item quantity and update the item total
                                //get current sale total and grand total and update them
                                $saleitem = $this->sales_model->getInvoiceItemByNameAndInvoiceID("extrabed",$sale->id);
                                $sale_item_total = $saleitem->price*$data['extra_bed'];
                                $invoice_item_q = array(
                                    'sale_id' => $sale->id,
                                    'name' => 'extrabed',
                                    'quantity' => $data['extra_bed'],
                                    'price' => $saleitem->price,
                                    'no_adults' => 0,
                                    'no_children' => 0,
                                    'pax' => 0,
                                    'total' => $sale_item_total,
                                );
                                $this->sales_model->updateInvoiceItem($saleitem->id, $invoice_item_q);
                                $saleitems = $this->sales_model->getAllInvoiceItemsByInvoiceId($sale->id);
                                $grand_total = 0;
                                foreach  ($saleitems as $item){
                                    $grand_total+=$item->total;
                                }
                                $saledata_x = array(
                                    'voucher_id' => $voucher->id,
                                    'customer_id' => $voucher->customer_id,
                                    'customer' => $customer->name,
                                    'currency' => $sale->currency,
                                    'residence' => $voucher->residence,
                                    'biller_id' => $voucher->biller_id,
                                    'biller' => $biller->name,
                                    'no_of_rooms' => $sale->no_of_rooms,
                                    'warehouse_id' => $voucher->hotel_id,
                                    'category_id' => 54,
                                    'chkindate' => $voucher->check_in,
                                    'chkoutdate' => $voucher->check_out,
                                    'note' => $voucher->remarks,
                                    'staff_note' =>  $voucher->remarks,
                                    'total' => $grand_total*$data['total_nights'],
                                    'product_discount' => $this->sma->formatDecimal(0),
                                    'order_discount_id' => null,
                                    'order_discount' => 0,
                                    'total_discount' => 0,
                                    'product_tax' => $this->sma->formatDecimal(0),
                                    'order_tax_id' => null,
                                    'order_tax' => 0,
                                    'total_tax' => 0,
                                    'shipping' => $this->sma->formatDecimal(0),
                                    'grand_total' => $grand_total*$data['total_nights'],
                                    'total_items' => $sale->no_of_rooms,
                                    'sale_status' => "completed",
                                    'payment_status' => "pending",
                                    'payment_term' => 0,
                                    'due_date' => $sale->due_date,
                                    'paid' => $sale->paid,
                                    'created_by' => $this->session->userdata('user_id')
                                );
                                $this->sales_model->updateSaleOnly($sale->id, $saledata_x);
                            }

                        }

                    }
                    //update item total get all items and calculate grand total by adding the item total and multiplying by new no of nights
                }
            }



            $this->session->set_flashdata('message', $this->lang->line("voucher_updated"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customers'] = $this->site->getCompanyByGroupID(5);
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['billers'] = $this->site->getAllCompanies("biller");
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['voucher'] = $voucher_original;
            $this->data['voucher_children'] = $voucher_children;
            $this->load->view($this->theme . 'vouchers/edit', $this->data);
        }
    }

    
	function checkin()
    {
        $this->sma->checkPermissions('add',null,'vouchers');

       // $this->form_validation->set_rules('voucher_number', 'Voucher Number', 'required');
       // $this->form_validation->set_rules('warehouse', 'Hotel', 'required');
        $this->form_validation->set_rules('chkincustomer', 'chkincustomer', 'required');
        //$this->form_validation->set_rules('biller', 'Biller', 'required');
        //$this->form_validation->set_rules('group_name', 'Group Name', 'required');
        $this->form_validation->set_rules('chkindate', 'Check In Date', 'required');
        $this->form_validation->set_rules('check_out', 'Check Out Date', 'required');
       // $this->form_validation->set_rules('no_adults', 'No of Adults', 'required');
		$this->form_validation->set_rules('billtoroom', 'Bill to Room', 'required');
        //$this->form_validation->set_rules('no_children', 'No of Children', 'required');
        //$this->form_validation->set_rules('residence', 'Residence', 'required');
        //$this->form_validation->set_rules('remarks', 'Remarks', 'required');
        //$this->form_validation->set_rules('meal_plan', 'Meal Plan', 'required');
		
       // $date1 = date_format(date_create($this->input->post('chkindate')),"Y-m-d");
       // $date2 = date_format(date_create($this->input->post('check_out'));
		$date1 = date_create($this->sma->fld(trim($this->input->post('chkindate'))));
		$date2 = date_create($this->sma->fld(trim($this->input->post('check_out'))));
        $diff = date_diff($date1,$date2);
        if($date1>$date2){
            $this->session->set_flashdata('error', 'Check in date cannot be after check out date');
            redirect('vouchers/checkin');
        }
		$vcode = $this->site->getVouchermaxcode();
		
        //if($this->vouchers_model->getVoucherByNumber($this->input->post('voucher_number'))){
         //  $this->session->set_flashdata('error', 'A voucher already exists with that number');
         //  redirect('vouchers/add');
       // }
	  //die($this->input->post('deluxe_room62'));
        if ($this->form_validation->run('vouchers/add') == true) {
           
            $data = array(
                'customer_id'=> $this->input->post('chkincustomer'),
                'voucher_no'=> $vcode->id,//$this->input->post('voucher_number'),
                'hotel_id'=> '4',//$this->input->post('warehouse')
                'biller_id'=> '259', //$this->input->post('biller')
                'status'=> 'Complete', //$this->input->post('status'
                'date_in'=> $this->sma->fld(trim($this->input->post('chkindate'))),
                'check_in'=> $this->sma->fld(trim($this->input->post('chkindate'))),
                'check_out'=>  $this->sma->fld(trim($this->input->post('check_out'))),
                'total_nights'=> $diff->format("%a")+1-1,
                'residence'=> 'Resident',//$this->input->post('residence'),
                'remarks'=> 'Remarks',//$this->input->post('remarks'),
                'no_children'=> $this->input->post('no_children'),
                'no_adults'=> $this->input->post('no_adults'),
                'executive_room'=> $this->input->post('executive_room60')?$this->input->post('executive_room60'):0,
				'executive_rate'=> $this->input->post('rate60')?$this->input->post('rate60'):0,
				'executive_roomnos'=> $this->input->post('chkinrms60')?$this->input->post('chkinrms60'):0,
				'executive_adults'=> $this->input->post('adults60')?$this->input->post('adults60'):0,
                'superior_room'=> $this->input->post('superior_room61')?$this->input->post('superior_room61'):0,
				'superior_rate'=> $this->input->post('rate61')?$this->input->post('rate61'):0,
				'superior_roomnos'=> $this->input->post('chkinrms61')?$this->input->post('chkinrms61'):0,
				'superior_adult'=> $this->input->post('adults61')?$this->input->post('adults61'):0,
                'deluxe_room'=> $this->input->post('deluxe_room62')?$this->input->post('deluxe_room62'):0,
				'deluxe_rate'=> $this->input->post('rate62')?$this->input->post('rate62'):0,
				'deluxe_roomnos'=> $this->input->post('chkinrms62')?$this->input->post('chkinrms62'):0,
				'deluxe_adults'=> $this->input->post('adults62')?$this->input->post('adults62'):0,
                'standard_room'=> $this->input->post('standard_room63')?$this->input->post('standard_room63'):0,
				'standard_rate'=> $this->input->post('rate63')?$this->input->post('rate63'):0,
				'standard_roomnos'=> $this->input->post('chkinrms63')?$this->input->post('chkinrms63'):0,
				'standard_adult'=> $this->input->post('adults63')?$this->input->post('adults63'):0,
                'singles_room'=> $this->input->post('singles_room64')?$this->input->post('singles_room64'):0,
				'singles_rate'=> $this->input->post('rate64')?$this->input->post('rate64'):0,
				'single_roomnos'=> $this->input->post('chkinrms64')?$this->input->post('chkinrms64'):0,
				'single_adult'=> $this->input->post('adults64')?$this->input->post('adults64'):0,
                'twin_room'=> $this->input->post('twin_room65')?$this->input->post('twin_room65'):0,
				'twin_rate'=> $this->input->post('rate65')?$this->input->post('rate65'):0,
				'twin_roomnos'=> $this->input->post('chkinrms65')?$this->input->post('chkinrms65'):0,
				'twin_adult'=> $this->input->post('adults65')?$this->input->post('adults65'):0,
                'meal_plan'=> $this->input->post('meal_plan'),
                'extra_bed'=> $this->input->post('extra_bed'),
                'contact_name'=> 'contact_name',//$this->input->post('contact_name'),
                'contact_phone'=> 'contact_phone',//$this->input->post('contact_phone'),
                'contact_email'=> 'contact_email',//$this->input->post('contact_email'),

            );

        } elseif ($this->input->post('add_voucher')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('vouchers/add');
        }

        if ($this->form_validation->run() == true && $rid = $this->vouchers_model->addVoucher($data)) {
            $actual_data = $this->input->post('data');
            if (count($actual_data)>0){
                for($i =0;$i<count($actual_data);$i++){
                    $voucher_children = array(
                        'voucher_id'=> $rid,
                        'age'=> $actual_data[$i],
                    );
                    $this->vouchers_model->addVoucherChildren($voucher_children);
                }
            }
		
		
        //if ($this->form_validation->run() == true) {
            $voucher_data = $this->vouchers_model->getVoucherByID($rid);
            $customer = $this->companies_model->getCompanyByID($voucher_data->customer_id);
            $biller = $this->companies_model->getBillerByID($voucher_data->biller_id);
			$total = $this->sma->formatDecimal($this->input->post('totalrpday')*$voucher_data->total_nights);
            $vat = $this->sma->formatDecimal(($total*18)/100);
            $data2 = array(
                'date' => date('Y-m-d H:i:s'),
                'reference_no' => time(),
                'voucher_id' => $voucher_data->id,
                'customer_id' => $voucher_data->customer_id,
                'customer' => $customer->name,
                'currency' => $this->input->post('currency'),
                'residence' => $voucher_data->residence,
                'biller_id' => $voucher_data->biller_id,
                'biller' => $biller->name,
				'room_id' => $this->input->post('billtoroom'),
                'no_of_rooms' => $this->input->post('totalrms'),
                'warehouse_id' => $voucher_data->hotel_id,
                'category_id' => 54,
                'chkindate' => $voucher_data->check_in,
                'chkoutdate' => $voucher_data->check_out,
                'note' => $voucher_data->remarks,
                'staff_note' =>  $voucher_data->remarks,
                'total' => $this->sma->formatDecimal($this->input->post('totalrpday')*$voucher_data->total_nights),
                'product_discount' => $this->sma->formatDecimal(0),
                'order_discount_id' => null,
                'order_discount' => 0,
                'total_discount' => 0,
                'product_tax' => $this->sma->formatDecimal(0),
                'order_tax_id' => null,
                'order_tax' => 0,
                'total_tax' => 0,
                'shipping' => $this->sma->formatDecimal(0),
                'grand_total' => $this->sma->formatDecimal($this->input->post('totalrpday')*$voucher_data->total_nights),
                'total_items' => 2,
                'sale_status' => "completed",
                'payment_status' => "pending",
                'payment_term' => 0,
                'due_date' => $this->input->post('chkindate'),
                'paid' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
        //}
				$sid = $this->sales_model->addSaleInvoice($data2);
       // if ($this->form_validation->run() == true && $sid = $this->sales_model->addSaleInvoice($data2)) {
            $ref_data = array(
                'reference_no' => 'E00'.$sid,
            );
            $this->sales_model->updateSaleOnly($sid, $ref_data);
			if($this->input->post('executive_room60') != NULL && $this->input->post('executive_room60') !='0'){
              
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'executive_room',
                    'quantity' => $this->input->post('executive_room60'),
                    'price' => $this->input->post('rate60'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate60'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('deluxe_room62')!= NULL  && $this->input->post('deluxe_room62') !='0'){
              
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'deluxe_room',
                    'quantity' => $this->input->post('deluxe_room62'),
                    'price' => $this->input->post('rate62'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate62'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('singles_room64') != NULL && $this->input->post('singles_room64') !='0'){
               
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'singles_room',
                    'quantity' => $this->input->post('singles_room64'),
                    'price' => $this->input->post('rate64'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate64'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            
            if($this->input->post('standard_room63') != NULL && $this->input->post('standard_room63') !='0'){
               $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'standard_room',
                    'quantity' => $this->input->post('standard_room63'),
                    'price' => $this->input->post('rate63'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate63'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            
            if( $this->input->post('superior_room61') != NULL && $this->input->post('superior_room61') !='0'){
              $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'superior_room',
                    'quantity' => $this->input->post('superior_room61'),
                    'price' => $this->input->post('rate61'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate61'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if( $this->input->post('twin_room65') != NULL && $this->input->post('twin_room65') != '0'){
               $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'twin_room',
                    'quantity' => $this->input->post('twin_room65'),
                    'price' => $this->input->post('rate65'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate65'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            
            
		if($this->input->post('chkinrms62') !='0' && $this->input->post('chkinrms62') != NULL ){ //deluxe
				$chkrm62 = $this->input->post('chkinrms62');
				$chkindate = $this->sma->fld(trim($this->input->post('chkindate')));
                $check_out= $this->sma->fld(trim($this->input->post('check_out')));
				$customer_id= $this->input->post('chkincustomer');
				
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm62,$chkindate,$check_out,$customer_id,$sid);
				}
		if($this->input->post('chkinrms60') !='0' && $this->input->post('chkinrms60') != NULL){ //deluxe
				$chkrm60 = $this->input->post('chkinrms60');
				$chkindate = $this->sma->fld(trim($this->input->post('chkindate')));
                $check_out= $this->sma->fld(trim($this->input->post('check_out')));
				$customer_id= $this->input->post('chkincustomer');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm60,$chkindate,$check_out,$customer_id,$sid);
				}
		if($this->input->post('chkinrms64') !='0' && $this->input->post('chkinrms64') != NULL){ //deluxe
				$chkrm64 = $this->input->post('chkinrms64');
				$chkindate = $this->sma->fld(trim($this->input->post('chkindate')));
                $check_out= $this->sma->fld(trim($this->input->post('check_out')));
				$customer_id= $this->input->post('chkincustomer');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm64,$chkindate,$check_out,$customer_id,$sid);
				}
		if($this->input->post('chkinrms63') !='0' && $this->input->post('chkinrms63') != NULL){ //deluxe
				$chkrm63 = $this->input->post('chkinrms63');
				$chkindate = $this->sma->fld(trim($this->input->post('chkindate')));
                $check_out= $this->sma->fld(trim($this->input->post('check_out')));
				$customer_id= $this->input->post('chkincustomer');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm63,$chkindate,$check_out,$customer_id,$sid);
				}		
		if($this->input->post('chkinrms61') !='0' && $this->input->post('chkinrms61') != NULL){ //deluxe
				$chkrm61 = $this->input->post('chkinrms61');
				$chkindate = $this->sma->fld(trim($this->input->post('chkindate')));
                $check_out= $this->sma->fld(trim($this->input->post('check_out')));
				$customer_id= $this->input->post('chkincustomer');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm61,$chkindate,$check_out,$customer_id,$sid);
				}	
		if($this->input->post('chkinrms65') !='0' && $this->input->post('chkinrms65') != NULL){ //deluxe
				$chkrm65 = $this->input->post('chkinrms65');
				$chkindate = $this->sma->fld(trim($this->input->post('chkindate')));
                $check_out= $this->sma->fld(trim($this->input->post('check_out')));
				$customer_id= $this->input->post('chkincustomer');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm65,$chkindate,$check_out,$customer_id,$sid);
				}
		
            $this->session->set_flashdata('message', $this->lang->line("checkin_added"));
            redirect('sales/view/'.$sid);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customers'] = $this->site->getCompanyByGroupID(1);
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['billers'] = $this->site->getAllCompanies("biller");
            $this->data['categories'] = $this->site->getAllInvCategories();
			$this->data['currencies'] = $this->site->getAllCurrencies();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('vouchers')));
            $meta = array('page_title' => lang('vouchers'), 'bc' => $bc);
            $this->page_construct('vouchers/checkin', $meta, $this->data);
        }
    }
	
	function checkincompany()
    {
        $this->sma->checkPermissions('add',null,'vouchers');

       // $this->form_validation->set_rules('voucher_number', 'Voucher Number', 'required');
       // $this->form_validation->set_rules('warehouse', 'Hotel', 'required');
        $this->form_validation->set_rules('chkincustomer', 'chkincustomer', 'required');
        $this->form_validation->set_rules('contact_name', 'Contact Name', 'required');
        //$this->form_validation->set_rules('group_name', 'Group Name', 'required');
        $this->form_validation->set_rules('chkindate', 'Check In Date', 'required');
        $this->form_validation->set_rules('check_out', 'Check Out Date', 'required');
        //$this->form_validation->set_rules('no_adults', 'No of Adults', 'required');
		$this->form_validation->set_rules('billtoroom', 'Bill to Room', 'required');
        //$this->form_validation->set_rules('no_children', 'No of Children', 'required');
        //$this->form_validation->set_rules('residence', 'Residence', 'required');
        //$this->form_validation->set_rules('remarks', 'Remarks', 'required');
        //$this->form_validation->set_rules('meal_plan', 'Meal Plan', 'required');
        $date1 = date_create($this->input->post('chkindate'));
        $date2 = date_create($this->input->post('check_out'));
        $diff = date_diff($date1,$date2);
        if($date1>$date2){
            $this->session->set_flashdata('error', 'Check in date cannot be after check out date');
            redirect('vouchers/checkin');
        }
		$vcode = $this->site->getVouchermaxcode();
		
        //if($this->vouchers_model->getVoucherByNumber($this->input->post('voucher_number'))){
         //  $this->session->set_flashdata('error', 'A voucher already exists with that number');
         //  redirect('vouchers/add');
       // }
	  //die($this->input->post('deluxe_room62'));
        if ($this->form_validation->run('vouchers/add') == true) {
           
            $data = array(
                'customer_id'=> $this->input->post('chkincustomer'),
                'voucher_no'=> $vcode->id,//$this->input->post('voucher_number'),
                'hotel_id'=> '4',//$this->input->post('warehouse')
                'biller_id'=> '259', //$this->input->post('biller')
                'status'=> 'Complete', //$this->input->post('status'
                'date_in'=> $this->input->post('chkindate'),
                'check_in'=> $this->input->post('chkindate'),
                'check_out'=> $this->input->post('check_out'),
                'total_nights'=> $diff->format("%a")+1-1,
                'residence'=> 'Resident',//$this->input->post('residence'),
                'remarks'=> 'Remarks',//$this->input->post('remarks'),
                'no_children'=> $this->input->post('no_children'),
                'no_adults'=> $this->input->post('no_adults'),
                'executive_room'=> $this->input->post('executive_room60')?$this->input->post('executive_room60'):0,
				'executive_rate'=> $this->input->post('rate60')?$this->input->post('rate60'):0,
				'executive_roomnos'=> $this->input->post('chkinrms60')?$this->input->post('chkinrms60'):0,
				'executive_adults'=> $this->input->post('adults60')?$this->input->post('adults60'):0,
                'superior_room'=> $this->input->post('superior_room61')?$this->input->post('superior_room61'):0,
				'superior_rate'=> $this->input->post('rate61')?$this->input->post('rate61'):0,
				'superior_roomnos'=> $this->input->post('chkinrms61')?$this->input->post('chkinrms61'):0,
				'superior_adult'=> $this->input->post('adults61')?$this->input->post('adults61'):0,
                'deluxe_room'=> $this->input->post('deluxe_room62')?$this->input->post('deluxe_room62'):0,
				'deluxe_rate'=> $this->input->post('rate62')?$this->input->post('rate62'):0,
				'deluxe_roomnos'=> $this->input->post('chkinrms62')?$this->input->post('chkinrms62'):0,
				'deluxe_adults'=> $this->input->post('adults62')?$this->input->post('adults62'):0,
                'standard_room'=> $this->input->post('standard_room63')?$this->input->post('standard_room63'):0,
				'standard_rate'=> $this->input->post('rate63')?$this->input->post('rate63'):0,
				'standard_roomnos'=> $this->input->post('chkinrms63')?$this->input->post('chkinrms63'):0,
				'standard_adult'=> $this->input->post('adults63')?$this->input->post('adults63'):0,
                'singles_room'=> $this->input->post('singles_room64')?$this->input->post('singles_room64'):0,
				'singles_rate'=> $this->input->post('rate64')?$this->input->post('rate64'):0,
				'single_roomnos'=> $this->input->post('chkinrms64')?$this->input->post('chkinrms64'):0,
				'single_adult'=> $this->input->post('adults64')?$this->input->post('adults64'):0,
                'twin_room'=> $this->input->post('twin_room65')?$this->input->post('twin_room65'):0,
				'twin_rate'=> $this->input->post('rate65')?$this->input->post('rate65'):0,
				'twin_roomnos'=> $this->input->post('chkinrms65')?$this->input->post('chkinrms65'):0,
				'twin_adult'=> $this->input->post('adults65')?$this->input->post('adults65'):0,
                'meal_plan'=> $this->input->post('meal_plan'),
                'extra_bed'=> $this->input->post('extra_bed'),
                'contact_name'=> $this->input->post('contact_name'),//$this->input->post('contact_name'),
                'contact_phone'=> $this->input->post('contact_phone'),//$this->input->post('contact_phone'),
                'contact_email'=> $this->input->post('contact_email'),//$this->input->post('contact_email'),

            );

        } elseif ($this->input->post('add_voucher')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('vouchers/add');
        }

        if ($this->form_validation->run() == true && $rid = $this->vouchers_model->addVoucher($data)) {
            $actual_data = $this->input->post('data');
            if (count($actual_data)>0){
                for($i =0;$i<count($actual_data);$i++){
                    $voucher_children = array(
                        'voucher_id'=> $rid,
                        'age'=> $actual_data[$i],
                    );
                    $this->vouchers_model->addVoucherChildren($voucher_children);
                }
            }
		
		
        //if ($this->form_validation->run() == true) {
            $voucher_data = $this->vouchers_model->getVoucherByID($rid);
            $customer = $this->companies_model->getCompanyByID($voucher_data->customer_id);
            $biller = $this->companies_model->getBillerByID($voucher_data->biller_id);
			$total = $this->sma->formatDecimal($this->input->post('totalrpday')*$voucher_data->total_nights);
            $vat = $this->sma->formatDecimal(($total*18)/100);
            $data2 = array(
                'date' => date('Y-m-d H:i:s'),
                'reference_no' => time(),
                'voucher_id' => $voucher_data->id,
                'customer_id' => $voucher_data->customer_id,
                'customer' => $customer->name,
                'currency' => $this->input->post('currency'),
                'residence' => $voucher_data->residence,
                'biller_id' => $voucher_data->biller_id,
                'biller' => $biller->name,
				'room_id' => $this->input->post('billtoroom'),
                'no_of_rooms' => $this->input->post('totalrms'),
                'warehouse_id' => $voucher_data->hotel_id,
                'category_id' => 54,
                'chkindate' => $voucher_data->check_in,
                'chkoutdate' => $voucher_data->check_out,
                'note' => $voucher_data->remarks,
                'staff_note' =>  $voucher_data->remarks,
                'total' => $this->sma->formatDecimal($this->input->post('totalrpday')*$voucher_data->total_nights),
                'product_discount' => $this->sma->formatDecimal(0),
                'order_discount_id' => null,
                'order_discount' => 0,
                'total_discount' => 0,
                'product_tax' => $this->sma->formatDecimal(0),
                'order_tax_id' => null,
                'order_tax' => 0,
                'total_tax' => 0,
                'shipping' => $this->sma->formatDecimal(0),
                'grand_total' => $this->sma->formatDecimal($this->input->post('totalrpday')*$voucher_data->total_nights),
                'total_items' => 2,
                'sale_status' => "completed",
                'payment_status' => "pending",
                'payment_term' => 0,
                'due_date' => $this->input->post('chkindate'),
                'paid' => 0,
                'created_by' => $this->session->userdata('user_id')
            );
        //}
				$sid = $this->sales_model->addSaleInvoice($data2);
       // if ($this->form_validation->run() == true && $sid = $this->sales_model->addSaleInvoice($data2)) {
            $ref_data = array(
                'reference_no' => 'E00'.$sid,
            );
            $this->sales_model->updateSaleOnly($sid, $ref_data);
			if($this->input->post('executive_room60') !='0'){
              
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'executive_room',
                    'quantity' => $this->input->post('executive_room60'),
                    'price' => $this->input->post('rate60'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate60'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('deluxe_room62') !='0'){
              
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'deluxe_room',
                    'quantity' => $this->input->post('deluxe_room62'),
                    'price' => $this->input->post('rate62'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate62'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('singles_room64') !='0'){
               
                $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'singles_room',
                    'quantity' => $this->input->post('singles_room64'),
                    'price' => $this->input->post('rate64'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate64'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            
            if($this->input->post('standard_room63') !='0'){
               $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'standard_room',
                    'quantity' => $this->input->post('standard_room63'),
                    'price' => $this->input->post('rate63'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate63'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            
            if($this->input->post('superior_room61') !='0'){
              $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'superior_room',
                    'quantity' => $this->input->post('superior_room61'),
                    'price' => $this->input->post('rate61'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate61'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            if($this->input->post('twin_room65') != '0'){
               $invoice_item = array(
                    'sale_id' => $sid,
                    'name' => 'twin_room',
                    'quantity' => $this->input->post('twin_room65'),
                    'price' => $this->input->post('rate65'),
                    'no_adults' => '',
                    'no_children' => '',
                    'pax' => '',
                    'total' => $this->input->post('rate65'),

                );
                $this->sales_model->addSaleInvoiceItem($invoice_item);
            }
            
            
		if($this->input->post('chkinrms62') !='0'){ //deluxe
				$chkrm62 = $this->input->post('chkinrms62');
				$chkindate = $this->input->post('chkindate');
                $check_out= $this->input->post('check_out');
				$customer_id= $this->input->post('chkincustomer');
				
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm62,$chkindate,$check_out,$customer_id,$sid);
				}
		if($this->input->post('chkinrms60') !='0'){ //deluxe
				$chkrm60 = $this->input->post('chkinrms60');
				$chkindate = $this->input->post('chkindate');
                $check_out= $this->input->post('check_out');
				$customer_id= $this->input->post('chkincustomer');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm60,$chkindate,$check_out,$customer_id,$sid);
				}
		if($this->input->post('chkinrms64') !='0'){ //deluxe
				$chkrm64 = $this->input->post('chkinrms64');
				$chkindate = $this->input->post('chkindate');
                $check_out= $this->input->post('check_out');
				$customer_id= $this->input->post('chkincustomer');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm64,$chkindate,$check_out,$customer_id,$sid);
				}
		if($this->input->post('chkinrms63') !='0'){ //deluxe
				$chkrm63 = $this->input->post('chkinrms63');
				$chkindate = $this->input->post('chkindate');
                $check_out= $this->input->post('check_out');
				$customer_id= $this->input->post('chkincustomer');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm63,$chkindate,$check_out,$customer_id,$sid);
				}		
		if($this->input->post('chkinrms61') !='0'){ //deluxe
				$chkrm61 = $this->input->post('chkinrms61');
				$chkindate = $this->input->post('chkindate');
                $check_out= $this->input->post('check_out');
				$customer_id= $this->input->post('chkincustomer');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm61,$chkindate,$check_out,$customer_id,$sid);
				}	
		if($this->input->post('chkinrms65') !='0'){ //deluxe
				$chkrm65 = $this->input->post('chkinrms65');
				$chkindate = $this->input->post('chkindate');
                $check_out= $this->input->post('check_out');
				$customer_id= $this->input->post('chkincustomer');
				//print_r($chkrm62);
				//die();
					$rid2 = $this->vouchers_model->addRoomStats($chkrm65,$chkindate,$check_out,$customer_id,$sid);
				}
		
            $this->session->set_flashdata('message', $this->lang->line("checkin_added"));
            redirect('sales/view/'.$sid);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customers'] = $this->site->getCompanyByGroupID(5);
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['billers'] = $this->site->getAllCompanies("biller");
            $this->data['categories'] = $this->site->getAllInvCategories();
			$this->data['currencies'] = $this->site->getAllCurrencies();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('vouchers')));
            $meta = array('page_title' => lang('vouchers'), 'bc' => $bc);
            $this->page_construct('vouchers/checkincompany', $meta, $this->data);
        }
    }
	
	
	function listCheckin($action = NULL)
    {
        $this->sma->checkPermissions('index',null,'service-vouchers');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('svouchers')));
        $meta = array('page_title' => lang('svouchers'), 'bc' => $bc);
        $this->page_construct('vouchers/list_checkin', $meta, $this->data);
    }
	
	function getServiceVouchers($action = NULL)
    {
                $this->sma->checkPermissions('index',null,'service-vouchers');
        $check_in_link = anchor('service_vouchers/checkin/$1', '<i class="fa fa-sign-in"></i> ' . lang('check_in_guest'), 'data-toggle="modal" data-target="#myModal"');
        $detail_link = anchor('service_vouchers/show/$1', '<i class="fa fa-file-text-o"></i> ' . lang('Service Voucher Details'));
        $email_link = anchor('service_vouchers/email/$1', '<i class="fa fa-envelope"></i> ' . lang('Email Service Voucher'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('service_vouchers/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_svoucher'), 'class="sledit"');
        $pdf_link = anchor('service_vouchers/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("sdelete_voucher") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('service_vouchers/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('sdelete_voucher') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $check_in_link . '</li>
            <li>' . $detail_link . '</li>
            <li>' . $edit_link . '</li>
            <li>' . $pdf_link . '</li>
            <li>' . $email_link . '</li>
            <li>' . $delete_link . '</li>
        </ul>
    </div></div>';
        $this->load->library('datatables');
        $this->datatables
            ->select("sma_vouchers.id as id,sma_companies.name,sma_vouchers.voucher_no,sma_vouchers.status,sma_vouchers.group_name,sma_vouchers.check_in,sma_vouchers.check_out,sma_vouchers.total_nights")
            ->from("sma_vouchers")
            ->join('sma_companies', 'sma_vouchers.customer_id = sma_companies.id','left')
            ->join('sma_service_vouchers', 'sma_vouchers.id = sma_service_vouchers.voucher_id')
            ->group_by("voucher_no");

        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
	}
	function delete($id = NULL)
    {
        $this->sma->checkPermissions('delete',true,'vouchers');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->vouchers_model->deleteVoucher($id)) {
            echo $this->lang->line("voucher_deleted");
        } else {
            $this->session->set_flashdata('warning', lang('voucher_not_deleted'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }
    }

    function deleteChild($id = NULL)
    {
         $this->sma->checkPermissions('delete',true,'vouchers');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->vouchers_model->deleteChild($id)) {
            echo $this->lang->line("voucher_deleted");
        } else {
            $this->session->set_flashdata('warning', lang('voucher_not_deleted'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }
    }

    function show($id = NULL)
    {
        $this->sma->checkPermissions('index',null,'vouchers');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $voucher = $this->vouchers_model->getVoucherByID($id);
        $voucher_children = $this->vouchers_model->getVoucherChildrenByVoucherID($id);

        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['voucher'] = $voucher;
        $this->data['voucher_children'] = $voucher_children;
        $this->data['customers'] = $this->vouchers_model->getCompanyByGroupID(5);
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['billers'] = $this->site->getAllCompanies("biller");
        $this->data['categories'] = $this->site->getAllCategories();
        $this->load->view($this->theme . 'vouchers/view', $this->data);

    }


    function getInvoiceItems(){
        $this->sma->checkPermissions('index',true,'vouchers');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $voucher = $this->vouchers_model->getVoucherByID($id);
        $inv = $this->vouchers_model->getInvByVoucherID($id);
        $inv_items = $this->vouchers_model->getInvItemsByInvID($inv->id);

        echo json_encode($inv_items);
    }

}
