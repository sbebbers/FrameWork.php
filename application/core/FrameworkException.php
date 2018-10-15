<?php
namespace Application\Core\FrameworkException;

if (! defined('FRAMEWORKPHP') || FRAMEWORKPHP != 65535) {
    require_once ("../view/403.phtml");
}

use Exception;

class FrameworkException extends Exception
{

    public $exceptionDetails;

    /**
     *
     * @param
     *            string, int, array
     * @author Shaun Bebbington
     * @date	21 Mar 2018 11:30:01
     * @return void
     */
    public function __construct($message, $code = null, array $additionalInformation = [])
    {
        Exception::__construct($message, (int) $code);
        $error = [
            'message' => $message,
            'code' => $code
        ];

        $this->setExceptionDetails(array_merge($error, $additionalInformation));
    }

    /**
     * getExceptionDetails
     *
     * @author Shaun Bebbington
     * @date	21 Mar 2018 11:30:49
     * @return array
     */
    public function getExceptionDetails()
    {
        return $this->exceptionDetails;
    }

    /**
     * setExceptionDetails
     *
     * @param
     *            array
     * @author Shaun Bebbington
     * @date	21 Mar 2018 11:31:21
     * @return void
     */
    public function setExceptionDetails(array $exceptionDetails = [])
    {
        $this->exceptionDetails = $exceptionDetails;
    }
}
