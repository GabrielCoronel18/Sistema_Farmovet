<?php
    
    namespace Gabriel\SistemaFarmovet\controller;
    class FrontController {

        private $dir;
        private $controller;        
        private $url;

        public function __construct() {
          
        
         session_start();
            require_once __DIR__ . '/../helpers/auth.php';

                if (isset($_POST['logout'])) {
                     $this->logout();
                }
          
            if (isset($_REQUEST["url"]) && isset($_SESSION['usuario'])) {

                $this->url = $_REQUEST["url"];
                

            } 
            else {
                
                $this->url = "Login";
            }

            $this->dir = 'app/controller/';
            $this->controller = 'Controller.php';
         
            $this->getURL();

           
        }

        private function getURL() {
           
            $file = $this->dir . $this->url . $this->controller;

            if(file_exists($file)) {
                require_once($file);
                
                
            } else {
                
                echo 'error';
                exit();
            }
        }
        private function logout(){
            $_SESSION = [];
            session_destroy();
            header('Location: index.php?url=Login');
            exit();
        }
    }
   

?>
