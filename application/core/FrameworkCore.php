<?php
namespace Application\Core\Framework;

if (! defined('FRAMEWORKPHP') || FRAMEWORKPHP != 65535) {
    require_once ("../view/403.phtml");
}

define('FLASHMESSAGE', 'flashMessage');
define('FLASH', 'flash');
define('DAY', 'day');
define('MONTH', 'month');
define('YEAR', 'year');
define('FULLVALUE', 'full');
define('SHORTVALUE', 'short');
define('NUMERICVALUE', 'numeric');
define('DEFAULTVALUE', 'default');

use Application\Core\FrameworkException\FrameworkException;
use stdClass;
require_once (serverPath('/core/GlobalHelpers.php'));
require_once (serverPath('/core/HtmlBuilder.php'));

class Core extends HtmlBuilder
{

    public $segment;

    public $host;

    public $partial;

    public $controller;

    public $title;

    public $description;

    public $metaData;

    public $serverPath;

    public $root;

    public $flash;

    public $filePath;

    public $uriPath;

    public $http;

    public $canonical = '';

    public $pageData = [];

    public $ignoredExts = [];

    protected $allowedSegments;

    protected $pageController;

    private $errorReporting;

    private $allowedFileExts;

    /**
     * Core constructor
     *
     * @param
     *            field_type
     * @author sbebbington
     * @date 26 Sep 2017 14:42:15
     * @version 0.1.5-RC3
     * @return void
     * @throws FrameworkException
     */
    public function __construct()
    {
        HtmlBuilder::__construct();
        if (isFalse($this->setSiteConfiguration())) {
            throw new FrameworkException("Please set up a pages.json file in the config folder", 0x00);
        }
        $this->pageData = $this->getPageData();
        $this->metaData = '';
        if (! empty($this->pageData['metaData'])) {
            $this->metaData = $this->setMetaData($this->pageData['metaData']);
        }
        
        $this->setErrorReporting();
        $this->setUri();
        $this->setGetGlobal();
        $this->checkExtension();
        
        $this->serverPath = serverPath();
        $this->root = str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']);
        $this->controller = new stdClass();
        $this->flash = new stdClass();
        $this->partial = array(
            'header' => (file_exists(serverPath("/view/partial/header.phtml"))) ? serverPath("/view/partial/header.phtml") : '',
            'footer' => (file_exists(serverPath("/view/partial/footer.phtml"))) ? serverPath("/view/partial/footer.phtml") : ''
        );
    }

    /**
     * Sets up the site configuration according
     * to the JSON objects in
     * ../application/config/pages.json
     * Note that allowedSegments and pageControllers
     * are required settings
     *
     * @param
     *            na
     * @author sbebbington
     * @date 28 Jul 2017 14:29:45
     * @version 0.1.5-RC3
     * @return boolean
     * @throws FrameworkException
     */
    protected function setSiteConfiguration(): bool
    {
        if (! file_exists(serverPath('/config/pages.json'))) {
            return false;
        }
        $siteConfiguration = json_decode(file_get_contents(serverPath('/config/pages.json')), true);
        if (empty($siteConfiguration)) {
            throw new FrameworkException("Failed to load site configuration file", 0xf17e);
        }
        $this->allowedSegments = $siteConfiguration['allowedSegments'];
        $this->pageController = $siteConfiguration['pageController'];
        $this->errorReporting = $siteConfiguration['errorReporting'] ?? [];
        $this->allowedFileExts = $siteConfiguration['allowedFileExts'] ?? [];
        $this->ignoredExts = $siteConfiguration['ignotedFileExts'] ?? [
            'js',
            'css'
        ];
        
        if (! empty($this->allowedSegments) && ! empty($this->pageController)) {
            return true;
        }
        throw new FrameworkException("No pages or page controllers set in the pages.json file", 0x01);
    }

