<?php
/**
 * Yii2 extension to the Twitter Streaming API
 *
 * @copyright Copyright &copy; Roman Bahatyi, richweber.net, 2015
 * @package yii2-phirehose
 * @version 1.0.0
 */

namespace richweber\twitter\streaming\lib;

/**
 * Exception represents a generic exception for all purposes.
 *
 * @author Roman Bahatyi <rbagatyi@gmail.com>
 * @since 1.0
 */
class Exception extends \Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Exception';
    }
}