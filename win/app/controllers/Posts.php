<?php
    class Posts extends Controller{
        public function __construct(){
            $this->postModel = $this->model('Post');
            $this->userModel = $this->model('User');
            $this->cameraModel = $this->model('Camera');
        }

        /// Index For all POST

        public function index($page = 0){
            $rpp = 5;
            isset($page) ? $page = $page : $page = 0;
            if ($page > 1)
                $start = ($page * $rpp) - $rpp;
            else
                $start = 0;

            //Query db for TOTAL records
            $row = $this->postModel->Count_rows();

            //GET total records
            $numRows = $row;

            //Get total number of pages
            // if total page 5.5 = page 6 if 5 page 5
            $totalPages = $numRows / $rpp;
            if (is_float($totalPages))
                $totalPages = floor($totalPages + 1);
            $data1 = [
                'start' => $start,
                'rpp' => $rpp
            ];
            $post = $this->postModel->getPost($data1);
            foreach($post as $p)
                $i++;
            $data = [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'title' => 'Posts',
                'post' => $post,
                'i' => $i,
                'total_page' => $totalPages
            ];
            $this->view('posts/index', $data);
        }

        // dlt a post

        public function delete($id = 0){
            if (is_login_in()){
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $post = $this->userModel->getUserbyid($id);
                    if ($post->user_id != $_SESSION['id'])
                        redirect('users/login');
                    if (is_login_in()){
                        if ($this->postModel->deletePost($id)){
                            dlt_img_path();
                            redirect('posts');
                        }
                    }
                }else
                    redirect("posts");
            }else{
                    flash('dlt_msg','You can\'t delete if you are not login.');
                    redirect('users/login');
            }
        }

        //// likes

        public function add_like($id = 0){
            if (is_login_in()){
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
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
                }else
                    redirect("posts");
            }else{
                flash('login','You can\'t like if you are not login.');
                redirect('users/login');
            }
        }

        //// Comments

        public function add_comment($id = 0){
            if (is_login_in()){
                    // echo 1;
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $post = $this->postModel->getPostbyid($id);
                    $user = $this->userModel->getUserbyid($_SESSION['user_id']);
                    $userPost = $this->postModel->getUserEmailbyPostid($id);
                    $n = $this->userModel->notifyResult($_SESSION['user_id']);
                    $data = [
                        'user' => $user,
                        'post' => $post,
                        'comment' => htmlspecialchars(trim($_POST['comment'])),
                        'comment_err' => ''
                    ];
                    if (empty($data['comment']))
                        $data['comment_err'] = "Write something for this post.";
                    else if (strlen($data["comment"]) >= 100)
                        $data['comment_err'] = "Write something for this post.";
                    if (empty($data['comment_err'])){
                        if ($this->postModel->sql_comment($data)){
                            $message = "<html>
                            <head>
                                <title>Notif like</title>
                            </head>
                            <body>
                                <p>
                                    You just receive a comment
                                </p>
                            </body>
                            </html>";
                            if ($n->notify === "0")
                                verify($userPost->email, $message);
                        }
                    }
                }else
                    redirect("posts");
            }else{
                flash('login','You can\'t comment if you are not login.');
                redirect('users/login');
            }
        }

        public function delete_comment($id = 0){
            if (is_login_in()){
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $this->postModel->delete_comment_sql($id);
                    redirect('posts/like_comment/'.$_SESSION['post_id']);
                    $_SESSION['post_id'] = "";
                    echo 1;
                }else
                    redirect("posts");
            }else{
                flash('login','You can\'t delete a comment if you are not login.');
                redirect('users/login');
            }
        }

        // like comment

        public function like_comment($id){
            $cnt_like = $this->postModel->count_like($id);
            $cnt_comment = $this->postModel->count_comment($id);
            $like = $this->postModel->get_like_all($id);
            $post = $this->postModel->getPostbyid($id);
            $userPost = $this->postModel->getUserNamebyPostid($id);
            $comment = $this->postModel->get_comment($id);
            $data = [
                'user' => $userPost,
                'post' => $post,
                'like' => $like,
                'list' => $comment,
                'cnt_like' => $cnt_like,
                'cnt_comment' => $cnt_comment,
            ];
            $_SESSION['post_id'] = $id;
            $this->view('posts/like_comment', $data);
        }
    }
?>