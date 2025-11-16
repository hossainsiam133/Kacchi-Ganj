<?php
session_start();
if(isset($_SESSION['receipt_order_id'])){
    unset($_SESSION['receipt_order_id']);
}
?>
