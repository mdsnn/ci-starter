<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Crud_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    // 26/9/24 MS: Insert a new entry into the database

    public function insert_entry($data)
    {
        
        $this->db->where('name', $data['name']);
        $this->db->or_where('email', $data['email']);
        $existing = $this->db->get('crud')->result();

        if (!empty($existing)) {
            return false; 
        }

        return $this->db->insert('crud', $data);
    }

    // 26/9/24 MS: Fetch all entries from the database

    public function get_entries()
    {
        return $this->db->get('crud')->result(); 
    }

    // 26/9/24 MS: Delete an entry from the database

    public function delete_entry($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('crud'); 
    }

    // 26/9/24 MS: Edit an existing entry

    public function edit_entry($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('crud')->row(); 
    }

    // 26/9/24 MS: Update an existing entry
    
    public function update_entry($data)
    {
        $this->db->where('id', $data['id']);
        return $this->db->update('crud', $data); 
    }
}
