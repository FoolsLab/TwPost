<?php
/**
 * @package TwPost
 * @version 0.1
 */
/*
Plugin Name: TwPost
Description: twitter post bot
Version: 0.1
 */

require __DIR__ . '/vendor/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class TwPost {
  public function __construct() {
    add_action('transition_post_status', [$this, 'published'], 10, 3);
  }

  public function published($new_status, $old_status, $post) {
    if($new_status != 'publish' || $old_status == 'publish') {
      return null;
    }

    $str = sprintf("%s - %s\n%s", $post->post_title, get_bloginfo('name'), get_permalink($post->ID));
    
    $consumerKey       = $_ENV['TWPOST_CONSUMER_KEY'];
    $consumerSecret    = $_ENV['TWPOST_CONSUMER_SECRET'];
    $accessToken       = $_ENV['TWPOST_ACCESS_TOKEN'];
    $accessTokenSecret = $_ENV['TWPOST_ACCESS_TOKEN_SECRET'];

    file_put_contents('hoge.log', $consumerKey);

    $tw = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
    // $tw->setApiVersion('2');

    $tw->post("statuses/update", ["status" => $str]);
  }
}

$twpost = new TwPost();

