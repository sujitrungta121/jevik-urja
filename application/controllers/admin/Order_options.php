<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_options extends CI_Controller {

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
        $this->load->model('admin/order_options_model');

        // Check if option_value table exists, if not create it
        if (!$this->db->table_exists('option_value')) {
            $this->setup_database_silent();
        }

        // Get existing option values if any
        $option_values = $this->order_options_model->get_option_values();
        
        // Convert to format expected by Angular
        $angular_data = array();
        foreach ($option_values as $value) {
            $angular_data[] = array(
                'option_value_id' => $value->option_value_id,
                'value_name' => $value->value_name,
                'order' => $value->order
            );
        }
        
        $data['optionList'] = $angular_data;
        $this->load->view('admin/order_options/manage', $data);
    }

    public function update() {
        $this->load->model('admin/order_options_model');
        
        // Get the posted data from JSON input
        $post_data = json_decode(file_get_contents('php://input'), true);
        $option_value_ids = isset($post_data['option_value_id']) ? $post_data['option_value_id'] : null;
        $orders = isset($post_data['order']) ? $post_data['order'] : null;
        
        if (!empty($option_value_ids) && !empty($orders)) {
            $success = true;
            
            // Update each row's order
            foreach ($option_value_ids as $key => $id) {
                if (isset($orders[$key])) {
                    $data = array(
                        'order' => $orders[$key],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    
                    if (!$this->order_options_model->update_option($id, $data)) {
                        $success = false;
                    }
                }
            }
            
            if ($success) {
                    echo json_encode(array(
                    'status' => 'success',
                    'message' => 'Orders updated successfully!'
                ));
            } else {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Error updating orders!'
                ));
            }
        } else {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'No data received to update!'
            ));
        }
        
        return;
    }
    
    
    /**
     * Setup database table silently
     */
    // private function setup_database_silent(){
    //     $this->load->dbforge();
        
    //     // Define fields for news table
    //     $fields = array(
    //         'id' => array(
    //             'type' => 'INT',
    //             'constraint' => 11,
    //             'unsigned' => TRUE,
    //             'auto_increment' => TRUE
    //         ),
    //         'news_text' => array(
    //             'type' => 'TEXT',
    //             'null' => FALSE,
    //         ),
    //         'news_date' => array(
    //             'type' => 'DATE',
    //             'null' => FALSE,
    //         ),
    //         'created_at' => array(
    //             'type' => 'DATETIME',
    //             'null' => FALSE,
    //         ),
    //         'updated_at' => array(
    //             'type' => 'DATETIME',
    //             'null' => FALSE,
    //         ),
    //     );
        
    //     $this->dbforge->add_field($fields);
    //     $this->dbforge->add_key('id', TRUE);
    //     $this->dbforge->create_table('news', TRUE);
    // }
    
    // /**
    //  * Setup database table - run this once to create the news table
    //  */
    // public function setup_database(){
    //     $this->load->dbforge();
        
    //     // Define fields for news table
    //     $fields = array(
    //         'id' => array(
    //             'type' => 'INT',
    //             'constraint' => 11,
    //             'unsigned' => TRUE,
    //             'auto_increment' => TRUE
    //         ),
    //         'news_text' => array(
    //             'type' => 'TEXT',
    //             'null' => FALSE,
    //         ),
    //         'news_date' => array(
    //             'type' => 'DATE',
    //             'null' => FALSE,
    //         ),
    //         'created_at' => array(
    //             'type' => 'DATETIME',
    //             'null' => FALSE,
    //         ),
    //         'updated_at' => array(
    //             'type' => 'DATETIME',
    //             'null' => FALSE,
    //         ),
    //     );
        
    //     $this->dbforge->add_field($fields);
    //     $this->dbforge->add_key('id', TRUE);
        
    //     if($this->dbforge->create_table('news', TRUE)){
    //         $this->session->set_flashdata('action_message', 'News table created successfully!');
    //         $this->session->set_flashdata('action_message_type', 'success');
    //     } else {
    //         $this->session->set_flashdata('action_message', 'News table already exists or failed to create!');
    //         $this->session->set_flashdata('action_message_type', 'warning');
    //     }
        
    //     redirect('admin/news');
    // }
}
