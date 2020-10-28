<?php
    class User{
        private $db;
        public function __construct(){
            $this->db = new Database;
        }

        public function getUser(){
            $this->db->query('SELECT id as user_id FROM Users');
            $this->db->execute();
            $rows = $this->db->get_result();
            return $rows;
        }

        public function update_time($email){
            $this->db->query('UPDATE Users SET created_at = CURRENT_TIMESTAMP WHERE email = :email');
            $this->db->bind(':email', $email);
            if ($this->db->execute())
                return TRUE;
            else
                return FALSE;
        }

        public function register($data){
            $this->db->query('INSERT INTO Users(email, name, password, vkey)VALUES(:email,:name,:password,:vkey)');
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':password', $data['password']);
            $this->db->bind(':vkey', $data['vkey']);
            if ($this->db->execute())
                return TRUE;
            else
                return FALSE;
        }

        //login

        public function login($name, $password){
            $this->db->query('SELECT * FROM Users WHERE name = :name');
            $this->db->bind(':name', $name);
            $rows = $this->db->single();
            $hash_password = $rows->password;
            if (password_verify($password, $hash_password))
                return $rows;
            else
                return false;
        }

        public function Check_email($email){
            $this->db->query('SELECT * FROM Users WHERE email = :email');
            $this->db->bind(':email', $email);
            $rows = $this->db->single();
            if ($this->db->rows() > 0)
                return TRUE;
            else
                return FALSE;
        }

        public function Check_name($name){
            $this->db->query('SELECT * FROM Users WHERE name = :name');
            $this->db->bind(':name', $name);
            $rows = $this->db->single();
            if ($this->db->rows() > 0)
                return TRUE;
            else
                return FALSE;
        }

        public function Check_verify($name){
            $this->db->query('SELECT verify FROM Users WHERE name = :name');
            $this->db->bind(':name', $name);
            $rows = $this->db->single();
            if ($this->db->rows() > 0)
                return $rows;
            else
                return FALSE;
        }

        public function getUserbyid($id){
            $this->db->query('SELECT * FROM Users WHERE id = :id');
            $this->db->bind(':id', $id);
            $rows = $this->db->single();
            return $rows;
        }

        //edit

        public function edit($data){
            $this->db->query('UPDATE Users SET `name` = :name, `email` = :email, `password` = :password WHERE id = :id');
            $this->db->bind(":id", $data['user_id']);
            $this->db->bind(":name", $data['name']);
            $this->db->bind(":email", $data['email']);
            $this->db->bind(":password", $data['old_p']);
            if ($this->db->execute())
                return TRUE;
            else
                return FALSE;
        }

        public function edit_password_for_edit($data){
            $this->db->query('UPDATE Users SET `password` = :password WHERE `id` = :id');
            $this->db->bind(":id", $data['user_id']);
            $this->db->bind(":password", $data['new_p']);
            if ($this->db->execute())
                return TRUE;
            else
                return FALSE;
        }

        public function getUserbyinfo($id){
            $this->db->query('SELECT name as user_name,email as user_email FROM Users WHERE id = :id');
            $this->db->bind(':id', $id);
            $rows = $this->db->single();
            foreach($rows as $r =>$key)
                $data[] = $key;
            return $data;
        }

        public function Check_password($data){
            $this->db->query('SELECT * FROM Users WHERE id = :id');
            $this->db->bind(':id', $data['user_id']);
            $rows = $this->db->single();
            $hash_pass = $rows->password;
            if (password_verify($data['old_p'], $hash_pass))
                return TRUE;
            else
                return FALSE;
        }

        //forget
        public function Check_password1($data){
            $this->db->query('SELECT * FROM Users WHERE email = :email');
            $this->db->bind(':email', $data['email']);
            $rows = $this->db->single();
            $hash_pass = $rows->password;
            if (password_verify($data['new_p'], $hash_pass))
                return TRUE;
            else
                return FALSE;
        }

        //Check password with vkey
        public function Check_password2($data){
            $this->db->query('SELECT * FROM Users WHERE vkey = :vkey');
            $this->db->bind(':vkey', $data['vkey']);
            $rows = $this->db->single();
            $hash_pass = $rows->password;
            if (password_verify($data['old_p'], $hash_pass))
                return TRUE;
            else
                return FALSE;
        }

        // all stuff with vkey

        public function getUserTime($vkey){
            // $this->db->query('SELECT DATE_FORMAT(created_at, "%H:%i:%s") as `time` FROM Users WHERE vkey = :vkey');
            $this->db->query('SELECT created_at as `time` FROM Users WHERE vkey = :vkey');
            $this->db->bind(':vkey', $vkey);
            $this->db->execute();
            $rows = $this->db->single();
            return $rows;
        }

        public function already_verify($vkey){
            $this->db->query('SELECT * FROM Users WHERE vkey = :vkey AND verify = 1');
            $this->db->bind(':vkey', $vkey);
            $rows = $this->db->single();
            if ($this->db->rows() > 0)
                return TRUE;
            else
                return FALSE;
        }

        public function Get_vkey($email){
            $this->db->query('SELECT vkey FROM Users WHERE email = :email');
            $this->db->bind(':email', $email);
            $rows = $this->db->single();
            if ($this->db->rows() > 0)
                return $rows;
            else
                return FALSE;
        }

        //verify with vkey

        public function verify_account($vkey){
            $this->db->query('UPDATE Users SET `verify` = 1 WHERE vkey = :vkey');
            $this->db->bind(':vkey', $vkey);
            if ($this->db->execute())
                return TRUE;
            else
                return FALSE;
        }

        // verify forget with help of vkey

        public function verify_forget($data){
            $this->db->query('UPDATE Users SET `password` = :password WHERE vkey = :vkey AND verify = 1');
            $this->db->bind(':vkey', $data['vkey']);
            $this->db->bind(':password', $data['new_p']);
            if ($this->db->execute())
                return TRUE;
            else
                return FALSE;
        }

        //notify

        public function modelNotify($id){
            $this->db->query('UPDATE Users SET `notify` = 1 WHERE id = :id');
            $this->db->bind(':id', $id);
            if ($this->db->execute())
                return TRUE;
            else
                return FALSE;
        }

        public function modelNotify1($id){
            $this->db->query('UPDATE Users SET `notify` = 0 WHERE id = :id');
            $this->db->bind(':id', $id);
            if ($this->db->execute())
                return TRUE;
            else
                return FALSE;
        }

        //see notify column

        public function notifyResult($id){
            $this->db->query('SELECT notify FROM Users WHERE id = :id');
            $this->db->bind(':id', $id);
            $rows = $this->db->single();
            if ($this->db->rows() > 0)
                return $rows;
            else
                return FALSE;
        }
    }