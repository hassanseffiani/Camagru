<?php
    class Camera{
        private $db;
        public function __construct(){
            $this->db = new Database;
        }

        public function get_img($id){
                $this->db->query("SELECT img_dir as img, type as type,filter as filter, id FROM Posts WHERE user_id = :user_id ORDER BY created_at DESC");
                $this->db->bind('user_id', $id);
                $rows = $this->db->get_result();
                return $rows;
        }

        public function delete_all($id){
            $this->db->query("DELETE FROM Posts WHERE user_id = :user_id");
            $this->db->bind(':user_id', $id);
            if ($this->db->execute())
                return TRUE;
            else
                return FALSE;

        }
    }