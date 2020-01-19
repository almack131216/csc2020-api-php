<?php

function removeHtmlChars($getString){
    $getString = str_replace('&nbsp;'," ",$getString);//space char 
    $getString = str_replace('&pound;','£',$getString);//£
    $getString = str_replace('&#39;',"'",$getString);//'
    return $getString;
}

// Now, let's fetch five random items and output their names to a list.
// We'll add less error handling here as you can do that on your own now
$sql = null;
$debug = "";
$printDebug = false;
if(isset($_REQUEST['debug']) && $_REQUEST['debug'] = true){
    $printDebug = true;
}
if($_REQUEST['api'] === 'brands'){
    $sql = "SELECT id,subcategory AS brand,slug FROM catalogue_subcats WHERE category=2 and subcategory!='' ORDER BY subcategory ASC";
}
if($_REQUEST['api'] === 'items'){
    $isStockPage = false;
    $isItemListPage = true;
    $sqlSelect = "";
    $sqlSelectCommonStock = ", `catalogue`.`subcategory` AS `brand`, `catalogue`.`detail_1` AS `year`";
    $sqlSelectCommonPrice = ", `catalogue`.`price`, `catalogue`.`price_details`";
    $sqlSelectCommonExcerpt = ", `catalogue`.`description` AS `excerpt`";
    $sqlWhere = "";
    $sqlGroup = " GROUP BY `catalogue`.`id`,`catalogue`.`name`";
    $sqlOrder = " ORDER BY `catalogue`.`upload_date` DESC";
    $qLimit = " LIMIT 300";
    if($_REQUEST['spec'] === 'for-sale') {
        $isStockPage = true;
        $sqlSelect .= $sqlSelectCommonStock; 
        // $sqlSelect .= $sqlSelectCommonExcerpt;        
        $sqlSelect .= $sqlSelectCommonPrice;               
        $sqlWhere .= " AND `catalogue`.`category` = 2 AND `catalogue`.`status` = 1";        
    }
    if($_REQUEST['spec'] === 'sold') {
        $isStockPage = true;
        $sqlSelect .= $sqlSelectCommonStock;
        // $sqlSelect .= $sqlSelectCommonExcerpt; 
        $sqlWhere .= " AND `catalogue`.`category` = 2 AND `catalogue`.`status` = 2";
    }
    if($_REQUEST['spec'] === 'press') {
        $isStockPage = false;
        $sqlSelect .= ",`catalogue`.`detail_2` AS source";
        $sqlWhere .= " AND `catalogue`.`category` = 4 AND `catalogue`.`status` = 1";
    }
    if($_REQUEST['spec'] === 'testimonials') {
        $isStockPage = false;
        $sqlSelect .= ",`catalogue`.`detail_2` AS source";
        $sqlWhere .= " AND `catalogue`.`category` = 3 AND `catalogue`.`status` = 1";
    }
    if($_REQUEST['spec'] === 'news') {
        $isStockPage = false;
        $sqlSelect .= ",`catalogue`.`detail_2` AS source";
        $sqlWhere .= " AND `catalogue`.`category` = 5 AND `catalogue`.`status` = 1";
    }
    if(isset($_REQUEST['id'])) {
        $sqlGroup = "";
        $isItemListPage = false;
        $sqlSelect .= ",`catalogue`.`description`";
        $sqlWhere = " AND ((`catalogue`.`id` = ".$_REQUEST['id']." AND `catalogue`.`category` = 2 AND `catalogue`.`status` = 1)";
        $sqlWhere .= " OR (`catalogue`.`id_xtra` = ".$_REQUEST['id']."))";
    }else{
        $sqlSelect .= $sqlSelectCommonExcerpt;
    }
    // id,status,name,slug,category,brand,year,price,price_details,excerpt,createdAt,updatedAt,image

    $sql = <<<EOD
    SELECT `catalogue`.`id`, `catalogue`.`status`, `catalogue`.`name`, `catalogue`.`slug`, `catalogue`.`category`, `catalogue`.`subcategory` AS `brand`, `catalogue`.`detail_1` AS `year`, `catalogue`.`price`, `catalogue`.`price_details`, `catalogue`.`detail_1` AS `excerpt`, `catalogue`.`upload_date` AS `createdAt`, `catalogue`.`upload_date` AS `updatedAt`, `catalogue`.`image_large` AS `image`, `catalogue_subcat`.`id` AS `catalogue_subcat.id`, `catalogue_subcat`.`subcategory` AS `catalogue_subcat.brand`, `catalogue_subcat`.`slug` AS `catalogue_subcat.slug` FROM `catalogue` AS `catalogue` INNER JOIN `catalogue_subcats` AS `catalogue_subcat` ON `catalogue`.`subcategory` = `catalogue_subcat`.`id` WHERE `catalogue`.`id_xtra` = 0 AND `catalogue`.`category` = 2 AND `catalogue`.`status` = 1 GROUP BY `catalogue`.`id`,`catalogue`.`name` ORDER BY `catalogue`.`upload_date` DESC
EOD;
$sql = "SELECT `catalogue`.`id`, `catalogue`.`status`, `catalogue`.`name`, `catalogue`.`slug`";
$sql .= $sqlSelect;
$sql .= ",`catalogue`.`category`";
$sql .= ", `catalogue`.`upload_date` AS `createdAt`, `catalogue`.`upload_date` AS `updatedAt`, `catalogue`.`image_large` AS `image`";
// $sql .= ",`catalogue_subcat`.`id` AS `catalogue_subcat.id`, `catalogue_subcat`.`subcategory` AS `catalogue_subcat.brand`, `catalogue_subcat`.`slug` AS `catalogue_subcat.slug`";
$sql .= ",`catalogue_subcat`.`id` AS `catalogue_subcat_id`, `catalogue_subcat`.`subcategory` AS `catalogue_subcat_brand`, `catalogue_subcat`.`slug` AS `catalogue_subcat_slug`";
// $sql .= ",catalogue_subcat.id, catalogue_subcat.subcategory, catalogue_subcat.slug";
$sql .= " FROM `catalogue` AS `catalogue` INNER JOIN `catalogue_subcats` AS `catalogue_subcat` ON `catalogue`.`subcategory` = `catalogue_subcat`.`id`";
$sql .= " WHERE `catalogue`.`id_xtra` = 0";
$sql .= $sqlWhere;
$sql .= $sqlGroup;
$sql .= $sqlOrder;
$sql .= $qLimit;
}
if($sql){
    $debug .= $sql;

    if (!$result = $mysqli->query($sql)) {
        return "Sorry, the website is experiencing problems.";
        exit;
    }else{
        if(mysqli_num_rows($result) === 0 ){
            return "Nothing to do";
            exit;
        }
    }

    //Initialize array variable
    $dbdata = array();
    $tmpCount = 0;
    //Fetch into associative array
    while ( $row = $result->fetch_assoc())  {
        $tmpCount = $tmpCount + 1;
        // $dbdata[]=$row;
        $row['id'] = intval($row['id']);
        $row['name'] = htmlspecialchars($row['name']);//£
        $row['name'] = removeHtmlChars($row['name']);
        $row['status'] = intval($row['status']);
        $row['category'] = intval($row['category']);
        if($isStockPage){
            $row['brand'] = intval($row['brand']);
            $row['year'] = intval($row['year']);
            if(isset($row['price'])) $row['price'] = intval($row['price']);
        }
        if($isItemListPage){
            $tmpExcerpt = strip_tags($row['excerpt']);
            $tmpExcerpt = removeHtmlChars($tmpExcerpt);
            // $tmpExcerpt = str_replace('&nbsp;'," ",$tmpExcerpt);//space char            
            $row['excerpt'] = implode(' ', array_slice(explode(' ', $tmpExcerpt), 0, 30));
        }
        
        
        if(!$isItemListPage && isset($row['description'])){
            $row['description'] = addcslashes($row['description'],'"');
        }

        $row['catalogue_subcat'] = array();
        $row['catalogue_subcat']['id'] = intval($row['catalogue_subcat_id']);
        $row['catalogue_subcat']['brand'] = $row['catalogue_subcat_brand'];
        $row['catalogue_subcat']['slug'] = $row['catalogue_subcat_slug'];
        $dbdata[]=$row;
        $debug .= '<br>'.$tmpCount.' > '.$row['id'].' | '.$row['name'].' | ';
    }
    
    if($printDebug){
        // echo $debug;
        // echo $sql;
        // echo '<br>------------<br>';
        echo json_encode($dbdata, JSON_PRETTY_PRINT);
    }else{
        return json_encode($dbdata);
    }

    // The script will automatically free the result and close the MySQL
    // connection when it exits, but let's just do it anyways
    $result->free();
    $mysqli->close();
}





// items for sale
// SELECT `catalogue`.`id`, `catalogue`.`status`, `catalogue`.`name`, `catalogue`.`slug`, `catalogue`.`category`, `catalogue`.`subcategory` AS `brand`, `catalogue`.`detail_1` AS `year`, `catalogue`.`price`, `catalogue`.`price_details`, `catalogue`.`description` AS `excerpt`, `catalogue`.`upload_date` AS `createdAt`, `catalogue`.`upload_date` AS `updatedAt`, `catalogue`.`image_large` AS `image`, `catalogue_subcat`.`id` AS `catalogue_subcat.id`, `catalogue_subcat`.`subcategory` AS `catalogue_subcat.brand`, `catalogue_subcat`.`slug` AS `catalogue_subcat.slug` FROM `catalogue` AS `catalogue` INNER JOIN `catalogue_subcats` AS `catalogue_subcat` ON `catalogue`.`subcategory` = `catalogue_subcat`.`id` WHERE `catalogue`.`id_xtra` = 0 AND `catalogue`.`category` = 4 AND `catalogue`.`status` = 1 ORDER BY `catalogue`.`upload_date` DESC;
?>