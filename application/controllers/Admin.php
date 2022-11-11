<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;
use PhpParser\Node\Expr\Cast\Double;
use PhpParser\Node\Stmt\Echo_;

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Handphone_model');
        $this->load->library('form_validation');
    }
    public function index()
    {
        $data['title'] = 'Dataset Admin';
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer');
    }
    public function profile()
    {
        $data['title'] = 'Dataset Admin';
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/profile', $data);
        $this->load->view('templates/footer');
    }
    public function table()
    {
        $data['title'] = 'Dataset Admin';
        $data['handphone'] = $this->Handphone_model->getAllHandphone();
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/table', $data);

        $this->load->view('templates/footer');
    }
    public function tambah()
    {
        $data['title'] = 'Dataset Admin';
        $this->form_validation->set_rules('nama', 'Nama Destinasi', 'required');
        $this->form_validation->set_rules('merk_hp', 'Lokasi Destinasi', 'required');
        $this->form_validation->set_rules('stok', 'Harga', 'required');
        if ($this->form_validation->run() == FALSE) {
            $data['user'] = $this->db->get_where('user', ['email' =>
            $this->session->userdata('email')])->row_array();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('admin/tambah', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Handphone_model->tambahDataHandphone();
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New data added successfully...</div>');
            redirect('admin/table');
        }
    }
    public function charts()
    {
        $data['title'] = 'Dataset Admin';
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/charts', $data);
        $this->load->view('templates/footer');
    }

    public function hapus($id)
    {
        $this->Handphone_model->hapusDataHp($id);
        $this->session->set_flashdata('deleted', '<div class="alert alert-warning" role="alert">Data Successfully Deleted..</div>');
        redirect('admin/table');
    }
    public function ubah($id)
    {
        $data['title'] = 'Dataset Admin';
        $data['handphone'] = $this->Handphone_model->getHandphoneById($id);
        $this->form_validation->set_rules('nama', 'Name', 'required');
        $this->form_validation->set_rules('merk_hp', 'Brand', 'required');
        $this->form_validation->set_rules('stok', 'Stock', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['user'] = $this->db->get_where('user', ['email' =>
            $this->session->userdata('email')])->row_array();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('admin/ubah', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Handphone_model->ubahDataHandphone();
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New data added successfully...</div>');
            redirect('admin/table');
        }
    }

    public function expdf()
    {
        $mpdf = new \Mpdf\Mpdf();
        $datahp = $this->Handphone_model->getAllHandphone();
        $data = $this->load->view('pdf/mpdf', ['semuahp' => $datahp], TRUE);
        $mpdf->WriteHTML($data);
        $mpdf->Output();


        $this->load->library('dompdf_gen');
        $data['handphone'] = $this->Handphone_model->getAllHandphone();

        $this->load->view('admin/laporan_pdf', $data, TRUE);
        $paper_size = 'A4';
        $orientation = 'landscape';
        $html = $this->output->get_output();
        $this->dompdf->set_paper($paper_size, $orientation);

        $this->dompdf->load_HTML($html);
        $this->dompdf->render();
        $this->dompdf->stream("laporan_produk.pdf", array('Attachment' => 0));
    }
    public function exexcel()
    {
        $data['title'] = 'Laporan Produk';
        $data['handphone'] = $this->Handphone_model->getAllHandphone();
        $this->load->view('admin/excel', $data);
    }
    public function data_api()
    {
        $data['title'] = 'Dataset Admin';
        $data['handphone'] = $this->Handphone_model->getAllHandphone();
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();
        $filedata = json_decode(file_get_contents('assets/datahp.json'));
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/index_get', $filedata);
        $this->load->view('templates/footer');
    }
}
