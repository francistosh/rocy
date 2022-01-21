<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('vouchers_model');
    }

    public function get_calendar_data_old($year, $month)
    {

        if ($this->Settings->restrict_calendar) {
            $query = $this->db->select('date, data')->from('calendar')->like('date', "$year-$month", 'after')->where('user_id', $this->session->userdata('user_id'))->get();
        } else {
            $query = $this->db->select('date, data')->from('calendar')->like('date', "$year-$month", 'after')->get();
        }
        $cal_data = array();
        //die(print_r($query->result()));
        foreach ($query->result() as $row) {
            $day = (int)substr($row->date, 8, 2);
            //$cal_data[$day] = str_replace("|", "<br>", html_entity_decode($row->data));
            $cal_data[$day][]= $this->sma->decode_html($row->data);
        }
        return $cal_data;

    }

    public function get_calendar_data($year, $month)
    {
        $cal_data = array();
        for($i=1;$i<=days_in_month($month,$year);$i++){
            $date = $year."-".$month."-".$i;
            $query = $this->db->select('vouchers.id as id,companies.name,vouchers.voucher_no,vouchers.status,vouchers.group_name,vouchers.check_in,vouchers.check_out,vouchers.total_nights,sum(single_room)+sum(double_room)+sum(twin_room)+sum(triple_room)+sum(family_room)+sum(honeymoon_room) AS total_rooms')
            ->from('vouchers')
            ->join('companies', 'vouchers.customer_id = companies.id')
            ->where("check_in <=",$date)
            ->where("DATE_SUB(check_out, INTERVAL 1 DAY) >=",$date)
            ->where('status',"Reserved")
            ->get();
            foreach ($query->result() as $row) {
                if($row->total_rooms>0){
                    $cal_data[$i][] = $this->sma->decode_html("<a href=https://techsavanna.net:8181/safaris-latest/vouchers/vouchers_date?voucher_date=".$date."&search=Search'>See vouchers</a><br>"."<span style='color: orange'> Capacity: 20</span><br>"."<span style='color: red'>Occupied: ".$row->total_rooms."</span><br>"."<span style='color: green'>Available: ".(20-$row->total_rooms)."</span>");
                }else{
                    $cal_data[$i][] = $this->sma->decode_html("<a href='https://techsavanna.net:8181/safaris-latest/vouchers/vouchers_date?voucher_date=".$date."&search=Search'>See vouchers</a><br>"."<span style='color: orange'> Capacity: 20</span><br>"."<span style='color: red'>Occupied: 0</span><br>"."<span style='color: green'>Available: ".(20-$row->total_rooms)."</span>");
                }

            }


        }

        return $cal_data;

    }

    function is_leap_year($year)
    {
        return ((($year % 4) == 0) && ((($year % 100) != 0) || (($year %400) == 0)));
    }

    public function add_calendar_data($date, $data)
    {

        $data = $this->sma->clear_tags($data, '<p><br><br /><br/>');
        if (empty($data)) {
            $this->deleteEvent($date);
        } else {
            /*  if ($this->db->select('date')->from('calendar')
                  ->where('date', $date)->count_all_results()
              ) {

                  $this->db->where('date', $date)
                      ->update('calendar', array(
                          'date' => $date,
                          'data' => $data,
                          'user_id' => $this->session->userdata('user_id')
                      ));

              } else {*/

            $this->db->insert('calendar', array(
                'date' => $date,
                'data' => $data,
                'user_id' => $this->session->userdata('user_id')
            ));
            //}
        }

    }

    public function deleteEvent($date)
    {

        if ($this->db->delete('calendar', array('date' => $date))) {
            return true;
        }
        return FALSE;
    }


}

/* End of file calendar_model.php */
/* Location: ./application/models/calendar_model.php */