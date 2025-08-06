<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library("session");
        $this->load->helper("url");
        $this->load->helper("form");
        
        if(! $this->session->userdata('validated')){
            redirect('admin/account/login');
        }
    }

    public function index(){
        $this->load->model('admin/news_model');
        
        // Check if news table exists, if not create it
        if (!$this->db->table_exists('news')) {
            $this->setup_database_silent();
        }
        
        // Get existing news data if any
        $data["news_data"] = $this->news_model->get_news();
        
        // Handle form submission
        if($_POST){
            $news_text = $this->input->post("news_text");
            $news_date = $this->input->post("news_date");
            
            if(empty($news_text) || empty($news_date)){
                $this->session->set_flashdata('action_message', 'Both news text and date are required!');
                $this->session->set_flashdata('action_message_type', 'danger');
                redirect($_SERVER['HTTP_REFERER']);
            }
            
            $save_data = array(
                'news_text' => $news_text,
                'news_date' => $news_date,
                'updated_at' => date('Y-m-d H:i:s')
            );
            
            if($data["news_data"]){
                // Update existing news
                $result = $this->news_model->update_news($save_data);
                $message = $result ? 'News updated successfully!' : 'Failed to update news!';
            } else {
                // Add new news
                $save_data['created_at'] = date('Y-m-d H:i:s');
                $result = $this->news_model->add_news($save_data);
                $message = $result ? 'News added successfully!' : 'Failed to add news!';
            }
            
            $this->session->set_flashdata('action_message', $message);
            $this->session->set_flashdata('action_message_type', $result ? 'success' : 'danger');
            redirect($_SERVER['HTTP_REFERER']);
        }
        
        $this->load->view('admin/news/manage', $data);
    }
    
    public function delete(){
        $this->load->model('admin/news_model');
        
        $result = $this->news_model->delete_news();
        
        if($result){
            $this->session->set_flashdata('action_message', 'News deleted successfully!');
            $this->session->set_flashdata('action_message_type', 'success');
        } else {
            $this->session->set_flashdata('action_message', 'Failed to delete news!');
            $this->session->set_flashdata('action_message_type', 'danger');
        }
        
        redirect('admin/news');
    }
    
    /**
     * Setup database table silently
     */
    private function setup_database_silent(){
        $this->load->dbforge();
        
        // Define fields for news table
        $fields = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'news_text' => array(
                'type' => 'TEXT',
                'null' => FALSE,
            ),
            'news_date' => array(
                'type' => 'DATE',
                'null' => FALSE,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('news', TRUE);
    }
    
    /**
     * Setup database table - run this once to create the news table
     */
    public function setup_database(){
        $this->load->dbforge();
        
        // Define fields for news table
        $fields = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'news_text' => array(
                'type' => 'TEXT',
                'null' => FALSE,
            ),
            'news_date' => array(
                'type' => 'DATE',
                'null' => FALSE,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        
        if($this->dbforge->create_table('news', TRUE)){
            $this->session->set_flashdata('action_message', 'News table created successfully!');
            $this->session->set_flashdata('action_message_type', 'success');
        } else {
            $this->session->set_flashdata('action_message', 'News table already exists or failed to create!');
            $this->session->set_flashdata('action_message_type', 'warning');
        }
        
        redirect('admin/news');
    }
}
