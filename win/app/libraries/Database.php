<?php
    class Database
    {
        private $user = DB_USER;
        private $dbname = DB_NAME;
        private $password = DB_PASS;
        private $host = DB_HOST;

        private $pdo;
        private $stmp;
        private $error;
        public function __construct(){
            $dsn = "mysql:host=$this->host; dbname=$this->dbname";
            // $option = array(
            //     PDO::ATTR_PERSISTENT => true,
            //     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            // );
            try{
                $this->pdo = new PDO($dsn, $this->user, $this->password);
            }catch(PDOException $e){
                $this->error = $e->getMessage();
                echo $this->error;
            }
        }

        public function query($sql){
            $this->stmp = $this->pdo->prepare($sql);
        }
        
        public function bind($name, $value, $type = null){
            if (is_null($type)){
                switch(true){
                    case is_int($value):
                        $type = PDO::PARAM_INT;
                    break;
                    case is_bool($value):
                        $type = PDO::PARAM_BOOL;
                    break;
                    case is_null($value):
                        $type = PDO::PARAM_NULL;
                    break;
                    default:
                        $type = PDO::PARAM_STR;
                }
            }

            $this->stmp->bindValue($name, $value, $type);
        }

        public function execute(){
            return $this->stmp->execute();
        }

        public function get_result(){
            $this->execute();    
            return $this->stmp->fetchAll(PDO::FETCH_OBJ);
        }

        public function single(){
            $this->execute();    
            return $this->stmp->fetch(PDO::FETCH_OBJ);
        }

        public function rows(){
            return $this->stmp->rowCount();
        }
    }