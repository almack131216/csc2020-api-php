<?php

if(isset($_REQUEST['rename'])){
    $rename = true;
    echo "<h1>item rename + slugify</h1>";
}else{
   $rename = false;
   echo "<h1>item slugify</h1>";
}

if(isset($_REQUEST['category'])){
    $categoryId = $_REQUEST['category'];    
}else{
    $categoryId = 2;
}
echo "<h1>category ".$categoryId."</h1>";

function create_slug_prep_items($getYear,$getName) { 
    $tmp = $getName;
    if($getYear!='' && $getYear!='0' && $getYear!=0) $tmp .= '-'.$getYear;
    $tmp = strtolower($tmp);
    return create_slug($tmp);
 }

 function strip_crap($getName){
    $tmp = str_replace('&nbsp;'," ",$getName);//space char 
    $tmp = str_replace('&amp;',"&",$tmp);//& char
    $tmp = str_replace('#39;',"'",$tmp);//& char    
    return $tmp;
 }



// Now, let's fetch five random items and output their names to a list.
// We'll add less error handling here as you can do that on your own now
$sql = "SELECT id,name,detail_1 FROM catalogue WHERE category=$categoryId and name!=''";
if(!$rename) $sql .= " AND slug=''";//rename regardless of slug
$sql .= " ORDER BY name ASC LIMIT 100";
if (!$result = $mysqli->query($sql)) {
    echo "Sorry, the website is experiencing problems.";
    exit;
}else{
    if(mysqli_num_rows($result) === 0 ){
        echo "Nothing to do";
        exit;
    }
}

// Print our 5 random items in a list, and link to each item
echo "<table>\n";
while ($item = $result->fetch_assoc()) {
    $id = $item['id'];
    if($rename){
        $renamed = strip_crap($item['name']);
    }
    
    $slugged = create_slug_prep_items($item['detail_1'],$item['name']);

    echo "<tr>";
    echo "<td><a href='" . $_SERVER['SCRIPT_FILENAME'] . "?slug=" . $item['name'] . "'>".$id."</a></td>\n";

    echo "<td>". $item['detail_1'] ."</td>";
    echo "<td>".$item['name']."</td>";
    echo "<td>".$slugged."</td>";
    echo "</tr>\n";

    $sqlUpdate = "UPDATE catalogue SET";
    if($renamed) $sqlUpdate .= " name='$renamed',";
    $sqlUpdate .= " slug='$slugged' WHERE id=$id";
    if ($r = $mysqli->query($sqlUpdate)) {
        echo "<tr><td colspan=4><strong style='color:green'>updated</strong></td></tr>";
    }
}
echo "</table>\n";

// The script will automatically free the result and close the MySQL
// connection when it exits, but let's just do it anyways
$result->free();
$mysqli->close();
?>