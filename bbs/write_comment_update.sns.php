<?php

if (!defined('_GNUBOARD_')) {
    exit;
} // 개별 페이지 접근 불가

if (!$board['bo_use_sns']) {
    return;
}

set_cookie('ck_facebook_checked', false, 86400 * 31);
set_cookie('ck_twitter_checked', false, 86400 * 31);

//============================================================================
// 페이스북
//----------------------------------------------------------------------------
$wr_facebook_user = "";
if (!empty($_POST['facebook_checked'])) {
    include_once(G5_SNS_PATH."/facebook/src/facebook.php");

    $facebook = new Facebook([
        'appId' => $config['cf_facebook_appid'],
        'secret' => $config['cf_facebook_secret']
    ]);

    $user = $facebook->getUser();

    if ($user) {
        try {
            $link = get_pretty_url($bo_table, $wr['wr_parent'], '&#c_'.$comment_id);
            $attachment = [
                'message' => stripslashes($wr_content),
                'name' => $wr_subject,
                'link' => $link,
                'description' => stripslashes(strip_tags($wr['wr_content']))
            ];
            // 등록
            $facebook->api('/me/feed/', 'post', $attachment);
            //$errors = error_get_last(); print_r2($errros); exit;

            set_cookie('ck_facebook_checked', true, 86400 * 31);
        } catch (FacebookApiException $e) {
        }
    }

    $wr_facebook_user = get_session("ss_facebook_user");
}
//============================================================================


//============================================================================
// 트위터
//----------------------------------------------------------------------------
$wr_twitter_user = "";
if (!empty($_POST['twitter_checked'])) {
    include_once(G5_SNS_PATH."/twitter/twitteroauth/twitteroauth.php");
    include_once(G5_SNS_PATH."/twitter/twitterconfig.php");

    if (!(empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret']))) {
        $comment_url = get_pretty_url($bo_table, $wr['wr_parent'], '&#c_'.$comment_id);

        $post = googl_short_url($comment_url).' '.$wr_content;
        $post = utf8_strcut($post, 140);

        $access_token = $_SESSION['access_token'];
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'],
            $access_token['oauth_token_secret']);
        // 등록
        $connection->post('statuses/update', ['status' => $post]);

        set_cookie('ck_twitter_checked', true, 86400 * 31);
    }

    $wr_twitter_user = get_session("ss_twitter_user");
}
//============================================================================;