    /**
     * Sets up the host object and checks against
     * the error reporting config values
     *
     * @param
     *            na
     * @author sbebbington
     * @date 25 Jul 2017 09:40:06
     * @version 0.1.5-RC3
     * @return void
     */
    protected function setErrorReporting(): void
    {
        $this->host = getConfig('baseURL') . getConfig("uriSegments");
        if (in_array($this->host, $this->errorReporting)) {
            error_reporting(- 1);
            ini_set('display_errors', '1');
        }
    }

    /**
     * Sets up the request URI path for the framework
     * and will also set the page segment (in the
     * allowedSegments JSON object)
     *
     * @param
     *            na
     * @author sbebbington
     * @date 28 Jul 2017 11:50:03
     * @version 0.1.5-RC3
     * @return void
     */
    protected function setUri(): void
    {
        $this->uriPath = getConfig('uriPath');
        $page = [];
        
        if (($page = array_filter(explode('/', $_SERVER['REQUEST_URI']), 'strlen')) && strlen($this->uriPath) <= 1 && ! empty($page)) {
            foreach ($page as $key => $data) {
                if ($key != count($page)) {
                    $this->uriPath .= "{$data}/";
                }
            }
        }
        $this->segment = (count($page) > 0) ? strtolower($page[count($page)]) : '';
    }

    /**
     * Sets up the $_GET super global
     *
     * @param
     *            na
     * @author sbebbington
     * @date 25 Jul 2017 09:48:12
     * @version 0.1.5-RC3
     * @return void
     */
    protected function setGetGlobal(): void
    {
        if (! empty($_GET)) {
            $segment = explode('?', $this->segment);
            $this->segment = $segment[0];
            
            if (isset($segment[1]) && ! empty($segment[1])) {
                $_GET = array();
                $get = explode("&", $segment[1]);
                foreach ($get as $data) {
                    $_data = explode("=", $data);
                    $_GET[$_data[0]] = urldecode($_data[1]);
                }
            }
        }
    }

    /**
     * Sets the page title and meta descriptions
     *
     * @param
     *            na
     * @author sbebbington
     * @date 25 Jul 2017 10:50:31
     * @version 0.1.5-RC3
     * @return array
     */
    public function getPageData(): array
    {
        if (! file_exists(serverPath('/config/pagedata.json'))) {
            return [];
        }
        return json_decode(file_get_contents(serverPath('/config/pagedata.json')), true);
    }

    /**
     * Sets the page matadata according to the
     * pagedata.json configuration file
     *
     * @author sbebbington
     * @date 22 Jan 2018 09:38:02
     * @version 0.1.5-RC3
     * @return string
     */
    public function setMetaData(array $pageData = []): string
    {
        if (empty($pageData) || empty($pageData[DEFAULTVALUE])) {
            return '';
        }
        $metaData = "";
        
        if ((empty($this->segment) || $this->segment == 'home') || ! empty($pageData["{$this->segment}"])) {
            if (! empty($pageData["{$this->segment}"])) {
                $pageData = $pageData["{$this->segment}"];
            } else if (empty($this->segment) || $this->segment == 'home') {
                $pageData = $pageData[DEFAULTVALUE];
            }
            
            foreach ($pageData as $key => $data) {
                $metaData .= "<meta name=\"{$key}\" content=\"{$data}\" />" . PHP_EOL;
            }
        }
        
        return $metaData;
    }

    /**
     * Checks for valid extension; if one is present then
     * a canonical string is set as each page will assume
     * that the page without the file extension is the
     * main page
     *
     * @param
     *            na
     * @author sbebbington
     * @date 25 Jul 2017 09:50:40
     * @version 0.1.5-RC3
     * @return void
     */
    protected function checkExtension(): void
    {
        if (strpos($this->segment, ".") > 0) {
            $segments = explode(".", $this->segment);
            
            if (in_array($segments[count($segments) - 1], $this->allowedFileExts)) {
                $this->segment = $segments[count($segments) - 2];
                $this->canonical = "<link rel=\"canonical\" href=\"{$this->host}/{$this->segment}\">" . PHP_EOL;
            }
        }
    }

