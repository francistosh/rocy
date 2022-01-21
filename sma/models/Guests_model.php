<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Guests_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function addGuest($data = array())
    {
        if ($this->db->insert('guests', $data)) {
            $gid = $this->db->insert_id();
            return $gid;
        }
        return false;
    }

    public function addNextOfKin($data = array())
    {
        if ($this->db->insert('guests_next_of_kins', $data)) {
            /*$gid = $this->db->insert_id();*/
            return true;
        }
        return false;
    }

    public function addRequirement($data = array())
    {
        if ($this->db->insert('guest_requirements', $data)) {
            /*$gid = $this->db->insert_id();*/
            return true;
        }
        return false;
    }

    public function addMedicalCondition($data = array())
    {
        if ($this->db->insert('guest_medical_conditions', $data)) {
            /*$gid = $this->db->insert_id();*/
            return true;
        }
        return false;
    }

    public function addRating($data = array())
    {
        if ($this->db->insert('guests_ratings', $data)) {
            /*$gid = $this->db->insert_id();*/
            return true;
        }
        return false;
    }

    public function addTemperature($data = array())
    {
        if ($this->db->insert('guests_temperatures', $data)) {
            /*$gid = $this->db->insert_id();*/
            return true;
        }
        return false;
    }

    public function addGuests($data = array())
    {
        if ($this->db->insert_batch('guests', $data)) {
            return true;
        }
        return false;
    }

    public function addNextOfKins($data = array())
    {
        if ($this->db->insert_batch('guests_next_of_kins', $data)) {
            return true;
        }
        return false;
    }

    public function addRequirements($data = array())
    {
        if ($this->db->insert_batch('guest_requirements', $data)) {
            return true;
        }
        return false;
    }

    public function addMedicalConditions($data = array())
    {
        if ($this->db->insert_batch('guest_medical_conditions', $data)) {
            return true;
        }
        return false;
    }

    public function getGuestByID($id)
    {
        $q = $this->db->get_where('guests', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getGuestByToken($token)
    {
        $q = $this->db->get_where('guests', array('token' => $token,'token_used' => false), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getNextOfKinByGuestID($id)
    {
        $q = $this->db->get_where('guests_next_of_kins', array('guest_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getRequirementsByGuestID($id)
    {
        $this->db->select('*');
        $this->db->from('guest_requirements');
        $this->db->where('guest_id' , $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        return FALSE;
    }

     public function getMedicalConditionsByGuestID($id)
    {
        $this->db->select('*');
        $this->db->from('guest_medical_conditions');
        $this->db->where('guest_id' , $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        return FALSE;
    }

    public function getTemperaturesByGuestID($id)
    {
        $this->db->select('*');
        $this->db->from('guests_temperatures');
        $this->db->where('guest_id' , $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        return FALSE;
    }

    public function updateGuest($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('guests', $data)) {
            return true;
        }
        return false;
    }

    public function updateNextOfKin($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('guests_next_of_kins', $data)) {
            return true;
        }
        return false;
    }

    public function deleteGuest($id)
    {

        if ($this->db->delete('guests', array('id' => $id)) &&
            $this->db->delete('guests_next_of_kins', array('guest_id' => $id)) &&
            $this->db->delete('guest_requirements', array('guest_id' => $id)) &&
            $this->db->delete('guest_medical_conditions', array('guest_id' => $id)) &&
            $this->db->delete('guests_temperatures', array('guest_id' => $id))) {
            return true;
        }
        return FALSE;
    }

}
