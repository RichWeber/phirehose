<?php
/**
 * @link http://richweber.net/
 * @copyright Copyright (c) 2014 Roman Bahatyi, richweber.net
 * @license http://richweber.net/license/
 */

namespace richweber\twitter\streaming\lib;

/**
 * @author Roman Bahatyi <rbagatyi@gmail.com>
 * @since 1.0
 */
class Stream extends OauthPhirehose
{
    // This function is called automatically by the Phirehose class
    // when a new tweet is received with the JSON data in $status
    public function enqueueStatus($status)
    {
        $stream = json_decode($status);
        if (!(isset($stream->id_str))) { return; }
        var_dump($stream);
    }
}
