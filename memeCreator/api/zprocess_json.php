<?php
echo"<pre>";
$subreddits = ['Showerthoughts','nocontext','nostupidquestions'];

foreach($subreddits as $reddit){
    $route = "https://www.reddit.com/r/{$reddit}/.json";
    echo $route."\n";
    $titles = get_titles($route );
    print_r($titles);
    flush();
    ob_flush();
}

function get_titles($url){
    $string = file_get_contents($url);
    // turn into an associative array
    $json = json_decode($string,true);

    $data = [];
    
    foreach($json['data']['children'] as $key => $val){
        $data[] = $val['data']['title'];
    }
    return $data;
}

