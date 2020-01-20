<?php
header("Access-Control-Allow-Origin: *");

echo <<<EOD
    <ul>
    <li><a href="csc-api-base.php?items=true">item slug generator</a></li>
    <li><a href="csc-api-base.php?brands=true">bland slug generator</a></li>
    <li>---</li>
    <li><a href="csc-api-base.php?api=items&spec=Live">API (Live)</a></li>
    <li><a href="csc-api-base.php?api=items&spec=Live&id=38211">API (Live-item)</a></li>
    <li>---</li>
    <li><a href="csc-api-base.php?api=items&spec=Archive">API (Archive)</a></li>
    <li><a href="csc-api-base.php?api=items&spec=Archive&id=37764">API (Archive-item)</a></li>
    <li>---</li>
    <li><a href="csc-api-base.php?api=items&spec=Press">API (Press)</a></li>
    <li><a href="csc-api-base.php?api=items&spec=Press&id=37258">API (Press-item)</a></li>
    <li>---</li>
    <li><a href="csc-api-base.php?api=items&spec=Testimonials">API (Testimonials)</a></li>
    <li><a href="csc-api-base.php?api=items&spec=Testimonials&id=37367">API (Testimonials-item)</a></li>
    </ul>
EOD;

?>