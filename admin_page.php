<?php
require_once 'tcpdf/tcpdf.php';
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

// Function to generate PDF report
function generatePDFReport() {
    global $conn;

    // Create new TCPDF instance
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Admin');
    $pdf->SetTitle('Admin Report');
    $pdf->SetSubject('Report');
    $pdf->SetKeywords('Report, Admin');

    // Add a page
    $pdf->AddPage();

    // Fetch statistics from database
    $total_pendings = getTotalPendings($conn);
    $total_completed = getTotalCompleted($conn);
    $number_of_orders = getNumberOfOrders($conn);
    $number_of_products = getNumberOfProducts($conn);
    $number_of_users = getNumberOfUsers($conn, 'user');
    $number_of_admins = getNumberOfUsers($conn, 'admin');
    $number_of_account = getTotalAccounts($conn);
    $number_of_messages = getNumberOfMessages($conn);

    // Set some content to display in PDF
    $content = '
    <h1>Admin Report</h1>
    <p>This report contains various statistics.</p>
    <ul>
        <li>Total Pendings: $' . $total_pendings . '</li>
        <li>Completed Payments: $' . $total_completed . '</li>
        <li>Orders Placed: ' . $number_of_orders . '</li>
        <li>Products Added: ' . $number_of_products . '</li>
        <li>Normal Users: ' . $number_of_users . '</li>
        <li>Admin Users: ' . $number_of_admins . '</li>
        <li>Total Accounts: ' . $number_of_account . '</li>
        <li>New Messages: ' . $number_of_messages . '</li>
    </ul>
    ';

    // Output the HTML content
    $pdf->writeHTML($content, true, false, true, false, '');

    // Close and output PDF document
    $pdf->Output('admin_report.pdf', 'D'); // D for download

    exit();
}

// Helper functions to fetch data from database
function getTotalPendings($conn) {
    $total_pendings = 0;
    $select_pending = mysqli_query($conn, "SELECT SUM(total_price) AS total_pendings FROM `orders` WHERE payment_status = 'pending'") or die(mysqli_error($conn));
    $result = mysqli_fetch_assoc($select_pending);
    if ($result['total_pendings']) {
        $total_pendings = $result['total_pendings'];
    }
    return $total_pendings;
}

function getTotalCompleted($conn) {
    $total_completed = 0;
    $select_completed = mysqli_query($conn, "SELECT SUM(total_price) AS total_completed FROM `orders` WHERE payment_status = 'completed'") or die(mysqli_error($conn));
    $result = mysqli_fetch_assoc($select_completed);
    if ($result['total_completed']) {
        $total_completed = $result['total_completed'];
    }
    return $total_completed;
}

function getNumberOfOrders($conn) {
    $select_orders = mysqli_query($conn, "SELECT COUNT(*) AS number_of_orders FROM `orders`") or die(mysqli_error($conn));
    $result = mysqli_fetch_assoc($select_orders);
    return $result['number_of_orders'];
}

function getNumberOfProducts($conn) {
    $select_products = mysqli_query($conn, "SELECT COUNT(*) AS number_of_products FROM `products`") or die(mysqli_error($conn));
    $result = mysqli_fetch_assoc($select_products);
    return $result['number_of_products'];
}

function getNumberOfUsers($conn, $user_type) {
    $select_users = mysqli_query($conn, "SELECT COUNT(*) AS number_of_users FROM `users` WHERE user_type = '$user_type'") or die(mysqli_error($conn));
    $result = mysqli_fetch_assoc($select_users);
    return $result['number_of_users'];
}

function getTotalAccounts($conn) {
    $select_account = mysqli_query($conn, "SELECT COUNT(*) AS total_accounts FROM `users`") or die(mysqli_error($conn));
    $result = mysqli_fetch_assoc($select_account);
    return $result['total_accounts'];
}

function getNumberOfMessages($conn) {
    $select_messages = mysqli_query($conn, "SELECT COUNT(*) AS number_of_messages FROM `message`") or die(mysqli_error($conn));
    $result = mysqli_fetch_assoc($select_messages);
    return $result['number_of_messages'];
}

// Handle report generation if button is clicked
if (isset($_POST['generate_report'])) {
    generatePDFReport();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Panel</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="dashboard">
   <h1 class="titledash">Dashboard</h1>
   <div class="box-container">
      <div class="box">
         <h3>$<?php echo getTotalPendings($conn); ?>/-</h3>
         <p>Total Pendings</p>
      </div>
      <div class="box">
         <h3>$<?php echo getTotalCompleted($conn); ?>/-</h3>
         <p>Completed Payments</p>
      </div>
      <div class="box">
         <h3><?php echo getNumberOfOrders($conn); ?></h3>
         <p>Order Placed</p>
      </div>
      <div class="box">
         <h3><?php echo getNumberOfProducts($conn); ?></h3>
         <p>Products Added</p>
      </div>
      <div class="box">
         <h3><?php echo getNumberOfUsers($conn, 'user'); ?></h3>
         <p>Normal Users</p>
      </div>
      <div class="box">
         <h3><?php echo getNumberOfUsers($conn, 'admin'); ?></h3>
         <p>Admin Users</p>
      </div>
      <div class="box">
         <h3><?php echo getTotalAccounts($conn); ?></h3>
         <p>Total Accounts</p>
      </div>
      <div class="box">
         <h3><?php echo getNumberOfMessages($conn); ?></h3>
         <p>New Messages</p>
      </div>
   </div>
   <form method="post" action="">
      <input type="submit" name="generate_report" value="Generate Report" class="btne">
   </form>
</section>

<script src="js/admin_script.js"></script>

</body>
</html>
