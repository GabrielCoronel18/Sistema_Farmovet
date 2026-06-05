<?php
    
    namespace Gabriel\SistemaFarmovet\controller;
    class FrontController {

        private $dir;
        private $controller;        
        private $url;

        public function __construct() {

            
            if (isset($_REQUEST["url"])) {
                $this->url = $_REQUEST["url"];
            } else {
                
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
                
                header("Location: ?url=Login");
                exit();
            }
        }

    }

?>
