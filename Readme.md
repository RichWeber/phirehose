# Yii2 extension to the Twitter Streaming API

A PHP interface to the Twitter Streaming API (firehose, etc). This library makes it easy to connect to and consume the Twitter stream via the Streaming API.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ php composer.phar require richweber/yii2-phirehose "dev-master"
```

or add

```
"richweber/yii2-phirehose": "dev-master"
```

to the ```require``` section of your `composer.json` file.

## Usage

### Component Configuration

```php
'components' => [
    ...
    'stream' => [
        'class' => 'richweber\twitter\streaming\lib\Stream',
        'username' => '1111111111-pPylwxC33VekLORMEfBIqYq8qekK4SqiD8pQpTs',
        'password' => 'OpKLltXkXBIUd4RQLwY8slLg3iIo2BCpXlgvkqxEBn33X',
        'consumerKey' => 'rvyKdnYDN887ohIfQU8m7tnxy',
        'consumerSecret' => 'oCYPFJSRPVlelJhEUCGVE8Aps0s4GpEfvNFR1ESQ01xTcq0xYL',
        'method' => 'user',
        'format' => 'json',
    ],
    ...
],
```

### Building a Yii Console Command

```php
<?php

namespace console\controllers;

use richweber\twitter\streaming\lib\Phirehose;

class StreamController extends \yii\console\Controller
{
    public function actionIndex()
    {
        Yii::$app->stream->consume();
    }
}
```

### Activating extension

You can test Phirehose using the PHP console command:

```
php yii stream
```

Twitter will send a stream of follower information for the user account, followed by real-time data as it arrives.

To activate Phirehose as a keep-alive, always-on console command, we'll use the [nohup command](http://manpages.ubuntu.com/manpages/hardy/man1/nohup.1.html), e.g. no hangup, and redirect output to dev/null:

```
nohup php yii stream > /dev/null 2>&1&
```

Ubuntu will respond with a job id of your process for future monitoring and termination:

``[1] 1376``

If you wish to check that the process is running, scan the task list for the job id:

``ps -e all | grep 1376``

You should see something like this:

``0  1000  1389 20855  20   0  17436   928 pipe_w S+   pts/8 0:00 grep --color=auto 1376 nohup php yii stream > /dev/null 2>&1``

And you can terminate Phirehose by killing the job id:

``kill 1376``

### Record the data received from Twitter

We want to minimize the amount of processing the real-time response needs to perform. Essentially, we just want to record the data received from Twitter to our database—and nothing else. We can do other processing in our own background tasks without slowing Phirehose's streaming connection. My example just takes tweet data from the incoming stream and stores it in the stream table:

```php
<?php

namespace common\components;

use richweber\twitter\streaming\lib\Stream;
use common\models\Twitts;

class MyStream extends Stream
{
    // This function is called automatically by the Phirehose class
    // when a new tweet is received with the JSON data in $status
    public function enqueueStatus($status)
    {
        $stream = json_decode($status);
        if (!(isset($stream->id_str))) { return; }

        $model = new Twitts;
        $model->tweet_id = $stream->id_str;
        $model->code = base64_encode(serialize($stream));
        $model->is_processed = 0;
        $model->created_at = time();
        $model->save();

        var_dump($stream);
    }
}
```

**Don't forget to change the class in config file.**

```php
'components' => [
    ...
    'stream' => [
        'class' => 'common\components\MyStream',
        ...
    ],
    ...
],
```

# Phirehose #

See:
  * https://github.com/fennb/phirehose/wiki/Introduction and
  * https://dev.twitter.com/streaming/overview

## Goals ##
  * Provide a simple interface to the Twitter Streaming API for PHP applications
  * Comply to Streaming API recommendations for error handling, reconnection, etc
  * Encourage well-behaved streaming API clients
  * Operate independently of PHP extensions (ie: shared memory, PCNTL, etc)

## What this library does do ##
  * Handles connection/authentication to the twitter streaming API
  * Consumes the stream handing off each status to be enqueued by a method of your choice
  * Handles graceful reconnection/back-off on connection and API errors
  * Monitors/reports performance metrics and errors

## What this library doesn't do ##
  * Decode/process tweets
  * Provide any sort of queueing mechanism for asynchronous processing (though some examples are included)
  * Provide any sort of inter-process communication
  * Provide any non-streaming API functionality (ie: user profile info, search, etc)

## How To Use ##

See the example subdirectory for example usage. In each example file you will need to insert your own oauth token/secret, and the key/secret for the Twitter app you have created.

  * filter-oauth.php shows how to follow certain keywords.
  * sample.php shows how to get a small random sample of all public statuses.
  * userstream-alternative.php shows how to get user streams. (All activity for one user.)
  * sitestream.php shows to how to get site streams. (All activity for multiple users.)

Please see the wiki for [documentation](https://github.com/fennb/phirehose/wiki/Introduction).

If you have any additional questions, head over to the Phirehose Users group [http://groups.google.com/group/phirehose-users]

It's recommended that you join (or at least regularly check) this group if you're actively using Phirehose so I can let you know when I release new versions.

Additionally, if you'd like to contact me directly, I'm [@fennb](http://twitter.com/fennb) on twitter.
