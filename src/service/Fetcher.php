<?php
namespace Service;

class Fetcher
{
    // fetch the content from a given url using cURL
    public function fetch(string $url): ?string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the response as a string
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // disable SSL verification
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // timeout after 10 seconds

        // execute the cURL session
        $result = curl_exec($ch);

        // check if an error occurred during the request
        if (curl_errno($ch)) {
            curl_close($ch);
            return null;
        }

        curl_close($ch);
        return $result ?: null;
    }
}
