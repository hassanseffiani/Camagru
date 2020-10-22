<?php
    //two part to modify part sign in and login
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
                            $data['name_err'] = 'Name is already token';
                    }
                    if (empty($data['password']))
                        $data['password_err'] = 'Enter a valid password';
                    // else if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{6,})/" ,$data['password']) == false)
                    //     $data['password_err'] = 'Enter a valid password';
                    if (empty($data['confirm_password']))
                        $data['confirm_password_err'] = 'Please confirm password';
                    else{
                        if ($data['password'] != $data['confirm_password'])
                            $data['confirm_password_err'] = 'Password do not match';
                    }
                    if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                        if ($this->userModel->register($data)){
                            $message = "<html>
                            <head>
                              <title>Confirm Your account</title>
                            </head>
                            <body>
                                <p>
                                    Thanks for signing up!
                                    Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
                                
                                    ------------------------
                                    Username: ".$data['name']."
                                    Password: ".trim($_POST['password'])."
                                    ------------------------
                                    
                                    Please click this link to activate your account:
                                </p>
                                <a href='".URLROOT.'users/verify/'.$data['vkey']."'>Verify your account</a>
                            </body>
                            </html>";
                            verify($data['email'], $message);
                            redirect('users/login');
                        }
                        else
                            $this->view('users/index', $data);
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

        public function verify($vkey = 0){
            if (empty($vkey))
                redirect('posts');
            $info = $this->userModel->getUserTime($vkey);
            $data = [
                'vkey' => $vkey,
                'vkey_err' => '',
                // 'now' => date("H:i:s"),00:20:25
                // 'now' => date("2020-10-22 00:20:25"),
                'now' => date("Y-m-d H:i:s"),
                'time' => $info->time
            ];
            $toConvert = new DateTime($data['time']);
            $strDate = $toConvert->format('Y-m-d H:i:s');
            $date1 = date_create($date['now']);
            $date2 = date_create($strDate);
            $interval = date_diff($date2, $date1);
            if($interval->format('%h%d%m%y')=="0000")
                $min = $interval->format('%i');
            else if($interval->format('%d%m%y')=="000")
                $hh = $interval->format('%h');
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if (empty($data['vkey']))
                    $data['vkey_err'] = "NOT GOOD";
                if (!$this->userModel->already_verify($vkey)){
                    if (empty($data['vkey_err'])){
                        if ($hh == null){
                            if ($min <= 5){
                                if ($this->userModel->verify_account($data['vkey'])){
                                    flash('register_success', 'You are registered and can log in');
                                    redirect('users/login');
                                }
                            }else{
                                flash('verify_failed', 'You have expire time to verify your account. Please send a new email');
                                $this->view('users/send_N_email', $data);
                            }
                        }else{
                            flash('verify_failed', 'You have expire time to verify your account');
                            redirect('users/index');
                        }
                    }
                }else{
                    flash('register_success', 'You have already verify ur account');
                    redirect('users/login');
                }
                
            }else
                $this->view('users/verify', $data);
        }

        //send a new email confirmation
        //please modify created_at to cancell the tretment in verify function
        //to contunie tommorrow
        public function send_N_email(){
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $vkey = $this->userModel->Get_vkey(trim($_POST['email']));
                $data = [
                    'email' => trim($_POST['email']),
                    'vkey' => $vkey->vkey,
                    'email_err' => ''
                ];
                if (empty($data['email']))
                    $data['email_err'] = 'Enter a valid email';
                else if (preg_match("/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/" ,$data['email']) == false)
                    $data['email_err'] = 'Enter a valid email';
               
                if (empty($data['email_err'])){
                    if ($this->userModel->update_time($data['email'])){

                        $message = "<html>
                            <head>
                                <title>Confirm Your account</title>
                            </head>
                            <body>
                                <p>
                                    Thanks for signing up!
                                    Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
                                
                                    Please click this link to activate your account:
                                </p>
                                <a href='".URLROOT.'users/verify/'.$data['vkey']."'>Verify your account</a>
                            </body>
                            </html>";
                        verify($data['email'], $message);
                        redirect('users/login');
                    }
                }else
                    $this->view('users/send_N_email', $data);
            }else{
                $data = [
                    'email' => '',
                    'vkey' => '',
                    'email_err' => ''
                ];
                $this->view('users/send_N_email', $data);
            }
        }

        public function login_in($data){
            if (!empty($data)){
                $_SESSION['user_id'] = $data->id;
                $_SESSION['user_email'] = $data->email;
                $_SESSION['user_name'] = $data->name;
                $_SESSION['test'] = $data->verify;
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
                        'verify_err' => '',
                    ];
                    if (empty($data['Username']))
                        $data['Username_err'] = 'Enter a valid Username';
                    if (empty($data['password']))
                        $data['password_err'] = 'Enter a valid password';
                    //check name
                    $name = $this->userModel->Check_name($data['Username']);
                    if ($name);
                    else
                        $data['Username_err'] = 'Username not found';
                    //verify
                    $verify = $this->userModel->Check_verify($data['Username']);
                    if ((int)$verify->verify){
                        $user_login = $this->userModel->login($data['Username'], $data['password']);
                        if (empty($data['Username_err']) && empty($data['password_err'])){
                            if ($user_login)
                                $this->login_in($user_login);
                            else{
                                $data['password_err'] = 'Password incorrect';
                                $this->view('users/login', $data);
                            }
                        }
                        else
                            $this->view('users/login', $data);
                    }
                    else{
                        if ($name)
                            $data['verify_err'] = 'Please verify your account';
                        $this->view('users/login', $data);
                    }
                }else{
                    $data = [
                        'Username' => '',
                        'password' => '',
                        'Username_err' => '',
                        'password_err' => '',
                        'verify_err' => '',
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
                        'con_p' => trim($_POST['con_p']),
                        'old_p_err' => '',
                        'new_p_err' => '',
                        'con_p_err' => '',
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
                    if (empty($data['con_p']))
                        $data['con_p_err'] = "Confirm your password";
                    else if ($data['new_p'] != $data['con_p'])
                        $data['con_p_err'] = "Confirm your password";
                    if (empty($data['old_p_err']) && empty($data['new_p_err']) && empty($data['con_p_err'])){
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
                    $vkey = 
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
                    // if (empty($data['new_p']))
                    //     $data['new_p_err'] = "Enter a valid password";
                    // else if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{6,})/" ,$data['new_p'],$match) == false)
                    //     $data['new_p_err'] = 'Enter a valid password';
                    // else{
                    //     if ($this->userModel->Check_password1($data))
                    //         $data['new_p_err'] = "Enter another password";
                    // }
                    if ($this->userModel->Check_email($data['email']));
                    else
                        $data['email_err'] = 'Email not found';
                    // if (empty($data['con_p']))
                    //     $data['con_p_err'] = "Enter a valid password";
                    // else if ($data['con_p'] != $data['new_p'])
                    //     $data['con_p_err'] = "Please confirm your password"; 
                    if (empty($data['email_err']) && empty($data['new_p_err']) && empty($data['con_p_err'])){
                        $data['new_p'] = password_hash($data['new_p'], PASSWORD_DEFAULT);
                        // if ($this->userModel->edit_password($data)){
                            //verify email
                        // if ($this->useModel->verify_forget($data)){
                            // $message = '<html><body><a href="'.URLROOT.'users/verify_forget/'.$data['vkey'].'">Verify your account</a></body></html>';
                            // verify($data['email'], $message);
                            // flash('edit_pass_success', 'Infos edited.');
                            redirect('users/login');
                        // }
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
        
        public function verify_forget($vkey = 0){
            if (empty($vkey))
                redirect('posts');
            if (!is_login_in()){
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $data = [
                    'new_p' => trim($_POST['new_p']),
                    'con_p' => trim($_POST['con_p']),
                    'new_p_err' => '',
                    'con_p_err' => '',
                    'vkey' => $vkey,
                    'vkey_err' => ''
                    // 'time' => $time
                ];
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if (empty($data['vkey']))
                    $data['vkey_err'] = "NOT GOOD";
                // if (empty($data['vkey_err'])){
                    // $this->userModel->verify1($data['vkey']);
                    // flash('register_success', 'You are registered and can log in');
                    // redirect('users/login');
                // }
                }else{
                    $data = [
                        'new_p' => '',
                        'con_p' => '',
                        'new_p_err' => '',
                        'con_p_err' => '',
                    ];
                    $this->view('users/verify_forget', $data);
                }
                // $info = $this->userModel->getUserTime($vkey);
                // foreach($info as $time);
                
                // var_dump($data['time']);
                    
            }else
                redirect('posts');
        }
    }