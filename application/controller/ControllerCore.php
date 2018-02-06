<?php
namespace Application\Controller;

use Application\Library\Library;
use stdClass;

require_once(serverPath('/library/Library.php'));

class ControllerCore
{
    public $post,
           $view,
           $lib,
           $sql,
           $host;
    
    public function __construct(){
        $this->lib      = new Library();
        if(!isset($_SESSION['flashMessage'])){
            $_SESSION['flashMessage']    = array();
        }
        if(empty($this->post) || $this->post == null){
            $this->post = array();
        }
        if(!empty($_POST)){
            $this->setPost();
        }
        $this->view     = new stdClass();        
        $this->host     = host();
    }
    
    /**
     * Sanatizes posted data
     *
     * @param   Array
     * @author  Linden && sbebbington
     * @date    7 Oct 2016 14:54:10
     * @version 0.1.5-RC2
     * @return  void
     */
    public function setPost(){
        foreach($_POST as $key => $data){
            $this->post[$key]   = is_string($data) ? trim($data): $data;
        }
    }
    
    /**
     * This should empty the super global $_POST and the controller $this->post
     *
     * @param   na
     * @author  sbebbington
     * @date    16 Jun 2016 11:25:04
     * @version 0.1.5-RC2
     * @return  array
     */
    public function emptyPost(){
        $_POST      = array();
        $this->post = $_POST;
    }
    
    /**
     * Clears PHP session cookies
     *
     * @param   na
     * @author  sbebbington
     * @date    14 Sep 2016 14:29:23
     * @version 0.1.5-RC2
     * @return
     */
    public function emptySession(){
        if(session_id() != ""){
            session_destroy();
        }    
        $this->session  = null;
    }
    
    /**
     * Sets flash messages (recommend using string for value param)
     *
     * @param   string, string | int | boolean
     * @author  sbebbington
     * @date    14 Sep 2016 09:48:53
     * @version 0.1.5-RC2
     * @return  void
     */
    public function setFlashMessage($key, $value){
        $_SESSION['flashMessage'][$key] = $value;
    }
}
