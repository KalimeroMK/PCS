<?php

// If you want to edit the email sent to the admin when writing other poll opinions, edit this file.
if (!defined('_GNUBOARD_')) {
    exit;
} // Individual page access not allowed
?>

<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>Poll Other Opinion Mail</title>
</head>

<body>

<div style="margin:30px auto;width:600px;border:10px solid #f7f7f7">
    <div style="border:1px solid #dedede">
        <h1 style="padding:30px 30px 0;background:#f7f7f7;color:#555;font-size:1.4em">
            <?php
            echo $subject ?>
        </h1>
        <span style="display:block;padding:10px 30px 30px;background:#f7f7f7;text-align:right">
            Author <?php
            echo $name ?> (<?php
            echo $mb_id ?>)
        </span>
        <p style="margin:20px 0 0;padding:30px 30px 50px;min-height:200px;height:auto !important;height:200px;border-bottom:1px solid #eee">
            <?php
            echo $content ?>
        </p>
    </div>
</div>

</body>
</html>
