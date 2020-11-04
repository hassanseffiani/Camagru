<?php
    class Users extends Controller{
        public function __construct(){
            $this->userModel = $this->model('User');
        }

        //Sign up || or create user

        public function index(){
            if (!is_login_in()){
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $vkey = md5(time(). trim($_POST['email']));
                    $data = [
                        'name' => htmlspecialchars(trim($_POST['name'])),
                        'email' => htmlspecialchars(trim($_POST['email'])),
                        'password' => htmlspecialchars(trim($_POST['password'])),
                        'confirm_password' => htmlspecialchars(trim($_POST['confirm_password'])),
                        'vkey' => $vkey,
                        'name_err' => '',
                        'email_err' => '',
                        'password_err' => '',
                        'confirm_password_err' => '',
                    ];
                    if (empty($data['email']))
                        $data['email_err'] = 'Enter a valid email';
                    else if ($this->userModel->Check_email($data['email']))
                        $data['email_err'] = 'Email not found';
                    else if (preg_match("/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/" ,$data['email']) == false)
                        $data['email_err'] = 'Enter a valid email';
                    if (empty($data['name']))
                        $data['name_err'] = 'Enter a valid name';
                    else if (strlen($data['name']) <= 2)
                        $data['name_err'] = 'Enter a name have more than 3 caractere.';
                    else{
                        if ($this->userModel->Check_name($data['name']))
                            $data['name_err'] = 'Name is already token';
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

        //to verify account just created

        public function verify($vkey = 0){
            if (empty($vkey))
                redirect('posts');
            $info = $this->userModel->getUserTime($vkey);
            $data = [
                'vkey' => $vkey,
                'vkey_err' => '',
                'now' => date("Y-m-d H:i:s"),
                'time' => $info->time
            ];
            $toConvert = new DateTime($data['time']);
            $strDate = $toConvert->format('Y-m-d H:i:s');
            $interval = date_diff(date_create($strDate), date_create($data['now']));
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
                            $this->view('users/send_N_email', $data);
                        }
                    }
                }else{
                    flash('register_success', 'You have already verify ur account');
                    redirect('users/login');
                }
                
            }else
                $this->view('users/verify', $data);
        }

        //send a new email confirmation if this is timeout for verification

        public function send_N_email(){
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $vkey = $this->userModel->Get_vkey(htmlspecialchars(trim($_POST['email'])));
                $data = [
                    'email' => htmlspecialchars(trim($_POST['email'])),
                    'vkey' => $vkey->vkey,
                    'email_err' => ''
                ];
                if (empty($data['email']))
                    $data['email_err'] = 'Enter a valid email';
                else if (!$this->userModel->Check_email($data['email']))
                    $data['email_err'] = 'Email not found';
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
                        flash('send_N_mail', 'Check your inbox for the new verification');
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

        ///forget
        // if user forget his password

        public function forget(){
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $vkey = $this->userModel->Get_vkey(htmlspecialchars(trim($_POST['email'])));
                $data = [
                    'email' => htmlspecialchars(trim($_POST['email'])),
                    'vkey' => $vkey->vkey,
                    'email_err' => ''
                ];
                if (empty($data['email']))
                    $data['email_err'] = 'Enter a valid email';
                else if (!$this->userModel->Check_email($data['email']))
                    $data['email_err'] = 'Email not found';
                else if (preg_match("/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/" ,$data['email']) == false)
                    $data['email_err'] = 'Enter a valid email';
                
                if (empty($data['email_err'])){
                    if ($this->userModel->update_time($data['email'])){
                        $message = "<html>
                            <head>
                                <title>Change password</title>
                            </head>
                            <body>
                                <p>
                                    Please click this link to change your passwrod account:
                                </p>
                                <a href='".URLROOT.'users/verify_forget/'.$data['vkey']."'>Cange your password</a>
                            </body>
                            </html>";
                        verify($data['email'], $message);
                        flash('verify_forget', 'Your password is changed success.');
                        redirect('users/login');
                    }
                }else
                    $this->view('users/forget', $data);
            }else
                $this->view('users/forget', $data);
        }

        // Change forget password

        public function verify_forget($vkey = 0){
            if (empty($vkey))
                redirect('posts');
            $info = $this->userModel->getUserTime($vkey);
            $data1 = [
                'now' => date("Y-m-d H:i:s"),
                'time' => $info->time
            ];
            $toConvert = new DateTime($data1['time']);
            $strDate = $toConvert->format('Y-m-d H:i:s');
            $interval = date_diff(date_create($strDate), date_create($data1['now']));
            if($interval->format('%h%d%m%y')=="0000")
                $min = $interval->format('%i');
            else if($interval->format('%d%m%y')=="000")
                $hh = $interval->format('%h');
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = [
                    'vkey' => $vkey,
                    'old_p' => htmlspecialchars(trim($_POST['old_p'])),
                    'new_p' => htmlspecialchars(trim($_POST['new_p'])),
                    'con_p' => htmlspecialchars(trim($_POST['con_p'])),
                    'vkey_err' => '',
                    'old_p_err' => '',
                    'new_p_err' => '',
                    'con_p_err' => '',
                ];
                if (empty($data['old_p']))
                    $data['old_p_err'] = "Enter a valid password";
                else{
                    if (!$this->userModel->Check_password2($data))
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
                    if ($hh == null){
                        if ($min <= 5){
                            if ($this->userModel->verify_forget($data)){
                                flash('register_success', 'Your password has changed correctly');
                                redirect('users/login');
                            }
                        }else{
                            flash('pass_err', 'You have expire time to modify your password your account. Please send a new email');
                            $this->view('users/forget', $data);
                        }
                    }else{
                        flash('pass_err', 'You have expire time to modify your password your account. Please send a new email');
                        $this->view('users/forget', $data);
                    }
                }else
                    $this->view('users/verify_forget', $data);
            }
            else
                $this->view('users/verify_forget', $data);
        }

        // Save all necessary session

        public function login_in($data){
            if (!empty($data)){
                $_SESSION['user_id'] = $data->id;
                $_SESSION['user_email'] = $data->email;
                $_SESSION['user_name'] = $data->name;
                $_SESSION['test'] = $data->verify;
                redirect('posts/post');
            }
        }

        // login

        public function login(){
            if (!is_login_in()){
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $data = [
                        'Username' => htmlspecialchars(trim($_POST['email'])),
                        'password' => htmlspecialchars(trim($_POST['password'])),
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
                    $user_login = $this->userModel->login($data['Username'], $data['password']);
                    if (empty($data['Username_err']) && empty($data['password_err'])){
                        if (!(int)$verify->verify)
                            $data['verify_err'] = 'Please verify your account';
                        if ($user_login)
                            if (empty($data['verify_err']))
                                $this->login_in($user_login);
                        if ($user_login);
                        else{
                            $data['password_err'] = 'Password incorrect';
                            $this->view('users/login', $data);
                        }
                    }
                    else
                        $this->view('users/login', $data);
                    $this->view('users/login', $data);
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

        //edit user profil

        public function edit(){
            if (is_login_in()){
                $info = $this->userModel->getUserbyinfo($_SESSION['user_id']);
                $n = $this->userModel->notifyResult($_SESSION['user_id']);
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
                        'notify' => $n->notify
                    ];
                        
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
                    $n = $this->userModel->notifyResult($_SESSION['user_id']);
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
                        'notify' => $n->notify
                    ];
                    $data['name'] = $info[0];
                    $data['email'] = $info[1];
                
                    $this->view('users/edit', $data);
                }

            }else
            $this->view('users/login', $data);
        }

        // Edit password user log in
        
        public function edit_pass(){
            if (is_login_in()){
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $data = [
                        'user_id' => $_SESSION['user_id'],
                        'old_p' => htmlspecialchars(trim($_POST['old_p'])),
                        'new_p' => htmlspecialchars(trim($_POST['new_p'])),
                        'con_p' => htmlspecialchars(trim($_POST['con_p'])),
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

        //logout

        public function logout(){
            $_SESSION['user_id'] = '';
            $_SESSION['user_email'] = '';
            $_SESSION['user_name'] = '';
            session_destroy();
            redirect('users/login');
        }

        //notify

        public function notify(){
            if (is_login_in()){
                $n = $this->userModel->notifyResult($_SESSION['user_id']);
                if ($n->notify === '0'){
                    $this->userModel->modelNotify($_SESSION['user_id']);
                    redirect('users/edit');
                }else{
                    $this->userModel->modelNotify1($_SESSION['user_id']);
                    redirect('users/edit');
                }
            }else
                $this->view('users/login', $data);
        }
    }