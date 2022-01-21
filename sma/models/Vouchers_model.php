<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vouchers_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function addVoucher($data = array())
    {
        if ($this->db->insert('vouchers', $data)) {
            $gid = $this->db->insert_id();
            return $gid;
        }
        return false;
    }
	public function addRoomStats($data,$chkindate,$check_out,$customer_id,$saleid)
    {
		$comp_Split = explode(",",$data);
		print_r($comp_Split);
		$cnt = count($comp_Split);
		//e($cnt);
		for ($i=0;$i<$cnt;$i++){
		$this->db->insert('roomstats', array('room_id' =>$comp_Split[$i], 'check_in' => $chkindate, 'check_out' => $check_out, 'cust_id' => $customer_id,'status'=>'occupied','sale_id'=>$saleid));
	
		}
           $gid = $this->db->insert_id();
            return $gid;
       
        return false;
    }
	public function addVoucherItems($data = array())
    {
        if ($this->db->insert('invoice_items', $data)) {
            $gid = $this->db->insert_id();
            return $gid;
        }
        return false;
    }
    public function addServiceVoucher($data = array())
    {
        if ($this->db->insert('service_vouchers', $data)) {
            $gid = $this->db->insert_id();
            return $gid;
        }
        return false;
    }

    public function addVoucherChildren($data = array())
    {
        if ($this->db->insert('vouchers_children', $data)) {
            return true;
        }
        return false;
    }

    public function getCompanyByGroupID($customer_group_id) {

        $this->db->select('*');
        $this->db->from('companies');
        $this->db->where('customer_group_id' , $customer_group_id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        return FALSE;
    }

    public function getVouchersByDate($date) {

        $this->db->select('vouchers.id as id,companies.name,vouchers.voucher_no,vouchers.status,vouchers.group_name,vouchers.check_in,vouchers.check_out,vouchers.total_nights,sum(single_room)+sum(double_room)+sum(twin_room)+sum(triple_room)+sum(family_room)+sum(honeymoon_room) AS total_rooms');
        $this->db->from('vouchers');
        $this->db->join('companies', 'vouchers.customer_id = companies.id');
        $this->db->where("check_in <=",$date);
        $this->db->where("DATE_SUB(check_out, INTERVAL 1 DAY) >=",$date);
        $this->db->where('status',"Reserved");
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllVouchers() {

        $q = $this->db->get('vouchers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getVoucherArrayByID($id) {

        $q = $this->db->get_where('vouchers', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getVoucherByNumber($voucher_no)
    {
        $q = $this->db->get_where('vouchers', array('voucher_no' => $voucher_no), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getVoucherByID($id)
    {
        $q = $this->db->get_where('vouchers', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getInvByVoucherID($id)
    {
        $q = $this->db->get_where('sales', array('voucher_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return array();
    }

    public function getServiceVoucherByID($id)
    {
        $q = $this->db->get_where('service_vouchers', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        return FALSE;
    }

    public function getServiceVoucherByVoucherID($id)
    {
        $q = $this->db->get_where('service_vouchers', array('voucher_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getInvItemsByInvId($sale_id){

        $q = $this->db->get_where('invoice_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        return $data = array();
    }

    public function getVoucherChildrenByVoucherID($id)
    {

        $this->db->select('*');
        $this->db->from('vouchers_children');
        $this->db->where('voucher_id' , $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        return $data = array();
    }


    public function updateVoucher($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('vouchers', $data)) {
            return true;
        }
        return false;
    }

    public function updateServiceVoucher($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('service_vouchers', $data)) {
            return true;
        }
        return false;
    }


    public function deleteVoucher($id)
    {

        if ($this->db->delete('vouchers', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteChild($id)
    {

        if ($this->db->delete('vouchers_children', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
    public function deleteAllChildren($id)
    {

        if ($this->db->delete('vouchers_children', array('voucher_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteInvItemByNameAndSaleId($name,$id){
        if ($this->db->delete('invoice_items', array('name' => $name,'sale_id'=>$id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteInvItemById($id){
        if ($this->db->delete('invoice_items', array('id'=>$id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteServiceVoucherByVoucherId($voucher_id){
        if ($this->db->delete('service_vouchers', array('voucher_id'=>$voucher_id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteServiceVoucherById($id){
        if ($this->db->delete('service_vouchers', array('id'=>$id))) {
            return true;
        }
        return FALSE;
    }
}
