<?
include_once("price_func.php");
    $zsite['navi']=$cms->sitenavi($_GET['x']);
    $zsite['middle'].=price($_GET['x']);
?>