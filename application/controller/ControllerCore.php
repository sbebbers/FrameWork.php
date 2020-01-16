<?php
namespace Application\Controller;

if (! defined('FRAMEWORKPHP') || FRAMEWORKPHP != 65535) {
    require_once ("../view/403.phtml");
}

use Application\Library\Library;
use stdClass;
use Application\Core\FrameworkException\FrameworkException;
use Application;
require_once (serverPath('/library/Library.php'));

class ControllerCore
{

    /**
     * <p></p>
     *
     * @var array $post
     */
    public $post = [];

    /**
     * <p>Used to pass mark-up and other entities to the served page</p>
     *
     * @var string $view
     */
    public $view;

    /**
     * <p></p>
     *
     * @var Application\Library $lib
     */
    public $lib;

    /**
     * <p></p>
     *
     * @var Application\Model $sql
     */
    public $sql;

    /**
     * <p></p>
     *
     * @var string $host
     */
    public $host;

    public function __construct()
    {
        $this->lib = new Library();
        if (! isset($_SESSION[FLASHMESSAGE])) {
            $_SESSION[FLASHMESSAGE] = [];
        }
        $this->setPost();

        $this->view = new stdClass();
        $this->host = host();
    }

    /**
     * <p>Sanatizes posted data</p>
     *
     * @param bool $unsetPost
     * @author Linden && sbebbington
     * @date 7 Oct 2016 14:54:10
     * @version 1.0.0-RC1
     * @return void
     */
    public function setPost(bool $unsetPost = FALSE): void
    {
        $post = @file_get_contents('php://input') ?? $_POST ?? [];
        if (empty($post)) {
            return;
        }

        foreach ($post as $key => $data) {
            $this->post[$key] = is_string($data) ? trim($data) : $data;
        }

        if ($unsetPost) {
            $this->emptyPost();
        }
    }

    /**
     * <p>This should empty the super global $_POST and the controller $this->post</p>
     *
     * @author sbebbington
     * @date 16 Jun 2016 11:25:04
     * @version 1.0.0-RC1
     * @return void
     */
    public function emptyPost(): void
    {
        $this->post = $_POST = [];
    }

    /**
     * <p>Clears PHP session cookies</p>
     *
     * @author sbebbington
     * @date 14 Sep 2016 14:29:23
     * @version 1.0.0-RC1
     * @return void
     * @throws FrameworkException
     */
    public function emptySession(): void
    {
        if (session_destroy()) {
            $this->session = null;
        } else {
            throw new FrameworkException(__METHOD__ . '() failed');
        }
    }

    /**
     * <p>Sets flash messages (recommend using string for value param)</p>
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
