<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class Api extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Handphone_model', 'hp');
    }
    public function index_get()
    {
        $id = $this->get('id');
        if ($id === null) {
            $p = $this->get('page');
            $p = (empty($p) ? 1 : $p);
            $total_data = $this->hp->count();
            $total_page = ceil($total_data / 5);
            $start = ($p - 1) * 5;
            $list = $this->hp->get(null, 5, $start);
            if ($list) {
                $data = [
                    'status' => true,
                    'page' => $p,
                    'total_data' => $total_data,
                    'total_page' => $total_page,
                    'data' => $list
                ];
            } else {
                $data = [
                    'status' => false,
                    'masg' => 'Data Tidak Ditemukan'
                ];
            }
            $this->response([$data], RestController::HTTP_OK);
        } else {
            $data = $this->hp->get($id);
            if ($data) {
                $this->response(['status' => true, 'data' => $data], RestController::HTTP_OK);
            } else {
                $this->response(['status' => false, 'msg' => $id . 'tidak ditemukan'], RestController::HTTP_NOT_FOUND);
            }
        }
    }
}
