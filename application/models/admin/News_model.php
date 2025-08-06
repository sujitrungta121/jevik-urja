<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class News_model extends CI_Model { 

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get current news data
     */
    public function get_news(){
        $query = $this->db->get('news');
        return $query->row();
    }

    /**
     * Add new news
     */
    public function add_news($data){
        return $this->db->insert('news', $data);
    }

    /**
     * Update existing news
     */
    public function update_news($data){
        // Since we only have one news record, update the first one
        $this->db->limit(1);
        return $this->db->update('news', $data);
    }

    /**
     * Delete news (if needed)
     */
    public function delete_news(){
        return $this->db->truncate('news');
    }
}
