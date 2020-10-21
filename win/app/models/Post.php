<?php
    class Post{
        private $db;
        public function __construct(){
            $this->db = new Database;
        }

        public function CountPost(){
            $this->db->query('SELECT * FROM Posts');
            $this->db->execute();
            $rows = $this->db->rows();
            return $rows;
        }

        public function getPost($data){
            $this->db->query('SELECT Posts.id as id, Users.id as user_id, Users.name as user_name,img_dir,type,filter,Posts.created_at, (select COUNT(*) from Likes where Likes.post_id = Posts.id) as `cnt_like` , (select COUNT(*) from Comment where Comment.post_id = Posts.id) as `cnt_comm` FROM Posts INNER JOIN Users on Posts.user_id = Users.id ORDER BY Posts.created_at DESC LIMIT :start, :rpp');
            $this->db->bind(':start', $data['start']);
            $this->db->bind(':rpp', $data['rpp']);
            $this->db->execute();
            $rows = $this->db->get_result();
            return $rows;
        }
        //Count rows
        public function Count_rows(){
            $this->db->query('SELECT Posts.id as id, Users.id as user_id, Users.name as user_name,img_dir,type,filter,Posts.created_at, (select COUNT(*) from Likes where Likes.post_id = Posts.id) as `cnt_like` , (select COUNT(*) from Comment where Comment.post_id = Posts.id) as `cnt_comm` FROM Posts INNER JOIN Users on Posts.user_id = Users.id ORDER BY Posts.created_at DESC');
            $this->db->execute();
            $rows = $this->db->rows();
            return $rows;
        }

        public function addPost($data){
            $this->db->query('INSERT INTO Posts(user_id, img_dir, type, filter)VALUES(:user_id, :img_dir, :type, :filter)');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':img_dir', $data['img_dir']);
            if (!empty($data['type']))
                $this->db->bind(':type', $data['type']['mime']);
            else
                $this->db->bind(':type', 'image/png');
            if (!empty($data['filter']))
                $this->db->bind(':filter', $data['filter']);
            else
                $this->db->bind(':filter', $data['filter']);
            if ($this->db->execute())
                return TRUE;
            else
                return FALSE;
        }

        public function getPostbyid($id){
            $this->db->query('SELECT * FROM Posts WHERE id = :id ORDER BY created_at DESC');
            $this->db->bind(':id', $id);
            $rows = $this->db->single();
            return $rows;
        }

        public function getUserbyPostid($id){
            $this->db->query('SELECT name FROM users INNER JOIN posts on users.id = posts.user_id WHERE posts.id = :id');
            $this->db->bind(':id', $id);
            $rows = $this->db->single();
            return $rows;
        }
        
        public function deletePost($id){
            $this->db->query('DELETE FROM Posts WHERE id = :id AND user_id = :user_id');
            $this->db->bind(':id', $id);
            $this->db->bind(':user_id', $_SESSION['user_id']);
            if ($this->db->execute())
                return TRUE;
            else
                return FALSE;
        }

        public function get_like($id){
            $this->db->query('SELECT * FROM Likes WHERE post_id = :id AND user_id = :user_id ORDER BY created_at DESC');
            $this->db->bind(':id', $id);
            $this->db->bind(':user_id', $_SESSION['user_id']);
            $rows = $this->db->single();
            return $rows;
        }

        public function get_like_all($id){
            $this->db->query('SELECT *,Users.name as user_name FROM Likes,Users WHERE post_id = :id AND Likes.user_id = Users.id ORDER BY Likes.created_at DESC');
            $this->db->bind(':id', $id);
            $rows = $this->db->get_result();
            return $rows;
        }

        public function sql_like($data){
            $this->db->query('INSERT INTO Likes(post_id ,user_id ,`like`)VALUES(:post_id, :user_id, :like)');
            $this->db->bind(':post_id', $data['post']->id);
            $this->db->bind(':user_id', $data['user']->id);
            $this->db->bind(':like', 1);
            if ($this->db->execute())
                return TRUE;
            else
                return FALSE;
        }

        public function delete_like($data){
            $this->db->query('DELETE FROM Likes WHERE post_id = :post_id AND user_id = :user_id');
            $this->db->bind(':post_id', $data['post']->id);
            $this->db->bind(':user_id', $data['user']->id);
            if ($this->db->execute())
                return TRUE;
            else
                return FALSE;
        }

        public function sql_comment($data){
            $this->db->query('INSERT INTO Comment(post_id ,user_id , comment)VALUES(:post_id, :user_id, :comment)');
            $this->db->bind(':post_id', $data['post']->id);
            $this->db->bind(':user_id', $data['user']->id);
            $this->db->bind(':comment', $data['comment']);
            if ($this->db->execute())
                return TRUE;
            else
                return FALSE;
        }

        public function get_comment($id){
            $this->db->query('SELECT *,Comment.id as cmt_id,Users.name as user_name FROM Comment,Users WHERE post_id = :id AND Comment.user_id = Users.id ORDER BY Comment.created_at DESC');
            $this->db->bind(':id', $id);
            $rows = $this->db->get_result();
            return $rows;
        }

        public function delete_comment_sql($id){
            $this->db->query('DELETE FROM Comment WHERE id = :id AND user_id = :user_id');
            
            $this->db->bind(':id', $id);
            $this->db->bind(':user_id', $_SESSION['user_id']);

            if ($this->db->execute())
                return TRUE;
            else
                return FALSE;
        }

        //count like

        public function count_like($id){
            $this->db->query("SELECT COUNT(*) as 'cnt' FROM Likes WHERE post_id = :id");
            $this->db->bind(":id", $id);
            $rows = $this->db->single();
            return $rows;
        }

        public function count_comment($id){
            $this->db->query("SELECT COUNT(*) as 'cnt' FROM Comment WHERE post_id = :id");
            $this->db->bind(":id", $id);
            $rows = $this->db->single();
            return $rows;
        }
    }
