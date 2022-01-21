<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Confirms extends MY_Controller
{

    function __construct()
    {

        $this->lang->load('customers', $this->Settings->language);
        $this->load->helper('string');
        $this->load->library('form_validation');
        $this->load->model('companies_model');
        $this->load->model('guests_model');
        $this->load->model('vouchers_model');
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
        $voucher = $this->vouchers_model->getVoucherByID($guest->voucher_d);

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
                'token_used' => true,
            );
            $message = "Group Name ".$voucher->group_name."\n Guest Name ".$guest->full_name."\n Rating over 10 ".$this->input->post('rating')."\n"." Comment ".$this->input->post('comment');
        } elseif ($this->input->post('confirm_guest')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->guests_model->updateGuest($guest->id, $personal_data) && $this->guests_model->addRating($data) && $this->sma->send_email("rocyhotel@gmail.com", "Feedback", $message, NULL, NULL, NULL, NULL, NULL) ) {
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
            $this->load->view($this->theme . 'confirms/confirm', $this->data);
        }

    }


}
