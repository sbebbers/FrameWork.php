<?php
if (! defined('FRAMEWORKPHP') || FRAMEWORKPHP != 65535) {
    require_once ("../view/403.phtml");
}

use Application\Controller\ControllerCore;
use Application\Model\Read\HomeModel;

require_once (serverPath("/model/read/HomeModel.php"));

class HomeController extends ControllerCore
{

    public function __construct()
    {
        ControllerCore::__construct();

        $this->sql = new HomeModel();
        foreach ($this->sql->getView() as $key => $data) {
            $key = $this->lib->convertSnakeCase($key);
            $this->view->{$key} = htmlspecialchars_decode($data);
        }
        $this->setFlashMessage('message', "Made you look :-P");
        if (isset($this->post['submit'])) {
            $this->lib->debug($this->post, TRUE);
        }
        // $this->view->easterEgg = ''; ## $this->lib->easterEgg();
    }

    /**
     * Tester for the encryption and decryption
     * library methods (and for my own sanity)
     * Seems worky.
     *
     * @param string $password
     * @param string $secret
     * @author sbebbington
     * @date 1 Mar 2017 09:20:43
     * @version 1.0.0-RC1
     * @return boolean
     */
    public function passwordTester(string $password = "Password", string $secret = 'password'): bool
    {
        $_password = $this->lib->encryptIt($password, $secret);
        $_decryption = $this->lib->decryptIt("{$_password}", $secret);
        return (bool) ($_decryption === $password);
    }
}
