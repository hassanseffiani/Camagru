<?php
    class Users extends Controller{
        public function __construct(){
            $this->userModel = $this->model('User');
        }

        public function index(){
            if (!is_login_in()){
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $vkey = md5(time(). trim($_POST['email']));
                    $data = [
                        'name' => trim($_POST['name']),
                        'email' => trim($_POST['email']),
                        'password' => trim($_POST['password']),
                        'confirm_password' => trim($_POST['confirm_password']),
                        'vkey' => $vkey,
                        'name_err' => '',
                        'email_err' => '',
                        'password_err' => '',
                        'confirm_password_err' => '',
                    ];
                    if (empty($data['email']))
                        $data['email_err'] = 'Enter a valid email';
                    else if (preg_match("/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/" ,$data['email']) == false)
                        $data['email_err'] = 'Enter a valid email';
                    else{
                        if ($this->userModel->Check_email($data['email']))
                            $data['email_err'] = 'Email is already token';
                    }
                    if (empty($data['name']))
                        $data['name_err'] = 'Enter a valid name';
                    else{
                        if ($this->userModel->Check_name($data['name']))
                            $data['name_err'] = 'Username is already token';
                    }
                    if (empty($data['password']))
                        $data['password_err'] = 'Enter a valid password';
                    else if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{6,})/" ,$data['password']) == false)
                        $data['password_err'] = 'Enter a valid password';
                    if (empty($data['confirm_password']))
                        $data['confirm_password_err'] = 'Please confirm password';
                    else{
                        if ($data['password'] != $data['confirm_password'])
                            $data['confirm_password_err'] = 'Password do not match';
                    }
                    if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                        if ($this->userModel->register($data)){
                            flash('register_success', 'You are registered and can log in');
                            // verify($data);
                            redirect('users/login');
                        }
                        else
                            die('Something is wrong');
                    }
                    else
                        $this->view('users/index', $data);
                }else{
                    $data = [
                        'name' => '',
                        'email' => '',
                        'password' => '',
                        'confirm_password' => '',
                        'name_err' => '',
                        'email_err' => '',
                        'password_err' => '',
                        'confirm_password_err' => '',
                    ];
                    $this->view('users/index', $data);
                }
            }else
                redirect('posts');
        }

        public function login_in($data){
            if (!empty($data)){
                $_SESSION['user_id'] = $data->id;
                $_SESSION['user_email'] = $data->email;
                $_SESSION['user_name'] = $data->name;
                redirect('posts/post');
            }
        }

        public function login(){
            if (!is_login_in()){
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $data = [
                        'Username' => trim($_POST['email']),
                        'password' => trim($_POST['password']),
                        'Username_err' => '',
                        'password_err' => '',
                    ];
                    if (empty($data['Username']))
                        $data['Username_err'] = 'Enter a valid Username';
                    if (empty($data['password']))
                        $data['password_err'] = 'Enter a valid password';
                    
                    if ($this->userModel->Check_name($data['Username']));
                    else
                        $data['Username_err'] = 'Username not found';
                    $user_login = $this->userModel->login($data['Username'], $data['password']);

                    
                    if (empty($data['Username_err']) && empty($data['password_err'])){
                        if ($user_login){
                            $this->login_in($user_login);
                        }
                        else{
                            $data['password_err'] = 'Password incorrect';
                            $this->view('users/login', $data);
                        }

                    }
                    else
                        $this->view('users/login', $data);
                }else{
                    $data = [
                        'Username' => '',
                        'password' => '',
                        'Username_err' => '',
                        'password_err' => '',
                    ];
                    $this->view('users/login', $data);
                }
            }else
                redirect('posts');
        }

        public function logout(){
            $_SESSION['user_id'] = '';
            $_SESSION['user_email'] = '';
            $_SESSION['user_name'] = '';
            session_destroy();
            redirect('users/login');
        }

        public function edit(){
            if (is_login_in()){
                $info = $this->userModel->getUserbyinfo($_SESSION['user_id']);
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $data = [
                        'user_id' => $_SESSION['user_id'],
                        'name' => htmlspecialchars(trim($_POST['name'])),
                        'email' => htmlspecialchars(trim($_POST['email'])),
                        'old_p' => htmlspecialchars(trim($_POST['old_p'])),
                        'name_err' => '',
                        'email_err' => '',
                        'old_p_err' => '',
                    ];
                    if (empty($data['name']))
                        $data['name'] = $info[1];
                    if (empty($data['email']))
                        $data['email'] = $info[1];
                        
                    if (empty($data['name']))
                        $data['name_err'] = "Enter a valid name";
                    if (empty($data['email']))
                        $data['email_err'] = "Enter a valid email";
                    if (empty($data['old_p']))
                        $data['old_p_err'] = "Enter a valid password";
                    else{
                        if (!$this->userModel->Check_password($data))
                            $data['old_p_err'] = "Enter your old password";
                    }
                    if (empty($data['name_err']) && empty($data['email_err']) && empty($data['old_p_err'])){
                        $data['old_p'] = password_hash($data['old_p'], PASSWORD_DEFAULT);
                        if ($this->userModel->edit($data))
                            $_SESSION['user_name'] = $data['name'];
                        redirect('posts');
                    }
                    $this->view('users/edit', $data);
                }else{
                    $info = $this->userModel->getUserbyinfo($_SESSION['user_id']);
                    $data = [
                        'user_id' => $_SESSION['user_id'],
                        'name' => '',
                        'email' => '',
                        'old_p' => '',
                        'new_p' => '',
                        'name_err' => '',
                        'email_err' => '',
                        'old_p_err' => '',
                        'new_p_err' => '',
                    ];
                    $data['name'] = $info[0];
                    $data['email'] = $info[1];
                
                    $this->view('users/edit', $data);
                }
            }else
            $this->view('users/login', $data);
        }

        public function edit_pass(){
            if (is_login_in()){
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $data = [
                        'user_id' => $_SESSION['user_id'],
                        'old_p' => trim($_POST['old_p']),
                        'new_p' => trim($_POST['new_p']),
                        'old_p_err' => '',
                        'new_p_err' => '',
                    ];
                    if (empty($data['old_p']))
                        $data['old_p_err'] = "Enter a valid password";
                    else{
                        if (!$this->userModel->Check_password($data))
                            $data['old_p_err'] = "Enter your old password";
                    }
                    if (empty($data['new_p']))
                        $data['new_p_err'] = "Enter a valid password";
                    else if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{6,})/" ,$data['new_p'],$match) == false)
                        $data['new_p_err'] = 'Enter a valid password';
                    else if ($data['new_p'] == $data['old_p'])
                        $data['new_p_err'] = "Enter another password";
                    if (empty($data['old_p_err']) && empty($data['new_p_err'])){
                        $data['new_p'] = password_hash($data['new_p'], PASSWORD_DEFAULT);
                        if ($this->userModel->edit_password_for_edit($data))
                            $_SESSION['user_name'] = $_SESSION['user_name'];
                        redirect('posts');
                    }
                    $this->view('users/edit_pass', $data);
                }else{
                    $data = [
                        'user_id' => $_SESSION['user_id'],
                        'old_p' => '',
                        'new_p' => '',
                        'old_p_err' => '',
                        'new_p_err' => '',
                    ];
                
                    $this->view('users/edit_pass', $data);
                }
            }else
            $this->view('users/login', $data);
        }


        public function forget(){
            if (!is_login_in()){
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $data = [
                        'email' => trim($_POST['email']),
                        'new_p' => trim($_POST['new_p']),
                        'con_p' => trim($_POST['con_p']),
                        'email_err' => '',
                        'new_p_err' => '',
                        'con_p_err' => '',
                    ];
                    if (empty($data['email']))
                        $data['email_err'] = "Enter a valid password";
                    if (empty($data['new_p']))
                        $data['new_p_err'] = "Enter a valid password";
                    else if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{6,})/" ,$data['new_p'],$match) == false)
                        $data['new_p_err'] = 'Enter a valid password';
                    else{
                        if ($this->userModel->Check_password1($data))
                            $data['new_p_err'] = "Enter another password";
                    }
                    if ($this->userModel->Check_email($data['email']));
                    else
                        $data['email_err'] = 'Email not found';
                    if (empty($data['con_p']))
                        $data['con_p_err'] = "Enter a valid password";
                    else if ($data['con_p'] != $data['new_p'])
                        $data['con_p_err'] = "Please confirm your password"; 
                    if (empty($data['email_err']) && empty($data['new_p_err']) && empty($data['con_p_err'])){
                        $data['new_p'] = password_hash($data['new_p'], PASSWORD_DEFAULT);
                        if ($this->userModel->edit_password($data)){
                            flash('edit_pass_success', 'Infos edited.');
                            redirect('users/login');
                        }
                    }
                    $this->view('users/forget', $data);
                }else{
                    $data = [
                        'email' => '',
                        'new_p' => '',
                        'con_p' => '',
                        'email_err' => '',
                        'new_p_err' => '',
                        'con_p_err' => '',
                    ];
                    $this->view('users/forget', $data);
                }
            }else
            redirect('posts/index');
        }      
    }