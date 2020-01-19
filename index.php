<?php
//https://stackoverflow.com/questions/43262121/trying-to-use-fetch-and-pass-in-mode-no-cors
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

function create_slug($string){
    $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    return $slug;
 }
//  echo create_slug('<p>#%%_==~!@#$%^&*()_+does this thing work or not</p>');
 //returns 'does-this-thing-work-or-not'

include("db.php");

if(!isset($_REQUEST['api'])){
    echo <<<EOD
    <ul>
    <li><a href="{$_SERVER['PHP_SELF']}?items=true">item slug generator</a></li>
    <li><a href="{$_SERVER['PHP_SELF']}?brands=true">bland slug generator</a></li>
    <li><a href="{$_SERVER['PHP_SELF']}?api=items&spec=for-sale&debug=true">API (for-sale)</a></li>
    <li><a href="{$_SERVER['PHP_SELF']}?api=items&spec=for-sale&debug=true&id=38211">API (for-sale-item)</a></li>
    <li><a href="{$_SERVER['PHP_SELF']}?api=items&spec=sold&debug=true">API (sold)</a></li>
    <li><a href="{$_SERVER['PHP_SELF']}?api=items&spec=sold&debug=true&id=37764">API (sold-item)</a></li>
    <li><a href="{$_SERVER['PHP_SELF']}?api=items&spec=press&debug=true">API (press)</a></li>
    <li><a href="{$_SERVER['PHP_SELF']}?api=items&spec=press&debug=true&id=37258">API (press-item)</a></li>
    <li><a href="{$_SERVER['PHP_SELF']}?api=items&spec=testimonials&debug=true">API (testimonials)</a></li>
    <li><a href="{$_SERVER['PHP_SELF']}?api=items&spec=testimonials&debug=true&id=37367">API (testimonials-item)</a></li>
    </ul>
EOD;

    if(isset($_REQUEST['items'])) include("csc-name-slug.php");
    if(isset($_REQUEST['brands'])) include("csc-brands-slug.php");
}else{
    include("csc-api.php");
}



?>