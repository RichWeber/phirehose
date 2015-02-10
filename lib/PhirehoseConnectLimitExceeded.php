<?php
/**
 * Розширення Yii Framework 2 для роботи з Monexy API
 *
 * @copyright Copyright &copy; Roman Bahatyi, richweber.net, 2015
 * @package yii2-monexy
 * @version 1.0.0
 */

namespace richweber\twitter\streaming\lib;

/**
 * ApiException represents a generic exception for all purposes.
 *
 * @author Roman Bahatyi <rbagatyi@gmail.com>
 * @since 1.0
 */
class PhirehoseConnectLimitExceeded extends PhirehoseException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'PhirehoseConnectLimitExceeded';
    }
}