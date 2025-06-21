<?php
if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    // Check if it's within Cloudflare's IP range
    include_once('../cloudflare.check.php');    // Check if it's within Cloudflare's IP range
}