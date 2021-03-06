<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

       /* if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }*/
        if ($this->Customer || $this->Supplier) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->lang->load('customers', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('companies_model');
        $this->load->model('guests_model');
    }

    function index($action = NULL)
    {
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('customers')));
        $meta = array('page_title' => lang('customers'), 'bc' => $bc);
        $this->page_construct('customers/index', $meta, $this->data);
    }

    function getCustomers()
    {
        $this->sma->checkPermissions('index');
        $this->load->library('datatables');
        $this->datatables
            ->select("id, company, name, email, phone, city, customer_group_name, vat_no, award_points")
            ->from("companies")
            ->where('group_name', 'customer')
            ->add_column("Actions", "<center>
                <a class=\"tip\" title='" . $this->lang->line("edit_customer") . "' href='" . site_url('customers/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> 
                <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_customer") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('customers/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
        //->unset_column('id');
        echo $this->datatables->generate();
    }
	 function status()
    {
		
       // $this->sma->checkPermissions(false, true);
		if ($this->input->get('room_type') && $this->input->get('sdate')) {
            $rmtype = $this->input->get('room_type');
			$sdate = $this->input->get('sdate');
			if($rmtype=='all'){
			$result=$this->db->query("SELECT sma_rooms.name,T1.st AS status,sma_rooms.id ,sma_categories.code FROM (SELECT sma_rooms.name,sma_roomstats.check_in,sma_roomstats.check_out,sma_roomstats.`status` ,IF( '$sdate' >= sma_roomstats.check_in AND '$sdate' < sma_roomstats.check_out ,sma_roomstats.status,'Vacant') AS st,sma_rooms.id  
									FROM sma_rooms  LEFT JOIN sma_roomstats ON sma_roomstats.room_id = sma_rooms.id WHERE  ('$sdate' >= sma_roomstats.check_in AND  '$sdate' < sma_roomstats.check_out)  GROUP BY sma_rooms.id ) AS T1 RIGHT JOIN sma_rooms ON sma_rooms.id = T1.id LEFT JOIN sma_categories ON sma_categories.id = sma_rooms.category_type")->result();
			}else {
			$result=$this->db->query("SELECT sma_rooms.name,T1.st AS status,sma_rooms.id ,sma_categories.code FROM (SELECT sma_rooms.name,sma_roomstats.check_in,sma_roomstats.check_out,sma_roomstats.`status` ,IF( '$sdate' >= sma_roomstats.check_in AND '$sdate' < sma_roomstats.check_out ,sma_roomstats.status,'Vacant') AS st,sma_rooms.id  
									FROM sma_rooms  LEFT JOIN sma_roomstats ON sma_roomstats.room_id = sma_rooms.id WHERE  ('$sdate' >= sma_roomstats.check_in AND  '$sdate' < sma_roomstats.check_out)  GROUP BY sma_rooms.id ) AS T1 RIGHT JOIN sma_rooms ON sma_rooms.id = T1.id LEFT JOIN sma_categories ON sma_categories.id = sma_rooms.category_type WHERE sma_categories.code = '$rmtype'")->result();
			}
			$this->data['roomsbydate'] = $result;
			$this->data['datemm'] = $this->input->get('sdate');
        }
		
		if ($this->input->get('sdate') && !$this->input->get('room_type') ) {
            $sdate = $this->input->get('sdate');
			$result=$this->db->query("SELECT sma_rooms.name,T1.st AS status,sma_rooms.id  FROM (SELECT sma_rooms.name,sma_roomstats.check_in,sma_roomstats.check_out,sma_roomstats.`status` ,IF( '$sdate' >= sma_roomstats.check_in AND '$sdate' < sma_roomstats.check_out ,sma_roomstats.status,'Vacant') AS st,sma_rooms.id  FROM sma_rooms  LEFT JOIN sma_roomstats ON sma_roomstats.room_id = sma_rooms.id WHERE  ('$sdate' >= sma_roomstats.check_in AND  '$sdate' < sma_roomstats.check_out)  GROUP BY sma_rooms.id ) AS T1 RIGHT JOIN sma_rooms ON sma_rooms.id = T1.id")->result();
			$this->data['roomsbydate'] = $result;
			$this->data['datemm'] = $this->input->get('sdate');
        }
		
		
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
			$this->data['rooms'] = $this->site->getAllRooms($rmtype);
			
			//$result=$this->db->query("select name from sma_companies where customer_group_name like '%corporate%' ")->result();
			$this->data['categories'] = $this->site->getCategoryBycode($rmtype);
			
			if ($this->input->get('room_type')) {
				if($rmtype=='all'){
					$this->load->view($this->theme . 'rooms/bill_to_room', $this->data);
				}else{
            $this->load->view($this->theme . 'rooms/checkinrm_modal', $this->data);
				}
					}else{
			$this->load->view($this->theme . 'rooms/status', $this->data);		
					}
					
            
       
    }
	 function rooming()
    {
		
       // $this->sma->checkPermissions(false, true);
		if ($this->input->get('room_type') && $this->input->get('sdate')) {
            $rmtype = $this->input->get('room_type');
			$sdate = $this->input->get('sdate');
			$result=$this->db->query("SELECT sma_rooms.name,T1.st AS status,sma_rooms.name as id ,sma_categories.code FROM (SELECT sma_rooms.name,sma_roomstats.check_in,sma_roomstats.check_out,sma_roomstats.`status` ,IF( '$sdate' >= sma_roomstats.check_in AND '$sdate' < sma_roomstats.check_out ,sma_roomstats.status,'Vacant') AS st,sma_rooms.id  
									FROM sma_rooms  LEFT JOIN sma_roomstats ON sma_roomstats.room_id = sma_rooms.id WHERE  ('$sdate' >= sma_roomstats.check_in AND  '$sdate' < sma_roomstats.check_out)  GROUP BY sma_rooms.id ) AS T1 RIGHT JOIN sma_rooms ON sma_rooms.id = T1.id LEFT JOIN sma_categories ON sma_categories.id = sma_rooms.category_type WHERE sma_categories.code = '$rmtype'")->result();
			$this->data['roomsbydate'] = $result;
			$this->data['datemm'] = $this->input->get('sdate');
        }
		if ($this->input->get('sdate') && !$this->input->get('room_type') ) {
            $sdate = $this->input->get('sdate');
			$result=$this->db->query("SELECT sma_rooms.name as id,T6.* FROM (SELECT sma_roomstats.room_id,IF(sma_companies.customer_group_id ='5',sma_vouchers.contact_name,sma_companies.name) as customer, IF(sma_companies.customer_group_id ='5',sma_companies.name,'SELF') as company,sma_vouchers.no_adults,sma_vouchers.no_children,sma_vouchers.check_in ,sma_vouchers.check_out,sma_vouchers.total_nights,
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
WHERE '$sdate' >= sma_roomstats.check_in AND  '$sdate' < sma_roomstats.check_out )AS T6 RIGHT JOIN sma_rooms ON sma_rooms.id = T6.room_id")->result();
			$this->data['roomsbydate'] = $result;
			$this->data['datemm'] = $this->input->get('sdate');
        }
		
		
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
			$this->data['rooms'] = $this->site->getAllRooms($rmtype);
			
			//$result=$this->db->query("select name from sma_companies where customer_group_name like '%corporate%' ")->result();
			$this->data['categories'] = $this->site->getCategoryBycode($rmtype);
			
			
			$this->load->view($this->theme . 'rooms/rooming', $this->data);		
				
					
            
       
    }
		 function housekeeping()
    {
		
       $this->sma->checkPermissions(false, true);

       // $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[companies.email]');
        $this->form_validation->set_rules('hkdate', $this->lang->line('Date'), 'required');
			//$date = date('Y-m-d H:i:s',strtotime($this->input->post('hkdate')));
			 $date = $this->sma->fld(trim($this->input->post('hkdate')));
			
        if ($this->form_validation->run('customers/housekeeping') == true) {
            $cg = $this->site->getCustomerGroupByID($this->input->post('customer_group'));
            $data = array('room_id' => $this->input->post('roomhk'),
				'date' => $date,
                'status' => $this->input->post('hkstatus'),
				'rmks'=> $this->input->post('hsekrmks'),
                'user_id' => '3',
                
            );
        } elseif ($this->input->post('update_hk')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('rooms/housekeeping_list');
        }

        if ($this->form_validation->run() == true && $cid = $this->companies_model->addHouseKeeping($data)) {
            $this->session->set_flashdata('message', $this->lang->line("House_Keeping Added"));
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
            redirect('rooms/housekeeping_list');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['rooms'] = $this->site->getAllRooms($rmtype);;
            $this->load->view($this->theme . 'rooms/house_keeping', $this->data);
        }		
            
       
    }
    function add()
    {
        $this->sma->checkPermissions(false, true);

        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[companies.email]');

        if ($this->form_validation->run('companies/add') == true) {
            $cg = $this->site->getCustomerGroupByID($this->input->post('customer_group'));
            $data = array('name' => $this->input->post('company'),
				'rtype' => $this->input->post('rtype'),
                'email' => $this->input->post('email'),
                'group_id' => '3',
                'group_name' => 'customer',
                'customer_group_id' => $this->input->post('customer_group'),
                'customer_group_name' => $cg->name,
                'company' => $this->input->post('company'),
                'address' => $this->input->post('address'),
                'vat_no' => $this->input->post('vat_no'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone'),
				'idno'=>  $this->input->post('idno'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => $this->input->post('cf2'),
                'cf3' => $this->input->post('cf3'),
                'cf4' => $this->input->post('cf4'),
                'cf5' => $this->input->post('cf5'),
                'cf6' => $this->input->post('cf6'),
            );
        } elseif ($this->input->post('add_customer')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && $cid = $this->companies_model->addCompany($data)) {
            $this->session->set_flashdata('message', $this->lang->line("customer_added"));
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
            redirect($ref[0] . '?customer=' . $cid);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->load->view($this->theme . 'customers/add', $this->data);
        }
    }

    function edit($id = NULL)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $company_details = $this->companies_model->getCompanyByID($id);
        if ($this->input->post('email') != $company_details->email) {
            $this->form_validation->set_rules('code', lang("email_address"), 'is_unique[companies.email]');
        }

        if ($this->form_validation->run('companies/add') == true) {
            $cg = $this->site->getCustomerGroupByID($this->input->post('customer_group'));
            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'group_id' => '3',
                'group_name' => 'customer',
                'customer_group_id' => $this->input->post('customer_group'),
                'customer_group_name' => $cg->name,
                'company' => $this->input->post('company'),
                'address' => $this->input->post('address'),
                'vat_no' => $this->input->post('vat_no'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => $this->input->post('cf2'),
                'cf3' => $this->input->post('cf3'),
                'cf4' => $this->input->post('cf4'),
                'cf5' => $this->input->post('cf5'),
                'cf6' => $this->input->post('cf6'),
                'award_points' => $this->input->post('award_points'),
            );
        } elseif ($this->input->post('edit_customer')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->companies_model->updateCompany($id, $data)) {
            $this->session->set_flashdata('message', $this->lang->line("customer_updated"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['customer'] = $company_details;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->load->view($this->theme . 'customers/edit', $this->data);
        }
    }

    function checkin($company_id = NULL)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }

        $post = array();
        foreach ( $_POST as $key => $value )
        {
            $post[$key] = $this->input->post($key);
        }
        //var_dump(json_encode($post));
        $actual_data = $this->input->post('data');
        //var_dump($actual_data[1]['kin_full_name']);

        $company = $this->companies_model->getCompanyByID($company_id);

        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[guests.email]');

        if ($this->form_validation->run('companies/add') == true) {

            $personal_data = array(
                'company_id' => $company_id,
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
                'passport_expiry' => $actual_data[0]['passport_expiry'],
            );



        } elseif ($this->input->post('check_in_customer')) {
            $this->session->set_flashdata('error', validation_errors());
            //replace this with the guest index route
            redirect('guests');
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
                                $this->data['company'] = $company;
                                $this->load->view($this->theme . 'customers/checkin', $this->data);
                            }

                        }else{
                            $this->session->set_flashdata('message', $this->lang->line("guest_added"));
                            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
                            redirect('guests');
                        }
                    }else{
                        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
                        $this->data['modal_js'] = $this->site->modal_js();
                        $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
                        $this->data['company'] = $company;
                        $this->load->view($this->theme . 'customers/checkin', $this->data);
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
                            $this->data['company'] = $company;
                            $this->load->view($this->theme . 'customers/checkin', $this->data);
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
                $this->data['company'] = $company;
                $this->load->view($this->theme . 'customers/checkin', $this->data);
            }

        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->data['company'] = $company;
            $this->load->view($this->theme . 'customers/checkin', $this->data);
        }
    }

    function users($company_id = NULL)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }


        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['company'] = $this->companies_model->getCompanyByID($company_id);
        $this->data['users'] = $this->companies_model->getCompanyUsers($company_id);
        $this->load->view($this->theme . 'customers/users', $this->data);

    }
    
     function getall()
    {
       
        $customers= $this->companies_model->getAllCustomerCompanies();
        //die(print_r($customers));
        echo json_encode($customers);
        exit();
    }

    function add_user($company_id = NULL)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }
        $company = $this->companies_model->getCompanyByID($company_id);

        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[users.email]');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'required|min_length[8]|max_length[20]|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('confirm_password'), 'required');

        if ($this->form_validation->run('companies/add_user') == true) {
            $active = $this->input->post('status');
            $notify = $this->input->post('notify');
            list($username, $domain) = explode("@", $this->input->post('email'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'company_id' => $company->id,
                'company' => $company->company,
                'group_id' => 3
            );
            $this->load->library('ion_auth');
        } elseif ($this->input->post('add_user')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $active, $notify)) {
            $this->session->set_flashdata('message', $this->lang->line("user_added"));
            redirect("customers");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['company'] = $company;
            $this->load->view($this->theme . 'customers/add_user', $this->data);
        }
    }

    function import_csv()
    {
        $this->sma->checkPermissions();
        $this->load->helper('security');
        $this->form_validation->set_rules('csv_file', $this->lang->line("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('warning', $this->lang->line("disabled_in_demo"));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            if (isset($_FILES["csv_file"])) /* if($_FILES['userfile']['size'] > 0) */ {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/csv/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '2000';
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('csv_file')) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("customers");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen("assets/uploads/csv/" . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5001, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('company', 'name', 'email', 'phone', 'address', 'city', 'state', 'postal_code', 'country', 'vat_no', 'cf1', 'cf2', 'cf3', 'cf4', 'cf5', 'cf6');

                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                $rw = 2;
                foreach ($final as $csv) {
                    if ($this->companies_model->getCompanyByEmail($csv['email'])) {
                        $this->session->set_flashdata('error', $this->lang->line("check_customer_email") . " (" . $csv['email'] . "). " . $this->lang->line("customer_already_exist") . " (" . $this->lang->line("line_no") . " " . $rw . ")");
                        redirect("customers");
                    }
                    $rw++;
                }
                foreach ($final as $record) {
                    $record['group_id'] = 3;
                    $record['group_name'] = 'customer';
                    $record['customer_group_id'] = 1;
                    $record['customer_group_name'] = 'General';
                    $data[] = $record;
                }
                //$this->sma->print_arrays($data);
            }

        } elseif ($this->input->post('import')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && !empty($data)) {
            if ($this->companies_model->addCompanies($data)) {
                $this->session->set_flashdata('message', $this->lang->line("customers_added"));
                redirect('customers');
            }
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'customers/import', $this->data);
        }
    }

    function delete($id = NULL)
    {
        $this->sma->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->input->get('id') == 1) {
            $this->session->set_flashdata('error', lang('customer_x_deleted'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }

        if ($this->companies_model->deleteCustomer($id)) {
            echo $this->lang->line("customer_deleted");
        } else {
            $this->session->set_flashdata('warning', lang('customer_x_deleted_have_sales'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }
    }

    function suggestions($term = NULL, $limit = NULL)
    {
        $this->sma->checkPermissions('index');
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        if (strlen($term) < 1) {
            return FALSE;
        }
        $limit = $this->input->get('limit', TRUE);
        $rows['results'] = $this->companies_model->getCustomerSuggestions($term, $limit);
        echo json_encode($rows);
    }

    function getCustomer($id = NULL)
    {
        $this->sma->checkPermissions('index');
        $row = $this->companies_model->getCompanyByID($id);
        echo json_encode(array(array('id' => $row->id, 'text' => ($row->company != '-' ? $row->company : $row->name))));
    }

    function get_award_points($id = NULL)
    {
        $this->sma->checkPermissions('index');
        $row = $this->companies_model->getCompanyByID($id);
        echo json_encode(array('ca_points' => $row->award_points));
    }

    function customer_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    $error = false;
                    foreach ($_POST['val'] as $id) {
                        if (!$this->companies_model->deleteCustomer($id)) {
                            $error = true;
                        }
                    }
                    if ($error) {
                        $this->session->set_flashdata('warning', lang('customers_x_deleted_have_sales'));
                    } else {
                        $this->session->set_flashdata('message', $this->lang->line("customers_deleted"));
                    }
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('email'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('phone'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('address'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('city'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('state'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('postal_code'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('country'));
                    $this->excel->getActiveSheet()->SetCellValue('J1', lang('vat_no'));
                    $this->excel->getActiveSheet()->SetCellValue('K1', lang('ccf1'));
                    $this->excel->getActiveSheet()->SetCellValue('L1', lang('ccf2'));
                    $this->excel->getActiveSheet()->SetCellValue('M1', lang('ccf3'));
                    $this->excel->getActiveSheet()->SetCellValue('N1', lang('ccf4'));
                    $this->excel->getActiveSheet()->SetCellValue('O1', lang('ccf5'));
                    $this->excel->getActiveSheet()->SetCellValue('P1', lang('ccf6'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $customer = $this->site->getCompanyByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $customer->company);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $customer->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $customer->email);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $customer->phone);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $customer->address);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $customer->city);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $customer->state);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $customer->postal_code);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $customer->country);
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, $customer->vat_no);
                        $this->excel->getActiveSheet()->SetCellValue('K' . $row, $customer->cf1);
                        $this->excel->getActiveSheet()->SetCellValue('L' . $row, $customer->cf2);
                        $this->excel->getActiveSheet()->SetCellValue('M' . $row, $customer->cf3);
                        $this->excel->getActiveSheet()->SetCellValue('N' . $row, $customer->cf4);
                        $this->excel->getActiveSheet()->SetCellValue('O' . $row, $customer->cf5);
                        $this->excel->getActiveSheet()->SetCellValue('P' . $row, $customer->cf6);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'customers_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', $this->lang->line("no_customer_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

}
