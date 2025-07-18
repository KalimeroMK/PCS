<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$social_providers_config = array(
    array(
        "provider_id" => "Facebook",
        "provider_name" => "Facebook",
        "require_client_id" => true,
        "callback" => true,
        "new_app_link" => "https://developers.facebook.com/apps",
        "default_api_scope" => "email, public_profile, user_friends",

        "default_network" => true,
        "cat" => "socialnetworks",
    ),
    array(
        "provider_id" => "Google",
        "provider_name" => "Google",
        "callback" => true,
        "require_client_id" => true,
        "new_app_link" => "https://console.developers.google.com",
        "default_api_scope" => "profile https://www.googleapis.com/auth/plus.profile.emails.read",

        "default_network" => true,
        "cat" => "socialnetworks",
    ),
    array(
        "provider_id" => "Twitter",
        "provider_name" => "Twitter",
        "callback" => true,
        "new_app_link" => "https://dev.twitter.com/apps",

        "default_network" => true,
        "cat" => "microblogging",
    ),
    array(
        "provider_id" => "WordPress",
        "provider_name" => "WordPress",
        "require_client_id" => true,
        "callback" => true,
        "new_app_link" => "https://developer.wordpress.com/apps/new/",

        "cat" => "blogging",
    ),
    array(
        "provider_id" => "Yahoo",
        "provider_name" => "Yahoo!",
        "new_app_link" => null,

        "cat" => "pleasedie",
    ),
    array(
        "provider_id" => "LinkedIn",
        "provider_name" => "LinkedIn",
        "new_app_link" => "https://www.linkedin.com/secure/developer",

        "cat" => "professional",
    ),
    array(
        "provider_id" => "Disqus",
        "provider_name" => "Disqus",
        "require_client_id" => true,
        "callback" => true,
        "new_app_link" => "https://disqus.com/api/applications/",

        "cat" => "misc",
    ),
    array(
        "provider_id" => "Instagram",
        "provider_name" => "Instagram",
        "callback" => true,
        "require_client_id" => true,
        "new_app_link" => "http://instagr.am/developer/clients/manage/",

        "cat" => "media",
    ),
    array(
        "provider_id" => "Reddit",
        "provider_name" => "Reddit",
        "require_client_id" => true,
        "callback" => true,
        "new_app_link" => "https://ssl.reddit.com/prefs/apps",

        "cat" => "socialnetworks",
    ),
    array(
        "provider_id" => "Foursquare",
        "provider_name" => "Foursquare",
        "callback" => true,
        "require_client_id" => true,
        "new_app_link" => "https://www.foursquare.com/oauth/",

        "cat" => "microblogging",
    ),
    array(
        "provider_id" => "LastFM",
        "provider_name" => "Last.FM",
        "new_app_link" => "http://www.lastfm.com/api/account",

        "cat" => "media",
    ),
    array(
        "provider_id" => "Tumblr",
        "provider_name" => "Tumblr",
        "new_app_link" => "http://www.tumblr.com/oauth/apps",

        "cat" => "microblogging", // o well
    ),
    array(
        "provider_id" => "Goodreads",
        "provider_name" => "Goodreads",
        "callback" => true,
        "new_app_link" => "http://www.goodreads.com/api",

        "cat" => "media",
    ),
    array(
        "provider_id" => "Stackoverflow",
        "provider_name" => "Stackoverflow",
        "new_app_link" => null,

        "cat" => "programmers",
    ),
    array(
        "provider_id" => "GitHub",
        "provider_name" => "GitHub",
        "require_client_id" => true,
        "callback" => true,
        "new_app_link" => "https://github.com/settings/applications/new",
        "default_api_scope" => "user:email",

        "cat" => "programmers",
    ),
    array(
        "provider_id" => "Dribbble",
        "provider_name" => "Dribbble",
        "require_client_id" => true,
        "custom_callback" => true,
        "new_app_link" => "https://dribbble.com/account/applications/new",

        "cat" => "designers",
    ),
    array(
        "provider_id" => "500px",
        "provider_name" => "px500",
        "new_app_link" => "http://developers.500px.com/",

        "cat" => "media",
    ),
    array(
        "provider_id" => "Skyrock",
        "provider_name" => "Skyrock",
        "callback" => true,
        "new_app_link" => "https://www.skyrock.com/developer/application",

        "cat" => "socialnetworks",
    ),
    array(
        "provider_id" => "Mixi",
        "provider_name" => "Mixi",
        "new_app_link" => null,

        "cat" => "socialnetworks",
    ),
    array(
        "provider_id" => "Steam",
        "provider_name" => "Steam",
        "new_app_link" => "http://steamcommunity.com/dev/apikey",
        "require_api_key" => true,

        "cat" => "gamers",
    ),
    array(
        "provider_id" => "TwitchTV",
        "provider_name" => "Twitch.tv",
        "require_client_id" => true,
        "callback" => true,
        "new_app_link" => "http://www.twitch.tv/settings?section=applications",

        "cat" => "gamers",
    ),
    array(
        "provider_id" => "Vkontakte",
        "provider_name" => "ВКонтакте",
        "callback" => true,
        "require_client_id" => true,
        "new_app_link" => "http://vk.com/developers.php",

        "cat" => "socialnetworks",
    ),
    array(
        "provider_id" => "Mailru",
        "provider_name" => "Mailru",
        "require_client_id" => true,
        "callback" => true,
        "new_app_link" => "http://api.mail.ru/",

        "cat" => "misc",
    ),
    array(
        "provider_id" => "Yandex",
        "provider_name" => "Yandex",
        "require_client_id" => true,
        "callback" => true,
        "new_app_link" => "https://oauth.yandex.ru",

        "cat" => "misc",
    ),
    array(
        "provider_id" => "Odnoklassniki",
        "provider_name" => "Odnoklassniki",
        "require_client_id" => true,
        "callback" => true,
        "new_app_link" => "http://dev.odnoklassniki.ru/",

        "cat" => "socialnetworks",
    ),
    array(
        "provider_id" => "AOL",
        "provider_name" => "AOL",
        "new_app_link" => null,

        "cat" => "pleasedie",
    ),
    array(
        "provider_id" => "Live",
        "provider_name" => "Windows Live",
        "require_client_id" => true,
        "new_app_link" => "https://account.live.com/developers/applications/create",

        "cat" => "pleasedie",
    ),
    array(
        "provider_id" => "PixelPin",
        "provider_name" => "PixelPin",
        "require_client_id" => true,
        "callback" => true,
        "new_app_link" => "https://login.pixelpin.co.uk/",

        "cat" => "misc",
    ),
);