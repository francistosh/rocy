<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rooms extends MY_Controller
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
        $this->lang->load('rooms', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('rooms_model');
        $this->load->model('sales_model');
        $this->load->model('pos_model');
        $this->load->model('settings_model');
    }

    function index($action = NULL)
    {
        $this->sma->checkPermissions('index',null,'rooms');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('rooms')));
        $meta = array('page_title' => lang('rooms'), 'bc' => $bc);
        $this->page_construct('rooms/index', $meta, $this->data);
    }

	    function housekeeping_list($action = NULL)
    {
        $this->sma->checkPermissions('index',null,'rooms');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('housekeepinglist')));
        $meta = array('page_title' => lang('housekeepinglist'), 'bc' => $bc);
        $this->page_construct('rooms/housekeepinglist', $meta, $this->data);
    }
	
    function getHouseKeepingList()
    {
        $this->sma->checkPermissions('index',null,'rooms');
        $this->load->library('datatables');
		$date = date('Y-m-d');
        $this->datatables
            ->select("sma_house_keeping.id as id,sma_rooms.name, sma_house_keeping.date , sma_house_keeping.date, 	
			CASE
    WHEN sma_house_keeping.status = 'vc'  THEN 'Vacant Clean'
	WHEN sma_house_keeping.status = 'vd'  THEN 'Vacant Dirty'
	WHEN sma_house_keeping.status = 'oc'  THEN 'Occupied Clean'
	WHEN sma_house_keeping.status = 'ooo'  THEN 'Out of Order'
	WHEN sma_house_keeping.status = 'oos'  THEN 'Out of Service'
	WHEN sma_house_keeping.status = 'od'  THEN 'Occupied Dirty'
	WHEN sma_house_keeping.status = 'dnr'  THEN 'Departure Not Ready'
	WHEN sma_house_keeping.status = 'vnr'  THEN 'Vacant Not Ready'
	ELSE 'dEFAULT'
	END as status,
			,sma_house_keeping.rmks,sma_house_keeping.user_id")
            ->from("sma_house_keeping")
			->where('DATE_FORMAT(sma_house_keeping.date,"%Y-%m-%d")', $date)
            ->join('sma_rooms', 'sma_rooms.id = sma_house_keeping.room_id','left')
            ->add_column("Actions", "<center>
                <a class=\"tip\" title='" . $this->lang->line("pdf") . "' href='" . site_url('rooms/pdf_bill/$1') . "'><i class=\"fa fa-file-pdf-o\"></i></a> 
                
                <a class=\"tip\" title='" . $this->lang->line("bill") . "' href='" . site_url('rooms/bill/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-money\"></i></a> 
                <a class=\"tip\" title='" . $this->lang->line("edit_room") . "' href='" . site_url('rooms/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-pencil\"></i></a> 
                <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_room") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('rooms/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
        //->unset_column('id');
        echo $this->datatables->generate();

    }
	    function getRooms()
    {
        $this->sma->checkPermissions('index',null,'rooms');
        $this->load->library('datatables');
        $this->datatables
            ->select("sma_rooms.id as id, sma_rooms.name, sma_categories.name as hotel")
            ->from("sma_rooms")
            ->join('sma_categories', 'sma_rooms.category_type = sma_categories.id','left')
            ->add_column("Actions", "<center>
                <a class=\"tip\" title='" . $this->lang->line("pdf") . "' href='" . site_url('rooms/pdf_bill/$1') . "'><i class=\"fa fa-file-pdf-o\"></i></a> 
                
                <a class=\"tip\" title='" . $this->lang->line("bill") . "' href='" . site_url('rooms/bill/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-money\"></i></a> 
                <a class=\"tip\" title='" . $this->lang->line("edit_room") . "' href='" . site_url('rooms/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-pencil\"></i></a> 
                <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_room") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('rooms/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
        //->unset_column('id');
        echo $this->datatables->generate();

    }
 function guests($action = NULL)
    {
        $this->sma->checkPermissions('index',null,'rooms');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('rooms')));
        $meta = array('page_title' => lang('rooms'), 'bc' => $bc);
        $this->page_construct('rooms/guests', $meta, $this->data);
    }
    function getGuests()
    {
        $this->sma->checkPermissions('index',null,'rooms');
        $this->load->library('datatables');

		 $this->datatables
            ->select("id, name, customer_group_name ")
            ->from("companies")
            ->where('group_name', 'customer')
			->where('customer_group_name', 'Business/Corporate')
            ->add_column("Actions", "<center>
                <a class=\"tip\" title='" . $this->lang->line("pdf") . "' href='" . site_url('rooms/pdf_bill/$1') . "'><i class=\"fa fa-file-pdf-o\"></i></a> 
                <a class=\"tip\" title='" . $this->lang->line("Guest_Bill") . "' href='" . site_url('rooms/allbills/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-money\"></i></a> 
                <a class=\"tip\" title='" . $this->lang->line("edit_room") . "' href='" . site_url('rooms/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-pencil\"></i></a>", "id");
        //->unset_column('id');
        echo $this->datatables->generate();
		
    }	
    function getRoom($id = NULL)
    {
        $this->sma->checkPermissions('index',null,'rooms');
        $row = $this->rooms_model->getRoomByID($id);
        echo json_encode(array(array('id' => $row->id, 'text' => $row->name)));
    }

    function add()
    {
        $this->sma->checkPermissions('add',true,'rooms');

        $this->form_validation->set_rules('roomtype', 'Hotel', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run('rooms/add') == true) {

            $data = array(
                'category_type' => $this->input->post('roomtype'),
				'hotel_id' => '4',
                'name' => $this->input->post('name'),
            );

        } elseif ($this->input->post('add_room')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('rooms');
        }

        if ($this->form_validation->run() == true && $rid = $this->rooms_model->addRoom($data)) {
            $this->session->set_flashdata('message', $this->lang->line("room_added"));
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
            redirect('rooms');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['warehouses'] = $this->site->getAllInvCategories();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('rooms')));
            $meta = array('page_title' => lang('rooms'), 'bc' => $bc);
            $this->page_construct('rooms/add', $meta, $this->data);
        }
    }

    function edit($id = NULL)
    {
        $this->sma->checkPermissions('edit',true,'rooms');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $room = $this->rooms_model->getRoomByID($id);

        $this->form_validation->set_rules('warehouse', 'Hotel', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run('rooms/add') == true) {

            $data = array(
                'category_type' => $this->input->post('warehouse'),
                'hotel_id' =>'4',
				'name' => $this->input->post('name'),
               'quantity' => $this->input->post('quantity'),
            );

        } elseif ($this->input->post('edit_room')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('rooms');
        }

        if ($this->form_validation->run() == true && $rid = $this->rooms_model->updateRoom($id,$data)) {
            $this->session->set_flashdata('message', $this->lang->line("room_updated"));
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
            redirect('rooms');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['warehouses'] = $this->site->getAllInvCategories();
            $this->data['room'] = $room;
            $this->load->view($this->theme . 'rooms/edit', $this->data);
        }
    }

    function bill($id = NULL)
    {
        $this->sma->checkPermissions('bill',true,'rooms');

$arr = $this->input->post('amount');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $room = $this->rooms_model->getRoomByID($id);
        $sale_items = $this->sales_model->getUnpaidBillsByRoomId($room->id);
		//$sale_items = $this->sales_model->getUnpaidInvoiceItemsByRoomId($room->id);
        $sales = $this->sales_model->getAllSalesByRoomId($room->id);
		
		//print_r($sales);
        $user = $this->rooms_model->getUserByID($this->session->userdata('user_id'));

        $this->form_validation->set_rules('warehouse', 'Hotel', 'required');


        if ($this->form_validation->run() == true) {
				
foreach ($arr as $key => $value) {
	if($value !=0){
	  $payment = array(
                    'date' => date('Y-m-d H:i:s'),
                    'sale_id' => $key,
                    'reference_no' => $this->site->getReference('pay'),
                    'amount' => $value,
                    'bill_change' => 0.00,
                    'paid_by' => $this->input->post('paid_by'),
					'room_id'=> $id,
                    'chef_id' => $this->session->userdata('user_id'),
                    'chef' => $user->first_name .' '.$user->last_name,

                    'cashier_id' => $this->session->userdata('user_id'),
                    'cashier' => $user->first_name .' '.$user->last_name,

                    'mpesa_transaction_no' => NULL,
                    'cost_center_no' => NULL,
                    'cheque_no' => NULL,
                    'cc_no' => NULL,
                    'cc_holder' => NULL,
                    'cc_month' => NULL,
                    'cc_year' => NULL,
                    'cc_type' => NULL,
                    'cc_cvv2' => NULL,
                    'note' => NULL,
                    'created_by' => $this->session->userdata('user_id'),
                    'type' => 'received'
                );
				$this->pos_model->addReceptionPayment($payment);
}
}
          


            $this->session->set_flashdata('message', $this->lang->line("room_bill_paid"));
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
            redirect('rooms');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['room'] = $room;
            $this->data['sale_items'] = $sale_items;
            $this->load->view($this->theme . 'rooms/bill', $this->data);
        }
		
    }

	  function allbills($id = NULL)
    {
        $this->sma->checkPermissions('bill',true,'rooms');

        $arr = $this->input->post('amountg');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
      
        $sale_items = $this->sales_model->getAllBarReceptionSalesByCustID($id);
		//print_r($id);
		//die();
        $sales = $this->sales_model->getAllSalesByCustId($id);
        $user = $this->rooms_model->getUserByID($this->session->userdata('user_id'));
		  $result=$this->db->query("select name,id from sma_companies where id = '$id' ")->result();

        $this->form_validation->set_rules('warehouse', 'Hotel', 'required');


        if ($this->form_validation->run() == true) {

foreach ($arr as $key => $value) {
	if($value !=0){
	  $payment = array(
                    'date' => date('Y-m-d H:i:s'),
                    'sale_id' => $key,
                    'reference_no' => $this->site->getReference('pay'),
                    'amount' => $value,
                    'bill_change' => 0.00,
                    'paid_by' => $this->input->post('paidg_by'),
					'room_id'=> $id,
                    'chef_id' => $this->session->userdata('user_id'),
                    'chef' => $user->first_name .' '.$user->last_name,

                    'cashier_id' => $this->session->userdata('user_id'),
                    'cashier' => $user->first_name .' '.$user->last_name,

                    'mpesa_transaction_no' => NULL,
                    'cost_center_no' => $result->name,
                    'cheque_no' => NULL,
                    'cc_no' => NULL,
                    'cc_holder' => NULL,
                    'cc_month' => NULL,
                    'cc_year' => NULL,
                    'cc_type' => NULL,
                    'cc_cvv2' => NULL,
                    'note' => NULL,
                    'created_by' => $this->session->userdata('user_id'),
                    'type' => 'received'
                );
				
				$this->pos_model->addReceptionPayment($payment);
}
}

//print_r($payment);
//die();
            $this->session->set_flashdata('message', $this->lang->line("bills_paid"));
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
            redirect('rooms/guests');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['room'] = $room;
			$this->data['customer'] = $result;
            $this->data['sale_items'] = $sale_items;
            $this->load->view($this->theme . 'rooms/guestbill', $this->data);
        }
    }
	
    function pdf_bill($id = NULL, $view = NULL, $save_bufffer = NULL)
    {
        $this->sma->checkPermissions('pdf',null,'rooms');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $room = $this->rooms_model->getRoomByID($id);
        $sale_items = $this->sales_model->getAllInvoiceItemsByRoomId($room->id);
        $sales = $this->sales_model->getAllSalesByRoomId($room->id);
        $user = $this->rooms_model->getUserByID($this->session->userdata('user_id'));

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $this->data['room'] = $room;
        $this->data['sale_items'] = $sale_items;
        $this->data['sales'] = $sales;
        $this->data['user'] = $user;
        $this->data['settings'] = $this->settings_model->getSettings();
        $this->data['current_date'] = date("d-m-Y");

        $name = lang("Room Bill") . "_" . str_replace('/', '_', $room->id) . ".pdf";
        $html = $this->load->view($this->theme . 'rooms/pdf_bill', $this->data, TRUE);
        if ($view) {
            $this->load->view($this->theme . 'rooms/pdf_bill', $this->data);
        } elseif ($save_bufffer) {
            return $this->sma->generate_pdf($html, $name, $save_bufffer, 'Thank you');
        } else {
            $this->sma->generate_pdf($html, $name, FALSE, 'Thank you');
        }
    }
    
    function delete($id = NULL)
    {
        $this->sma->checkPermissions('delete',true,'rooms');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->rooms_model->deleteRoom($id)) {

            echo $this->lang->line("room_deleted");
        } else {
            $this->session->set_flashdata('warning', lang('room_not_deleted'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }
    }

    function suggestions($term = NULL, $limit = NULL)
    {
        $this->sma->checkPermissions('index',null,'rooms');

        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        if (strlen($term) < 1) {
            return FALSE;
        }
        $limit = $this->input->get('limit', TRUE);
        $rows['results'] = $this->rooms_model->getRoomSuggestions($term, $limit);
        echo json_encode($rows);
    }
	function status()
    {
         $this->sma->checkPermissions('edit',true,'rooms');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $room = $this->rooms_model->getRoomByID($id);

        $this->form_validation->set_rules('warehouse', 'Hotel', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run('rooms/add') == true) {

            $data = array(
                'category_type' => $this->input->post('category'),
                'hotel_id' => $this->input->post('warehouse'),
                'name' => $this->input->post('name'),
                'quantity' => $this->input->post('quantity'),
            );

        } elseif ($this->input->post('edit_room')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('rooms');
        }

        if ($this->form_validation->run() == true && $rid = $this->rooms_model->updateRoom($id,$data)) {
            $this->session->set_flashdata('message', $this->lang->line("room_updated"));
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
            redirect('rooms');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['room'] = $room;
            $this->load->view($this->theme . 'rooms/status', $this->data);
        }
    }
}
