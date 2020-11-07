<?php
    class Cameras extends Controller{
        public function __construct(){
            $this->cameraModel = $this->model('Camera');
            $this->postModel = $this->model('Post');
        }

        /// index Camera
        public function index(){
            if (is_login_in()){
                $img = $this->cameraModel->get_img($_SESSION['user_id']);
                    $data =[
                        'title' => 'Camera',
                        'user_id' => $_SESSION['user_id'],
                        'img' => $img,
                        'img_err' => '',
                        'is_in' => 0,
                        'arr' => get_all_stickers(),
                    ];
                    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                        if (isset($_FILES['file']))
                            $data['is_in'] = 1;
                        $name_file = $_FILES['file']['name'];
                        $imageFileType = strtolower(pathinfo($name_file,PATHINFO_EXTENSION));
                        $tmp_name = $_FILES['file']['tmp_name'];
                        $upload_directory = APPROOT1; //This is the folder which you created just now
                        if ($_FILES["file"]["size"] < 5 * 1048576){ //  1 * 1048576  == 1mb 
                            if (move_uploaded_file($tmp_name, $upload_directory.$name_file)){
                                $sendjs = base64_encode(file_get_contents($upload_directory.$name_file));
                                $b64 = merge_64($sendjs , trim($_POST['sticker64']));
                                $data1 = [
                                    'img_dir' => $b64,
                                    'type' => getimagesize($upload_directory.$name_file),
                                    'filter' => trim($_POST['filter']),
                                    'sendjs' => $sendjs
                                ];
                                if ($data1['type'] !== false){
                                    if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif"){
                                            $data = array_merge($data, $data1);
                                            if ($this->postModel->addPost($data))
                                                redirect('cameras');
                                    }
                                    else{
                                        $data['img_err'] = 'Sorry, there was an error uploading your file. type error.';
                                        $this->view('cameras/index', $data);
                                    }
                                }
                                else{
                                    $data['img_err'] = 'Sorry, there was an error uploading your file.';
                                    $this->view('cameras/index', $data);
                                }
                            }
                        }
                        else{
                            $data['img_err'] = 'Sorry, there was an error uploading your file. Size error.';
                            $this->view('cameras/index', $data);
                        }

                        if (!empty($_POST['img64'])){
                            $b64 = merge_64(trim($_POST['img64']), trim($_POST['sticker64']));
                            $data1 = [
                                'img_dir' => $b64,
                                'filter' => trim($_POST['filter'])
                            ];
                            $data = array_merge($data, $data1);
                            $this->postModel->addPost($data);
                            redirect('cameras');
                        }
                        else
                            redirect('cameras');
                    }else
                        $this->view('cameras/index', $data);
            }else
                redirect('posts');
        }

        /// Delete preview

        public function delete_preview($id){
            if (is_login_in()){
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $this->postModel->deletePost($id);
                    dlt_img_path();
                }else
                    redirect('cameras');
            }else{
                flash('login','You can\'t delete a preview if you are not login.');
                redirect('users/login');
            }
        }
    }