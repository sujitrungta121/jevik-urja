<?php
require APPPATH.'controllers/MainController.php';
defined('BASEPATH') OR exit('No direct script access allowed');
class Language extends MainController
{
    public function set()
    {
        $this->load->library("Session");
        $this->load->library("Security");
        $this->load->helper("url");
        $id = $this->security->xss_clean($this->uri->segment(3));

        if ($id == "tr") {
            $this->session->set_userdata('lang', '1');
            $this->session->set_userdata('lang_file', 'turkish');
        }
        if ($id == "en") {
            $this->session->set_userdata('lang', '2');
            $this->session->set_userdata('lang_file', 'english');
        }

        redirect($_SERVER['HTTP_REFERER']);
    }
}
