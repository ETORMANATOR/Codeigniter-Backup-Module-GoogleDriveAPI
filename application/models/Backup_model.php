<?php
class Backup_model extends CI_Model
{
    
    public function check()
    {
        $this->db->select('admin_enable_backup,backup_type,backup_time,backup_path');
        $this->db->where('user_level', '1');
        $query = $this->db->get('autobackup');
        return $query->result();
    
    }
    public function updatecheck($data)
    {
        $this->db->where('user_level', '1');
        if($this->db->update('autobackup', $data)){
         return true;
        }else{
            return false;
        }
    }
}