<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_options_model extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get current news data
     */
    public function get_option_values(){
        $this->db->order_by('order', 'ASC');
        $query = $this->db->get('option_value');
        return $query->result();
    }

    /**
     * Add new news
     */
    public function add_option_values($data){
        return $this->db->insert('option_value', $data);
    }

    /**
     * Update specific option
     */
    public function update_option($option_value_id, $data) {
        $this->db->where('option_value_id', $option_value_id);
        return $this->db->update('option_value', $data);
    }

    /**
     * Update existing options
     */
    public function update_option_values($data){
        // Since we only have one news record, update the first one
        $this->db->limit(1);
        return $this->db->update('option_value', $data);
    }

    /**
     * Delete option values (if needed)
     */
    public function delete_option_values(){
        return $this->db->truncate('option_value');
    }
}
