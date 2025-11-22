<?php
// Simple receipt generator without external library (using built-in capabilities)
// This creates a downloadable PDF receipt for the order

require_once 'connection.php';
require_once 'auth_helper.php';

session_start();
require_user_login();
$user_id = get_user_id();

if(!isset($_GET['order_id'])){
    die('Order ID not provided');
}

$order_id = intval($_GET['order_id']);

// Fetch order details
$order_query = mysqli_query($conn, "SELECT * FROM `order` WHERE id='$order_id'") or die('query failed');
if(mysqli_num_rows($order_query) === 0){
    die('Order not found');
}

$order = mysqli_fetch_assoc($order_query);

// Security: Verify the order belongs to the logged-in user
if($order['user_id'] != $user_id) {
    die('Unauthorized: This order does not belong to you');
}

$user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id='".$order['user_id']."'") or die('query failed');
$user = mysqli_fetch_assoc($user_query);

// Generate HTML for receipt
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 15px; }
        .header h1 { margin: 0; color: #2563eb; font-size: 28px; }
        .header p { margin: 5px 0; font-size: 12px; color: #666; }
        .section { margin-bottom: 20px; }
        .section-title { font-weight: bold; color: #2563eb; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; }
        .label { font-weight: bold; color: #555; }
        .value { color: #333; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .items-table th { background-color: #f5f5f5; padding: 8px; text-align: left; font-weight: bold; font-size: 12px; border-bottom: 1px solid #ddd; }
        .items-table td { padding: 8px; border-bottom: 1px solid #eee; font-size: 12px; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .total-amount { font-size: 16px; color: #2563eb; }
        .footer { text-align: center; margin-top: 20px; font-size: 11px; color: #999; border-top: 1px solid #ddd; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kacchi Ganj</h1>
            <p>Order Receipt</p>
            <p>Receipt ID: #' . str_pad($order['id'], 6, '0', STR_PAD_LEFT) . '</p>
        </div>

        <div class="section">
            <div class="section-title">Order Information</div>
            <div class="row">
                <span class="label">Order Date:</span>
                <span class="value">' . htmlspecialchars($order['placed_on']) . '</span>
            </div>
            <div class="row">
                <span class="label">Order ID:</span>
                <span class="value">#' . str_pad($order['id'], 6, '0', STR_PAD_LEFT) . '</span>
            </div>
            <div class="row">
                <span class="label">Payment Method:</span>
                <span class="value">' . htmlspecialchars(ucfirst($order['method'])) . '</span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Customer Information</div>
            <div class="row">
                <span class="label">Name:</span>
                <span class="value">' . htmlspecialchars($order['name']) . '</span>
            </div>
            <div class="row">
                <span class="label">Email:</span>
                <span class="value">' . htmlspecialchars($order['email']) . '</span>
            </div>
            <div class="row">
                <span class="label">Mobile:</span>
                <span class="value">' . htmlspecialchars($order['number']) . '</span>
            </div>
            <div class="row">
                <span class="label">Delivery Address:</span>
                <span class="value">' . htmlspecialchars($order['address']) . '</span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Order Details</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>' . htmlspecialchars($order['total_products']) . '</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Payment Summary</div>
            <div class="row">
                <span class="label">Total Amount:</span>
                <span class="value total-amount">৳' . htmlspecialchars($order['total_price']) . '</span>
            </div>
            <div class="row">
                <span class="label">Payment Status:</span>
                <span class="value">' . htmlspecialchars(ucfirst($order['payment_status'] ?? 'pending')) . '</span>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for your order! Please keep this receipt for your records.</p>
            <p>For support, contact us at support@kacchiganj.com</p>
            <p>Generated on: ' . date('Y-m-d H:i:s') . '</p>
        </div>
    </div>
</body>
</html>
';

// Set headers for PDF download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Receipt_' . str_pad($order['id'], 6, '0', STR_PAD_LEFT) . '.pdf"');

// Use a simple HTML to PDF conversion or fallback to HTML download
// For now, we'll send as HTML that can be printed to PDF
// If you want proper PDF generation, install TCPDF or use a service like dompdf

// Simple approach: send as HTML and let browser handle print-to-PDF
header('Content-Type: text/html; charset=utf-8');
header('Content-Disposition: attachment; filename="Receipt_' . str_pad($order['id'], 6, '0', STR_PAD_LEFT) . '.html"');
echo $html;
?>
