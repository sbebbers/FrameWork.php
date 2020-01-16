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
     * <p>Constructs parent</p>
     *
     * @param string $message
     * @param int $code
     * @param array $additionalInformation
     * @author Shaun Bebbington
     * @date 21 Mar 2018 11:30:01
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
     * <p>Returns the Exception details</p>
     *
     * @author Shaun Bebbington
     * @date 21 Mar 2018 11:30:49
     * @return array
     */
    public function getExceptionDetails(): array
    {
        return $this->exceptionDetails;
    }

    /**
     * <p>Creates the Exception details information</p>
     *
     * @param array $exceptionDetails
     * @author Shaun Bebbington
     * @date 21 Mar 2018 11:31:21
     * @return void
     */
    public function setExceptionDetails(array $exceptionDetails = []): void
    {
        $this->exceptionDetails = $exceptionDetails;
    }
}
