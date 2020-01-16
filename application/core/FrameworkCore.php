<?php
namespace Application\Core\Framework;

if (! defined('FRAMEWORKPHP') || FRAMEWORKPHP != 65535) {
    require_once ("../view/403.phtml");
}

use Application\Core\FrameworkException\FrameworkException;
use stdClass;
require_once (serverPath('/core/GlobalHelpers.php'));
require_once (serverPath('/core/HtmlBuilder.php'));

if (($preDefinedConstants = serverPath('/core/pre-defined-constants.php')) && file_exists($preDefinedConstants)) {
    require_once ($preDefinedConstants);
}

class Core extends HtmlBuilder
{

    /**
     * <p></p>
     *
     * @var string $segment
     */
    public $segment;

    /**
     * <p></p>
     *
     * @var string $host
     */
    public $host;

    /**
     * <p></p>
     *
     * @var string $partial
     */
    public $partial;

    /**
     * <p></p>
     *
     * @var object $controller
     */
    public $controller;

    /**
     * <p>For the meta title</p>
     *
     * @var string $title
     */
    public $title;

    /**
     * <p>For the meta description</p>
     *
     * @var string $description
     */
    public $description;

    /**
     * <p></p>
     *
     * @var string $metaData
     */
    public $metaData;

    /**
     * <p>File path to the hosted files</p>
     *
     * @var string $serverPath
     */
    public $serverPath;

    /**
     * <p></p>
     *
     * @var string $root
     */
    public $root;

    /**
     * <p>Used for the flash message</p>
     *
     * @var string $flash
     */
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

    /**
     * <p>To stop infinite redirections from routing via index.php</p>
     *
     * @var object $allowedFileExts
     */
    private $allowedFileExts;

