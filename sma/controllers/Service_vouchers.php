<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Service_vouchers extends MY_Controller
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
        $this->lang->load('sales', $this->Settings->language);
        $this->load->helper('string');
        $this->load->library('form_validation');
        $this->load->model('sales_model');
        $this->load->model('vouchers_model');
        $this->load->model('guests_model');
        $this->load->model('companies_model');
    }

    function index($action = NULL)
    {
        $this->sma->checkPermissions('index',null,'service-vouchers');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('svouchers')));
        $meta = array('page_title' => lang('svouchers'), 'bc' => $bc);
        $this->page_construct('service_vouchers/index', $meta, $this->data);
    }

    function getServiceVouchers()
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

    function checkin($voucher_id = NULL)
    {
        $this->sma->checkPermissions('check_in',null,'service-vouchers');

        if ($this->input->get('id')) {
            $voucher_id = $this->input->get('id');
        }

        $post = array();
        foreach ( $_POST as $key => $value )
        {
            $post[$key] = $this->input->post($key);
        }
        //var_dump(json_encode($post));
        $actual_data = $this->input->post('data');
        //var_dump($actual_data[1]['kin_full_name']);

        $service_voucher = $this->vouchers_model->getServiceVoucherByVoucherID($voucher_id);

        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[guests.email]');

        if ($this->form_validation->run('service_vouchers/add') == true) {

            $personal_data = array(
                'voucher_id' => $voucher_id,
                'service_voucher_id' => $service_voucher->id,
                'full_name' => $actual_data[0]['full_name'],
                'nationality' => $actual_data[0]['nationality'],
                'passport_id_number' => $actual_data[0]['passport_id_number'],
                'gender' => $actual_data[0]['gender'],
                'address' => $actual_data[0]['address'],
                'telephone' => $actual_data[0]['telephone'],
                'phone' => $actual_data[0]['phone'],
                'alt_phone' => $actual_data[0]['alt_phone'],
                'email' => $actual_data[0]['email'],
                'alt_email' => $actual_data[0]['alt_email'],
                'dob' => $actual_data[0]['dob'],
                'passport_expiry' => $actual_data[0]['passport_expiry']?$actual_data[0]['passport_expiry']:NULL,
                'room' => $actual_data[0]['room'],
                'travel_history' => $actual_data[0]['travel_history'],
                'token' => random_string('alnum', 8),
                'token_used' => false,
            );



        } elseif ($this->input->post('check_in_guest')) {
            $this->session->set_flashdata('error', validation_errors());
            //replace this with the guest index route
            redirect('service_vouchers/index');
        }

        if ($this->form_validation->run() == true && $gid = $this->guests_model->addGuest($personal_data)) {
            $next_of_kin_data = array(
                'guest_id' => $gid,
                'full_name' => $actual_data[1]['kin_full_name'],
                'phone' => $actual_data[1]['kin_phone'],
            );
            $temp_data = array(
                'guest_id' => $gid,
                'temp' => $actual_data[4][0]['temperature'],
            );
            if ($this->guests_model->addNextOfKin($next_of_kin_data) && $this->guests_model->addTemperature($temp_data)){
                if(count($this->input->post('data[2]'))>0){// if dietary requirements are present

                    $requirements = $this->input->post('data[2]');

                    foreach ($requirements as $i => &$element) {
                        $element['guest_id'] = $gid;
                    }

                    if($this->guests_model->addRequirements($requirements)){
                        if(count($this->input->post('data[3]'))>0){//if medical conditions are present

                            $medical_conditions = $this->input->post('data[3]');

                            foreach ($medical_conditions as $i => &$element) {
                                $element['guest_id'] = $gid;
                            }

                            if ($this->guests_model->addMedicalConditions($medical_conditions)){
                                $this->session->set_flashdata('message', $this->lang->line("guest_added"));
                                $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
                                redirect('guests');
                            }else{
                                $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
                                $this->data['modal_js'] = $this->site->modal_js();
                                $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
                                $this->data['service_voucher'] = $service_voucher;
                                $this->load->view($this->theme . 'service_vouchers/checkin', $this->data);
                            }

                        }else{
                            $this->session->set_flashdata('message', $this->lang->line("Guest added successfully"));
                            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
                            redirect('guests');
                        }
                    }else{
                        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
                        $this->data['modal_js'] = $this->site->modal_js();
                        $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
                        $this->data['service_voucher'] = $service_voucher;
                        $this->load->view($this->theme . 'service_vouchers/checkin', $this->data);
                    }

                }else{
                    if(count($this->input->post('data[3]'))>0){//if medical conditions are present

                        $medical_conditions = $this->input->post('data[3]');

                        foreach ($medical_conditions as $i => &$element) {
                            $element['guest_id'] = $gid;
                        }

                        if ($this->guests_model->addMedicalConditions($medical_conditions)){//if medical conditions have been saved
                            $this->session->set_flashdata('message', $this->lang->line("guest_added"));
                            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
                            redirect('guests');
                        }else{
                            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
                            $this->data['modal_js'] = $this->site->modal_js();
                            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
                            $this->data['service_voucher'] = $service_voucher;
                            $this->load->view($this->theme . 'service_vouchers/checkin', $this->data);
                        }

                    }else{
                        $this->session->set_flashdata('message', $this->lang->line("guest_added"));
                        $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
                        redirect('guests');
                    }
                }


            }else{
                $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
                $this->data['modal_js'] = $this->site->modal_js();
                $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
                $this->data['service_voucher'] = $service_voucher;
                $this->load->view($this->theme . 'service_vouchers/checkin', $this->data);
            }

        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->data['service_voucher'] = $service_voucher;
            $this->load->view($this->theme . 'service_vouchers/checkin', $this->data);
        }
    }

    function add()
    {
        $this->sma->checkPermissions('add',null,'service-vouchers');


        $this->form_validation->set_rules('arrival_time', 'Arrival Time', 'required');
        $this->form_validation->set_rules('voucher', 'Voucher', 'required');

        if ($this->form_validation->run('service_voucher/add') == true) {

            $data = array(
                'voucher_id'=> $this->input->post('voucher'),
                'arrival_time'=> $this->input->post('arrival_time'),
                'special_instructions'=> $this->input->post('special_instructions'),
                'booked_by'=> $this->session->userdata('user_id'),
            );

        } elseif ($this->input->post('add_service_voucher')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('service_vouchers/index');
        }

        if ($this->form_validation->run() == true && $rid = $this->vouchers_model->addServiceVoucher($data)) {

            $this->session->set_flashdata('message', $this->lang->line("svoucher_added"));
            redirect('service_vouchers/index');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['vouchers'] = $this->vouchers_model->getAllVouchers();
            $this->data['customers'] = $this->vouchers_model->getCompanyByGroupID(5);
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['billers'] = $this->site->getAllCompanies("biller");
            $this->data['categories'] = $this->site->getAllCategories();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('svouchers')));
            $meta = array('page_title' => lang('svouchers'), 'bc' => $bc);
            $this->page_construct('service_vouchers/add', $meta, $this->data);
        }
    }

    function edit($id = NULL)
    {
        $this->sma->checkPermissions('edit',null,'service-vouchers');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('arrival_time', 'Arrival Time', 'required');
        $this->form_validation->set_rules('voucher', 'Voucher', 'required');

        $voucher = $this->vouchers_model->getVoucherByID($id);
        $svoucher = $this->vouchers_model->getServiceVoucherByVoucherID($id);
        $inv = $this->vouchers_model->getInvByVoucherID($id);

        if ($this->form_validation->run() == true) {
            $data = array(
                'voucher_id'=> $this->input->post('voucher'),
                'arrival_time'=> $this->input->post('arrival_time'),
                'special_instructions'=> $this->input->post('special_instructions'),
            );
        }

        if ($this->form_validation->run() == true && $this->vouchers_model->updateServiceVoucher($svoucher->id, $data)) {

            $this->session->set_flashdata('message', lang("sale_edited"));
            redirect("service_vouchers/index");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['vouchers'] = $this->vouchers_model->getAllVouchers();
            $this->data['svoucher'] = $svoucher;
            $this->data['customers'] = $this->vouchers_model->getCompanyByGroupID(5);
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['billers'] = $this->site->getAllCompanies("biller");
            $this->data['categories'] = $this->site->getAllCategories();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('svouchers')));
            $meta = array('page_title' => lang('svouchers'), 'bc' => $bc);
            $this->page_construct('service_vouchers/edit', $meta, $this->data);
        }
    }

    function delete($id = NULL)
    {
        $this->sma->checkPermissions('delete',true,'service-vouchers');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->vouchers_model->deleteServiceVoucherByVoucherId($id)) {
            echo $this->lang->line("svoucher_deleted");
        } else {
            $this->session->set_flashdata('warning', lang('svoucher_not_deleted'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }
    }

    function show($id = NULL)
    {
        $this->sma->checkPermissions('index',null,'service-vouchers');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $voucher = $this->vouchers_model->getVoucherByID($id);
        $svoucher = $this->vouchers_model->getServiceVoucherByVoucherID($id);
        $inv = $this->vouchers_model->getInvByVoucherID($id);
        $inv_items = $this->vouchers_model->getInvItemsByInvID($inv->id);

        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['voucher'] = $voucher;
        $this->data['svoucher'] = $svoucher;
        $this->data['inv'] = $inv;
        $this->data['invoice_items'] = $inv_items;
        $this->data['qcategory'] = $this->site->getCategoryByID($inv->category_id);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['pax'] = $this->sales_model->getPaxfrominvoice($inv->id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($inv->id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($svoucher->booked_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['biller'] = $this->companies_model->getBillerByID($voucher->biller_id);
        $return = $this->sales_model->getReturnBySID($inv->id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($inv->id);
        //$this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        $this->data['paypal'] = $this->sales_model->getPaypalSettings();
        $this->data['skrill'] = $this->sales_model->getSkrillSettings();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('svouchers')));
        $meta = array('page_title' => lang('vouchers'), 'bc' => $bc);
        $this->page_construct('service_vouchers/view', $meta, $this->data);

    }

    function pdf($id = NULL, $view = NULL, $save_bufffer = NULL)
    {
        $this->sma->checkPermissions('pdf',null,'service-vouchers');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $voucher = $this->vouchers_model->getVoucherByID($id);
        $svoucher = $this->vouchers_model->getServiceVoucherByVoucherID($id);
        $inv = $this->vouchers_model->getInvByVoucherID($id);
        $inv_items = $this->vouchers_model->getInvItemsByInvID($inv->id);

        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['voucher'] = $voucher;
        $this->data['svoucher'] = $svoucher;
        $this->data['inv'] = $inv;
        $this->data['invoice_items'] = $inv_items;
        $this->data['qcategory'] = $this->site->getCategoryByID($inv->category_id);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['pax'] = $this->sales_model->getPaxfrominvoice($inv->id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($inv->id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($svoucher->booked_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['biller'] = $this->companies_model->getBillerByID($voucher->biller_id);
        $return = $this->sales_model->getReturnBySID($inv->id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($inv->id);

        $name = "Service_Voucher" . "_" . str_replace('/', '_', $inv->reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'service_vouchers/pdf', $this->data, TRUE);
        if ($view) {
            $this->load->view($this->theme . 'service_vouchers/pdf', $this->data);
        } elseif ($save_bufffer) {
            return $this->sma->generate_pdf($html, $name, $save_bufffer, "Confirmed By ". $this->site->getUser($svoucher->booked_by)->first_name . ' ' . $this->site->getUser($svoucher->booked_by)->last_name .' '. $this->data['biller']->invoice_footer);
        } else {
            $this->sma->generate_pdf($html, $name, FALSE, "Confirmed By ". $this->site->getUser($svoucher->booked_by)->first_name . ' ' . $this->site->getUser($svoucher->booked_by)->last_name .' '. $this->data['biller']->invoice_footer);
        }
    }

    function email($id = NULL)
    {
        $this->sma->checkPermissions('email',true,'service-vouchers');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $inv = $this->vouchers_model->getInvByVoucherID($id);

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
            redirect("service_vouchers");
        } else {

            if (file_exists('./themes/' . $this->theme . '/views/email_templates/service_voucher.html')) {
                $sale_temp = file_get_contents('themes/' . $this->theme . '/views/email_templates/service_voucher.html');
            } else {
                $sale_temp = file_get_contents('./themes/default/views/email_templates/service_voucher.html');
            }

            $this->data['subject'] = array('name' => 'subject',
                'id' => 'subject',
                'type' => 'text',
                'value' => $this->form_validation->set_value('subject', 'Service Voucher for '.lang('invoice').' (' . $inv->reference_no . ') '.lang('from').' ' . $this->Settings->site_name),
            );
            $this->data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'text',
                'value' => $this->form_validation->set_value('note', $sale_temp),
            );
            $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);

            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'service_vouchers/email', $this->data);
        }
    }

    function getInvoiceItems(){
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $voucher = $this->vouchers_model->getVoucherByID($id);
        $inv = $this->vouchers_model->getInvByVoucherID($id);
        $inv_items = $this->vouchers_model->getInvItemsByInvID($inv->id);

        echo json_encode($inv_items);
    }

}
