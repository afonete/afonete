<?php

use Plisio\ClientAPI;

function verifyPlisioCallbackData($postData, $secretKey)
{
    $plisio = new ClientAPI($secretKey);
    return $plisio->verifyCallbackData($postData, $secretKey);
}


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
        $post['expire_utc'] = (string)$post['expire_utc'];
    }

    if (isset($post['tx_urls'])) {
        $post['tx_urls'] = html_entity_decode($post['tx_urls']);
    }

    $postString = serialize($post);
    $checkKey = hash_hmac('sha1', $postString, 'rPs1vyRlJZChOsYy9F--yeiEUTNgCOzCcnG4bKu_sp3hM5SP64GzWqqdadDM6x95');

    if ($checkKey != $verifyHash) {
        return false;
    }

    return true;
}