    /**
     * Bug fixed edition of the using ZF type view variables
     * added in error surpression to prevent warnings being
     * logged
     *
     * @param
     *            resource | \stdClass, string
     * @author sbebbington
     * @date 30 May 2017 09:49:39
     * @version 0.1.5-RC3
     * @return void
     */
    public function setView($instance, string $masterKey = ''): void
    {
        foreach ($instance as $key => $data) {
            if ($masterKey == '') {
                $this->{$key} = $data;
            } else {
                $this->{$masterKey}->{$key} = $data;
            }
        }
    }

    /**
     * Clears the page flash messages as these
     * are stored in the PHP $_SESSION global
     * or will empty the whole $_SESSION var
     *
     * @param
     *            boolean
     * @author sbebbington
     * @date 28 Jul 2017 12:04:03
     * @version 0.1.5-RC3
     * @return array
     */
    public function emptySession(bool $emptyFlash = false): array
    {
        return (isTrue($emptyFlash)) ? $_SESSION[FLASHMESSAGE] = array() : array();
    }

    /**
     * This will load the view and related controllers
     * It has an added exception for Jamie's admin html
     * template.
     * This version should now allow Zend-alike
     * view variables - so if you set an object in a page
     * controller as $this->view->objName, you can use
     * $this->objName in the PHP/HTML view or something.
     * Update includes a simplified way to get the page
     * meta data
     *
     * @param
     *            na
     * @author sbebbington
     * @date 25 Jul 2017 10:59:38
     * @version 0.1.5-RC3
     * @return void
     */
    public function loadPage(): void
    {
        if ($this->segment == "") {
            $this->segment = 'home';
        }
        $this->checkForPage();
        
        if (isTrue(in_array($this->segment, $this->allowedSegments))) {
            $this->title = $this->pageData['titles']["{$this->segment}"] ?? '';
            $this->description = $this->pageData['descriptions']["{$this->segment}"] ?? '';
            $this->loadControllerCore();
            
            foreach ($this->pageController as $instance => $controller) {
                if ($this->segment == $instance) {
                    require_once (serverPath("/controller/{$controller}.php"));
                    $_instance = $this->lib->camelCaseFromDashes($instance);
                    $this->controller->{$_instance} = new $controller();
                    
                    if ($this->controller->{$_instance}->view instanceof stdClass) {
                        $this->setView($this->controller->{$_instance}->view);
                        $this->controller->{$_instance}->view = null;
                    }
                }
            }
            
            $emptyFlash = false;
            if (isset($_SESSION[FLASHMESSAGE]) && ! empty($_SESSION[FLASHMESSAGE])) {
                $this->setView($_SESSION[FLASHMESSAGE], FLASH);
                $emptyFlash = true;
            }
            
            require_once (serverPath("/view/{$this->uriPath}{$this->segment}.phtml"));
            $this->emptySession($emptyFlash);
        }
    }

    /**
     * Checks if the route is valid or not
     *
     * @author Shaun B
     * @date 27 Jul 2018 16:28:18
     * @return bool
     */
    public function checkForPage(): bool
    {
        if (isFalse(in_array($this->segment, $this->allowedSegments)) || isFalse(file_exists(serverPath("/view/{$this->uriPath}{$this->segment}.phtml")))) {
            $this->title = '404 error - page not found, please try again';
            $this->description = 'There\'s a Skeleton in the Sandbox';
            $this->setView(array(
                "_404Error" => 1
            ));
            http_response_code(404);
            require_once (serverPath("/view/404.phtml"));
            exit();
        }
        return true;
    }

    /**
     * Requires Controller Core if necessary
     *
     * @author Shaun B
     * @date 27 Jul 2018 16:30:33
     * @return void
     */
    public function loadControllerCore(): void
    {
        if (isTrue(getConfig('loadCoreController'))) {
            require_once (serverPath("/controller/ControllerCore.php"));
        }
    }
}
