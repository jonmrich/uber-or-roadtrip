<?php
//
// A very simple PHP example that sends a HTTP POST to a remote site
//

echo $_SERVER['REMOTE_ADDR'];

$thisthing = "host={66.117.248.59}";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://tools.keycdn.com/geo.json?");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
            $thisthing);

// in real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS, 
//          http_build_query(array('postvar1' => 'value1')));

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);

curl_close ($ch);

// further processing ....

var_dump($ch)

?>