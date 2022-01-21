<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Guests extends MY_Controller
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
        $this->load->helper('string');
        $this->load->library('form_validation');
        $this->load->model('companies_model');
        $this->load->model('guests_model');
        $this->load->model('vouchers_model');
    }

    function index($action = NULL)
    {
        $this->sma->checkPermissions('index',null,'guests');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('Guest')));
        $meta = array('page_title' => lang('customers'), 'bc' => $bc);
        $this->page_construct('guests/index', $meta, $this->data);
    }

    function getGuests()
    {
        $this->sma->checkPermissions('index',null,'guests');
        $this->load->library('datatables');
        $this->datatables
            ->select("guests.id as id, vouchers.group_name, guests.full_name, guests.nationality, guests.passport_id_number, guests.gender, guests.telephone, guests.phone, guests.email, guests.status")
            ->from("guests")
            ->join('vouchers', 'guests.voucher_id = vouchers.id')
            ->add_column("Actions", "<center>
                <a class=\"tip\" title='" . $this->lang->line("record_temp") . "' href='" . site_url('guests/temperature/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-fire\"></i></a> 
                <a class=\"tip\" title='" . $this->lang->line("guest_details") . "' href='" . site_url('guests/show/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-user-md\"></i></a> 
                <a class=\"tip\" title='" . $this->lang->line("edit_guest") . "' href='" . site_url('guests/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-pencil\"></i></a>
                <a class=\"tip\" title='" . $this->lang->line("checkout_guest") . "' href='" . site_url('guests/checkout/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-sign-out\"></i></a>
                <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_guest") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('guests/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
        //->unset_column('id');
        echo $this->datatables->generate();
    }

    function add()
    {
        $this->sma->checkPermissions('add',null,'guests');

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

    function checkout($id = NULL){
        $this->sma->checkPermissions('check-out',true,'guests');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $guest = $this->guests_model->getGuestByID($id);

        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'required');

        if ($this->form_validation->run('companies/add') == true) {
            $personal_data = array(
                'full_name' => $guest->full_name,
                'nationality' => $guest->nationality,
                'passport_id_number' => $guest->passport_id_number,
                'gender' => $guest->gender,
                'address' => $guest->address,
                'telephone' => $guest->telephone,
                'phone' => $guest->phone,
                'alt_phone' => $guest->alt_phone,
                'email' => $guest->email,
                'alt_email' => $guest->alt_email,
                'dob' => $guest->dob,
                'passport_expiry' => $guest->passport_expiry,
                'room' => $guest->room,
                'travel_history' => $guest->travel_history,
                'status' => '<span class="label label-danger">out</span>',
            );
            $data = array(
                'status'=> 'Completed',
            );
        } elseif ($this->input->post('checkout_guest')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("guests");
        }

        if ($this->form_validation->run() == true && $this->vouchers_model->updateVoucher($guest->voucher_id, $data) && $this->guests_model->updateGuest($id, $personal_data) && $this->sma->send_email($guest->email, "ROCY HOTEL CHECK OUT", "Dear, ".$guest->full_name."<br> We hope you are well. Please help us by confirming the details below. Click the link below to confirm your details.<br> Regards <br> <a href='https://techsavanna.net:8181/safaris-latest/confirms/confirm/".$guest->token."'>https://techsavanna.net:8181/safaris-latest/confirms/confirm/".$guest->token."</a>", NULL, NULL, NULL, NULL, NULL)) {

            $this->session->set_flashdata('message', lang("guest_checked_out"));
            redirect("guests");
        }else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->data['guest'] = $guest;
            $this->load->view($this->theme . 'guests/checkout', $this->data);
        }




    }

    function confirm($token = NULL)
    {

        if ($this->input->get('id')) {
            $token = $this->input->get('id');
        }

        $guest = $this->guests_model->getGuestByToken($token);
        $next_of_kin = $this->guests_model->getNextOfKinByGuestID($guest->id);
        $requirements = $this->guests_model->getRequirementsByGuestID($guest->id);
        $medical_conditions = $this->guests_model->getMedicalConditionsByGuestID($guest->id);
        $temperatures = $this->guests_model->getTemperaturesByGuestID($guest->id);

        $this->form_validation->set_rules('rating', "Rating required", 'required');

        if ($this->form_validation->run('guests/confirm') == true) {
            $data = array(
                'guest_id' => $guest->id,
                'rating' => $this->input->post('rating'),
                'briefing' => $this->input->post('briefing'),
                'ambience' => $this->input->post('ambience'),
                'tent_amenities' => $this->input->post('tent_amenities'),
                'tent_cleanliness' => $this->input->post('tent_cleanliness'),
                'bar_restaurant_ambience' => $this->input->post('bar_restaurant_ambience'),
                'food_quality' => $this->input->post('food_quality'),
                'staff_service' => $this->input->post('staff_service'),
                'felt_welcomed' => $this->input->post('felt_welcomed'),
                'covid_compliance' => $this->input->post('covid_compliance'),
                'stay_again' => $this->input->post('stay_again'),
                'comment' => $this->input->post('comment'),
            );
            $personal_data = array(
                'voucher_id' => $guest->voucher_id,
                'service_voucher_id' => $guest->service_voucher_id,
                'full_name' => $guest->full_name,
                'nationality' => $guest->nationality,
                'passport_id_number' => $guest->passport_id_number,
                'gender' => $guest->gender,
                'address' => $guest->address,
                'telephone' => $guest->telephone,
                'phone' => $guest->phone,
                'alt_phone' => $guest->alt_phone,
                'email' => $guest->email,
                'alt_email' => $guest->alt_email,
                'dob' => $guest->dob,
                'passport_expiry' => $guest->passport_expiry,
                'room' => $guest->room,
                'travel_history' => $guest->travel_history,
                'token' => $guest->token,
                'token_used' => true,
                'status' => '<span class="label label-danger">out</span>',
            );
            $message = "Guest Name ".$guest->full_name."\n Rating over 10 ".$this->input->post('rating')."\n"."Comment ".$this->input->post('comment');
        } elseif ($this->input->post('confirm_guest')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->guests_model->addRating($data) && $this->sma->send_email("rocyhotel@gmail.com", "Feedback", $message, NULL, NULL, NULL, NULL, NULL) ) {
            $this->session->set_flashdata('message', $this->lang->line("guest_confirmed"));
            /*redirect($_SERVER["HTTP_REFERER"]);*/
        } else {
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->data['guest'] = $guest;
            $this->data['next_of_kin'] = $next_of_kin;
            $this->data['requirements'] = $requirements;
            $this->data['medical_conditions'] = $medical_conditions;
            $this->data['temperatures'] = $temperatures;
            $this->load->view($this->theme . 'guests/confirm', $this->data);
        }

    }


    function edit($id = NULL)
    {
        $this->sma->checkPermissions('edit',true,'guests');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $guest = $this->guests_model->getGuestByID($id);
        $next_of_kin = $this->guests_model->getNextOfKinByGuestID($id);
        $actual_data = $this->input->post('data');

        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[guests.email]');

        if ($this->form_validation->run('companies/add') == true) {
            $personal_data = array(
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
                'room' => $actual_data[0]['room'],
                'travel_history' => $actual_data[0]['travel_history'],
            );
            $next_of_kin_data = array(
                'full_name' => $actual_data[1]['kin_full_name'],
                'phone' => $actual_data[1]['kin_phone'],
            );
        } elseif ($this->input->post('edit_guest')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->guests_model->updateGuest($id, $personal_data) && $this->guests_model->updateNextOfKin($id, $next_of_kin_data)) {
            $this->session->set_flashdata('message', $this->lang->line("guest_updated"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->data['guest'] = $guest;
            $this->data['next_of_kin'] = $next_of_kin;
            $this->load->view($this->theme . 'guests/edit', $this->data);
        }
    }

    function temperature($id = NULL)
    {
        $this->sma->checkPermissions('record-temperature',true,'guests');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $guest = $this->guests_model->getGuestByID($id);

        $this->form_validation->set_rules('temperature', "Temperature required", 'required');

        if ($this->form_validation->run('companies/add') == true) {
            $data = array(
                'guest_id' => $id,
                'temp' => $this->input->post('temperature'),
            );
        } elseif ($this->input->post('add_guest_temp')) {
            $this->session->set_flashdata('error', validation_errors());
            //replace this with the guest index route
            redirect('guests');
        }

        if ($this->form_validation->run() == true && $gid = $this->guests_model->addTemperature($data)) {
            $this->session->set_flashdata('message', $this->lang->line("temp_added"));
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
            redirect('guests');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->data['guest'] = $guest;
            $this->load->view($this->theme . 'guests/temp', $this->data);
        }
    }

    function show($id = NULL)
    {
        $this->sma->checkPermissions('index',null,'guests');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $guest = $this->guests_model->getGuestByID($id);

        $next_of_kin = $this->guests_model->getNextOfKinByGuestID($id);
        $requirements = $this->guests_model->getRequirementsByGuestID($id);
        $medical_conditions = $this->guests_model->getMedicalConditionsByGuestID($id);
        $temperatures = $this->guests_model->getTemperaturesByGuestID($id);

        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
        $this->data['guest'] = $guest;
        $this->data['next_of_kin'] = $next_of_kin;
        $this->data['requirements'] = $requirements;
        $this->data['medical_conditions'] = $medical_conditions;
        $this->data['temperatures'] = $temperatures;
        $this->load->view($this->theme . 'guests/view', $this->data);


    }
    
    function getall()
    {
       
        $customers= $this->companies_model->getAllCustomerCompanies();
        //die(print_r($customers));
        echo json_encode($customers);
        exit();
    }

    function import_csv()
    {
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
        $this->sma->checkPermissions('delete',true,'guests');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->guests_model->deleteGuest($id)) {
            echo $this->lang->line("guest_deleted");
        } else {
            $this->session->set_flashdata('warning', lang('guest_not_deleted'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }

    }


}