    /**
     * <p>Core constructor - sets up everything for the response</p>
     *
     * @author sbebbington
     * @date 26 Sep 2017 14:42:15
     * @version 1.0.0-RC1
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
     * <p>Sets up the site configuration according
     * to the JSON objects in</p>
     * <pre>../application/config/pages.json</pre>
     * <p>Note that allowedSegments and pageControllers
     * are required settings</p>
     *
     * @author sbebbington
     * @date 28 Jul 2017 14:29:45
     * @version 1.0.0-RC1
     * @return boolean
     * @throws FrameworkException
     */
    protected function setSiteConfiguration(): bool
    {
        if (! file_exists(serverPath('/config/pages.json'))) {
            return FALSE;
        }
        $siteConfiguration = json_decode(file_get_contents(serverPath('/config/pages.json')), TRUE);
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
            return TRUE;
        }
        throw new FrameworkException("No pages or page controllers set in the pages.json file", 0x01);
    }

    /**
     * <p>Sets up the host object and checks against
     * the error reporting config values</p>
     *
     * @author sbebbington
     * @date 25 Jul 2017 09:40:06
     * @version 1.0.0-RC1
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
     * <p>Sets up the request URI path for the framework
     * and will also set the page segment (in the
     * allowedSegments JSON object)</p>
     *
     * @author sbebbington
     * @date 28 Jul 2017 11:50:03
     * @version 1.0.0-RC1
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
     * <p>Sets up the $_GET super global</p>
     *
     * @author sbebbington
     * @date 25 Jul 2017 09:48:12
     * @version 1.0.0-RC1
     * @return void
     * @deprecated 16 Jan 2020 13:58:01
     */
    protected function setGetGlobal(): void
    {
        writeToLogFile('Deprecated method called ' . __METHOD__ . '()');
        if (! empty($_GET)) {
            $segment = explode('?', $this->segment);
            $this->segment = $segment[0];

            if (isset($segment[1]) && ! empty($segment[1])) {
                $_GET = [];
                $get = explode("&", $segment[1]);
                foreach ($get as $data) {
                    $_data = explode("=", $data);
                    $_GET[$_data[0]] = urldecode($_data[1]);
                }
            }
        }
    }

    /**
     * <p>Sets the page title and meta descriptions</p>
     *
     * @author sbebbington
     * @date 25 Jul 2017 10:50:31
     * @version 1.0.0-RC1
     * @return array
     */
    public function getPageData(): array
    {
        if (! file_exists(serverPath('/config/pagedata.json'))) {
            return [];
        }
        return json_decode(file_get_contents(serverPath('/config/pagedata.json')), TRUE);
    }

    /**
     * <p>Sets the page matadata according to the
     * pagedata.json configuration file</p>
     *
     * @author sbebbington
     * @date 22 Jan 2018 09:38:02
     * @version 1.0.0-RC1
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
     * <p>Checks for valid extension; if one is present then
     * a canonical string is set as each page will assume
     * that the page without the file extension is the
     * main page</p>
     *
     * @author sbebbington
     * @date 25 Jul 2017 09:50:40
     * @version 1.0.0-RC1
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
     * <p>Bug fixed edition of the using ZF type view variables
     * added in error surpression to prevent warnings being
     * logged</p>
     *
     * @param mixed $instance
     * @param string $masterKey
     * @author sbebbington
     * @date 30 May 2017 09:49:39
     * @version 1.0.0-RC1
     * @return void
     */
    public function setView($instance = null, string $masterKey = ''): void
    {
        if (empty($instance)) {
            return;
        }

        foreach ($instance as $key => $data) {
            if ($masterKey == '') {
                $this->{$key} = $data;
            } else {
                $this->{$masterKey}->{$key} = $data;
            }
        }
    }

    /**
     * <p>Clears the page flash messages as these
     * are stored in the PHP $_SESSION global
     * or will empty the whole $_SESSION var</p>
     *
     * @param bool $emptyFlash
     * @author sbebbington
     * @date 28 Jul 2017 12:04:03
     * @version 1.0.0-RC1
     * @return array
     */
    public function emptySession(bool $emptyFlash = FALSE): array
    {
        return (isTrue($emptyFlash)) ? $_SESSION[FLASHMESSAGE] = [] : [];
    }

    /**
     * <p>Sets response headers</p>
     *
     * @param string $serverProtocol
     * @param int $serverResponse
     * @param string $serverMessage
     * @author sbebbington
     * @date 11 Jan 2019 16:47:51
     * @return void
     */
    public function setPageHeaders(string $serverProtocol = null, int $serverResponse = 200, string $serverMessage = "Ok"): void
    {
        if ($serverProtocol === null) {
            $protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
        } else {
            $protocol = $serverProtocol;
        }
        http_response_code($serverResponse);
        header("{$protocol} {$serverMessage}");
    }

    /**
     * <p>This will load the view and related controllers
     * It has an added exception for Jamie's admin html
     * template.</p>
     *
     * <p>This version should now allow Zend-alike
     * view variables - so if you set an object in a page
     * controller as <strong>$this->view->objName</strong>, you can use
     * <strong>$this->objName</strong> in the PHP/HTML view or something.
     * Update includes a simplified way to get the page
     * meta data</p>
     *
     * @author sbebbington
     * @date 25 Jul 2017 10:59:38
     * @version 1.0.0-RC1
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

            $emptyFlash = FALSE;
            if (isset($_SESSION[FLASHMESSAGE]) && ! empty($_SESSION[FLASHMESSAGE])) {
                $this->setView($_SESSION[FLASHMESSAGE], FLASH);
                $emptyFlash = TRUE;
            }

            $this->setPageHeaders();
            require_once (serverPath("/view/{$this->uriPath}{$this->segment}.phtml"));
            $this->emptySession($emptyFlash);
        }
    }

    /**
     * <p>Checks if the route is valid or not</p>
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
            $this->setPageHeaders(null, 404, 'Not Found');
            require_once (serverPath("/view/404.phtml"));
            exit();
        }
        return TRUE;
    }

    /**
     * <p>Requires Controller Core if necessary</p>
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
