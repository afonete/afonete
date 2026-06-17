<?php

// app/Helpers/PlisioHelper.php
//
// SECURITY: Uses config('services.plisio.api_key') instead of a hardcoded
// key. Make sure PLISIO_API_KEY is set in your .env file.

function verifyCallbackData()
{
    if (!isset($_POST['verify_hash'])) {
        return false;
    }

    $post = $_POST;
    $verifyHash = $post['verify_hash'];
    unset($post['verify_hash']);
    ksort($post);

    if (isset($post['expire_utc'])) {
        $post['expire_utc'] = (string) $post['expire_utc'];
    }

    if (isset($post['tx_urls'])) {
        $post['tx_urls'] = html_entity_decode($post['tx_urls']);
    }

    $postString = serialize($post);
    $apiKey     = config('services.plisio.api_key', env('PLISIO_API_KEY'));
    $checkKey   = hash_hmac('sha1', $postString, $apiKey);

    return hash_equals($checkKey, $verifyHash);
}
