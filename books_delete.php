<?php
session_start();
require 'mysql/config.php';

if(!empty($_GET['rmid'])) {
$query_product = mysqli_query($conn, "SELECT * FROM books WHERE id='{$_GET['bkstatus']}'"); 
$result = mysqli_fetch_assoc($query_product);
$query = mysqli_query($conn , "DELETE FROM books WHERE id='{$_GET['bkstatus']}'") or die ('query failed');

if($query) {
    $_SESSION['message'] = 'Product Deleted success';
    header('location: ' . $base_url . '/books_list.php');
    }else{
    $_SESSION['message'] = 'Product could not be deleted!'; 
    header('location: ' . $base_url . '/books_list.php');
    }
}