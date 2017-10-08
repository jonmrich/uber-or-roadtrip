<?php
$size      = 1;
$newoffset = 0;
$final     = [];
function runQuery($newoffset, $size, $final)
{
    $SECRET_KEY = '3715669c7738c1e8e101bc72bde7d1c31006d2fe5b609815d64a6192a586c152';
    $s          = hash_hmac('sha256', '/api/v2/canonical_tags?limit=100&offset=' . $newoffset . '-', $SECRET_KEY, false);
    $curl       = curl_init();
    $headers    = array();
    $headers[]  = 'Accept: application/json';
    $headers[]  = 'Content-Type: application/json';
    $headers[]  = "RT-ORG-APP-CLIENT-ID: 9489739c9d644b5fbb45fb3d25721024";
    $headers[]  = "RT-ORG-APP-HMAC: " . $s;
    curl_setopt_array($curl, array(
        //CURLOPT_URL            => 'https://absinthe.tomodev.net/canonical_tags?limit=100&offset=' . $newoffset,
        CURLOPT_URL => 'https://api.tomo.co/api/v2/canonical_tags?limit=100&offset='.$newoffset,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => "GET",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => $headers,
    ));
    $response = curl_exec($curl);
    $err      = curl_error($curl);
    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $array   = json_decode($response, true);
        $results = $array['data'];
        $size    = sizeof($array['data']); //size of array
        $result  = array();
        if ($size > 0) {
            $newoffset += 100;
            foreach ($results as $member) {
                $final['all_tags'][] = array(
                    'tag_name'      => $member['name'],
                    'tag_id'        => $member['id'],
                    'tag_public_id' => $member['public_id'],
                );
            }
        }
        if ($size > 0) {
            runQuery($newoffset, $size, $final);
        } else {
            echo json_encode($final);
        }
    }
} //end function
runQuery($newoffset, $size, $final);
