<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rooms_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function addRoom($data = array())
    {
        if ($this->db->insert('rooms', $data)) {
            $rid = $this->db->insert_id();
            return $rid;
        }
        return false;
    }

    public function getRoomSuggestions($term, $limit = 10)
    {
        $this->db->select("id, name as text", FALSE);
        $this->db->where(" (id LIKE '%" . $term . "%' OR name LIKE '%" . $term . "%') ");
        $q = $this->db->get_where('rooms', array('name !=' => NULL), $limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getRoomByID($id)
    {
        $q = $this->db->get_where('rooms', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
  public function getAllRooms() {
        $q = $this->db->get('rooms');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getUserByID($id)
    {
        $q = $this->db->get_where('users', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateRoom($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('rooms', $data)) {
            return true;
        }
        return false;
    }

    public function deleteRoom($id)
    {

        if ($this->db->delete('rooms', array('id' => $id))){
            return true;
        }
        return FALSE;
    }

}
