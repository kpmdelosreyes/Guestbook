<?php
class Guestbook extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->table = 't_board';
    }
    
    public function save($data)
    {
        $sql = $this->db->insert('t_board', $data);
        $id = $this->db->insert_id();
        if ($id > 0) {
            return $id;
        }
        return false;
    }
    
    public function update($data, $id)
    {
        $this->db->where('id', $id);
        $result = $this->db->update($this->table, $data); 
        if ($result) {
            return true;
        }
        return false;
    }
    
    public function delete($data)
    {
        $id = implode(',', $data);
        $sql = "DELETE FROM {$this->table} WHERE id IN({$id})";
        
        $result = $this->db->query($sql);
        
        if ($result) {
            return true;
        }
        return false;
    }
    
    public function get_data($id = null, $aOption=null)
    {
        $where = null;
        
        if ($id != null) {
            $where = "WHERE id = '{$id}'";
        } 
        if ($aOption['search_type'] != null) {
            $where = "WHERE {$aOption[search_type]} like '%{$aOption[search_query]}%'";
        } 
        
        $sql = "SELECT * FROM {$this->table} {$where} ORDER BY registerTime DESC ";
        if($aOption['limit'] != null){
            $sql .= "LIMIT {$aOption[offset]},{$aOption[limit]}";
        }
        $query = $this->db->query($sql);
        
        return $query->result_array();
    }
    
    public function get_data_search($data)
    {
        $where = null;
        
        if ($data != null) {
            $where = "WHERE {$data['search_type']} like '%{$data['search_query']}%'";
        } 
        
        $sql = "SELECT * FROM {$this->table} {$where} ORDER BY registerTime DESC";
        $query = $this->db->query($sql);
        
        return $query->result_array();
    }
    public function getResultCount()
    {
        return $this->db->count_all($this->table);
    }
}
