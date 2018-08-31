<?php
namespace Application\Controller;

if (! defined('FRAMEWORKPHP') || FRAMEWORKPHP != 65535) {
    require_once ("../view/403.phtml");
}

use Application\Library\Library;
if (! defined('FRAMEWORKPHP') || FRAMEWORKPHP != 65535) {
    require_once ("../view/403.phtml");
}

use stdClass;
require_once (serverPath('/library/Library.php'));

class ControllerCore
{

    public $post = [];

    public $view;

    public $lib;

    public $sql;

    public $host;

    public function __construct()
    {
        $this->lib = new Library();
        if (! isset($_SESSION[FLASHMESSAGE])) {
            $_SESSION[FLASHMESSAGE] = array();
        }
        if (! empty($_POST)) {
            $this->setPost();
        }
        $this->view = new stdClass();
        $this->host = host();
    }

    /**
     * Sanatizes posted data
     *
     * @author Linden && sbebbington
     * @date 7 Oct 2016 14:54:10
     * @version 1.0.0-RC1
     * @return void
     */
    public function setPost(): void
    {
        foreach ($_POST as $key => $data) {
            $this->post[$key] = is_string($data) ? trim($data) : $data;
        }
    }

    /**
     * This should empty the super global $_POST and the controller $this->post
     *
     * @author sbebbington
     * @date 16 Jun 2016 11:25:04
     * @version 1.0.0-RC1
     * @return void
     */
    public function emptyPost(): void
    {
        $_POST = array();
        $this->post = $_POST;
    }

    /**
     * Clears PHP session cookies
     *
     * @author sbebbington
     * @date 14 Sep 2016 14:29:23
     * @version 1.0.0-RC1
     * @return
     */
    public function emptySession(): void
    {
        if (session_id() != "") {
            session_destroy();
        }
        $this->session = null;
    }

    /**
     * Sets flash messages (recommend using string for value param)
     *
     * @param string $key
     * @param mixed $value
     * @author sbebbington
     * @date 14 Sep 2016 09:48:53
     * @version 1.0.0-RC1
     * @return void
     */
    public function setFlashMessage($key, $value): void
    {
        $_SESSION[FLASHMESSAGE][$key] = $value;
    }
}
