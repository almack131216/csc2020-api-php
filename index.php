<?php
header("Access-Control-Allow-Origin: *");

echo <<<EOD
    <ul>
    <li><a href="csc-api-base.php?items=true">item slug generator</a></li>
    <li><a href="csc-api-base.php?brands=true">bland slug generator</a></li>
    <li><a href="csc-api-base.php?api=items&spec=Live&debug=true">API (Live)</a></li>
    <li><a href="csc-api-base.php?api=items&spec=Live&debug=true&id=38211">API (Live-item)</a></li>
    <li><a href="csc-api-base.php?api=items&spec=Archive&debug=true">API (Archive)</a></li>
    <li><a href="csc-api-base.php?api=items&spec=Archive&debug=true&id=37764">API (Archive-item)</a></li>
    <li><a href="csc-api-base.php?api=items&spec=Press&debug=true">API (press)</a></li>
    <li><a href="csc-api-base.php?api=items&spec=Press&debug=true&id=37258">API (press-item)</a></li>
    <li><a href="csc-api-base.php?api=items&spec=Testimonials&debug=true">API (testimonials)</a></li>
    <li><a href="csc-api-base.php?api=items&spec=Testimonials&debug=true&id=37367">API (testimonials-item)</a></li>
    </ul>
EOD;

?>