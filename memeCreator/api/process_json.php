<?php
/**
 * This file opens all the subreddits in the array and dumps thier json
 * to the screen. 
 */
echo"<pre>";
$subreddits = ['Showerthoughts','nocontext','nostupidquestions'];

foreach($subreddits as $reddit){
    $route = "https://www.reddit.com/r/{$reddit}/.json";
    echo $route."\n";
    $titles = get_titles($route);
    print_r($titles);
    flush();
    ob_flush();
}

/**
 * To save them to a file, pass in true to `save_file`
 * $url [string]     : url to grab subreddit
 * $save_file [bool] : whether to save the json to a file
 * $name [string]    : name to save file
 */
function get_titles($url,$save_file=false,$name=null){
    $string = file_get_contents($url);
    // turn into an associative array
    $json = json_decode($string,true);

    $data = [];
    
    foreach($json['data']['children'] as $key => $val){
        $data[] = $val['data']['title'];
    }

    if($save_file){
        file_put_contents($name,json_encode($json));
    }
    return $data;
}

