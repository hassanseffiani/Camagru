<?php
    class Posts extends Controller{
        public function __construct(){
            $this->postModel = $this->model('Post');
            $this->userModel = $this->model('User');
            $this->cameraModel = $this->model('Camera');
        }

        /// Index For all POST

        public function index($page = 1){
            //How many record per page
            $rpp = 5;
            // if ($page) ? $nb = $page:$nb = 0;
            if ($page > 1) $start = ($page * $rpp) - $rpp;else $start = 0;
            $post = $this->postModel->getPost($start, $rpp);
            //numrows
            $p = $this->postModel->CountPost();

            $data = [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'title' => 'Posts',
                'post' => $post,
                'i' => $p / $rpp
            ];
            $this->view('posts/index', $data);
        }
        
        public function delete_all($id){
            $this->cameraModel->delete_all($id);
            $file = APPROOT1.'*';
            $files = glob($file); //get all file names
            foreach($files as $file){
                if(is_file($file))
                unlink($file); //delete file
            }
            flash('dlt_file','File delete succes');
            redirect('posts');
        }

        /// Show detail Post

        public function show($id){
            $post = $this->postModel->getPostbyid($id);
            $user = $this->userModel->getUserbyid($post->user_id);
            $data = [
                'user' => $user,
                'post' => $post
            ];
            $this->view('posts/show', $data);
        }

        public function delete($id){
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $post = $this->userModel->getUserbyid($id);
                if ($post->user_id != $_SESSION['id'])
                    redirect('posts');
                if (is_login_in()){
                    if ($this->postModel->deletePost($id)){
                        dlt_img_path();
                        redirect('posts');
                    }
                }else{
                    flash('dlt_msg','You can\'t delete if you are not login.');
                    redirect('posts');
                }
            }else{
                redirect('posts');
            }
        }
        //// likes

        public function like($id){
            $like = $this->postModel->get_like_all($id);
            $post = $this->postModel->getPostbyid($id);
            $user = $this->userModel->getUserbyid($_SESSION['user_id']);
            $data = [
                'user' => $user,
                'post' => $post,
                'like' => $like,
            ];
            $this->view('posts/like', $data);
        }

        public function add_like($id){
            if (is_login_in()){
                $like = $this->postModel->get_like($id);
                $post = $this->postModel->getPostbyid($id);
                $user = $this->userModel->getUserbyid($_SESSION['user_id']);
                $data = [
                    'user' => $user,
                    'post' => $post,
                ];
                if (!$like){
                    $this->postModel->sql_like($data);
                    echo 1;
                }
                else{
                    $this->postModel->delete_like($data);
                    echo -1;
                }
            }
        }

        //// Comments

        public function comment($id){
            $post = $this->postModel->getPostbyid($id);
            $comment = $this->postModel->get_comment($id);
            $user = $this->userModel->getUserbyid($_SESSION['user_id']);
            $data = [
                'user' => $user,
                'post' => $post,
                'list' => $comment
            ];
            $_SESSION['post_id'] = $id;
            $this->view('posts/comment', $data);
        }

        public function add_comment($id){
            if (is_login_in()){
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $post = $this->postModel->getPostbyid($id);
                    $user = $this->userModel->getUserbyid($_SESSION['user_id']);
                    $data = [
                        'user' => $user,
                        'post' => $post,
                        'comment' => trim($_POST['comment']),
                        'comment_err' => ''
                    ];
                    if (empty($data['comment']))
                        $data['comment_err'] = "Write something for this post.";
                    if (empty($data['comment_err']))
                        if ($this->postModel->sql_comment($data))
                            echo 1;
                    }
            }
        }

        public function delete_comment($id){
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $this->postModel->delete_comment_sql($id);
                $cnt_cmt = $this->postModel->search_comment_sql($id);
                echo $cnt_cmt;
                // redirect('posts/like_comment/'.$_SESSION['post_id']);
                $_SESSION['post_id'] = "";
            }
        }

        // like comment

        public function like_comment($id){
            $cnt_like = $this->postModel->count_like($id);
            $cnt_comment = $this->postModel->count_comment($id);
            $like = $this->postModel->get_like_all($id);
            $post = $this->postModel->getPostbyid($id);
            $user = $this->postModel->getUserbyPostid($id);
            $comment = $this->postModel->get_comment($id);
            $data = [
                'user' => $user,
                'post' => $post,
                'like' => $like,
                'list' => $comment,
                'cnt_like' => $cnt_like,
                'cnt_comment' => $cnt_comment
            ];
            // var_dump($data);
            $_SESSION['post_id'] = $id;
            $this->view('posts/like_comment', $data);
        }
    }
?>