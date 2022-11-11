<?php
class Handphone_model extends CI_Model
{
    public function getAllHandphone()
    {
        return $query = $this->db->get('handphone')->result_array();
    }
    public function tambahDataHandphone()
    {
        $data = [
            "nama_hp" => $this->input->post('nama', true),
            "merk_hp" => $this->input->post('merk_hp', true),
            "stok" => $this->input->post('stok', true)
        ];
        $this->db->insert('handphone', $data);
    }
    public function hapusDataHp($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('handphone');
    }
    public function getHandphoneById($id)
    {
        return $this->db->get_where('handphone', ['id' => $id])->row_array();
    }
    public function ubahDataHandphone()
    {
        $data = [
            "nama_hp" => $this->input->post('nama', true),
            "merk_hp" => $this->input->post('merk_hp', true),
            "stok" => $this->input->post('stok', true)
        ];
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('handphone', $data);
    }
    public function get($id = null, $limit = 5, $offset = 0)
    {
        if ($id === null) {
            return $this->db->get('handphone', $limit, $offset)->result();
        } else {
            return $this->db->get_where('handphone', ['id' => $id])->result_array();
        }
    }
    public function count()
    {
        return $this->db->get('handphone')->num_rows();
    }
}
