<?php
    include 'connection.php';
    session_start();
    $user_id=$_SESSION['user_id'];

    if(!isset($user_id)){
       header('location: ../01_Admin Site/login.php');
    }
    if(isset($_POST['logout'])){
        session_destroy();
        header('location: ../01_Admin Site/login.php');
    }
    
    // Fetch all messages from massage table
    $messages_query = mysqli_query($conn, "SELECT * FROM `massage` ORDER BY user_id DESC") or die('query failed');
    $total_messages = mysqli_num_rows($messages_query);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>"> 
    <title>কাচ্চি গঞ্জ</title>
  </head>
  <body>
    <header class="header">
     <?php include 'nav.php'; ?>
      <div class="section__container header__container" id="home">
        <div class="header__image">
          <img src="assets/header.png" alt="header" />
        </div>
        <div class="header__content">
          <h2>"📞 যেকোনো তথ্য বা জিজ্ঞাসার জন্য যোগাযোগ করুন কাচ্চি গঞ্জ-
                এর সাথে আমাদের রেস্টুরেন্টের ফোন নম্বর বা ইমেইল ঠিকানায়।"</h2>
          <h1>যোগাযোগ করুন </h1>
        </div>
      </div>
    </header>

    <!-- Messages Section -->
    <section class="messages-container">
      <div class="messages-header">
        <div class="messages-title-box">
          <h1 class="messages-title">📧 All Messages</h1>
          <p class="messages-subtitle">Total Messages: <span class="badge"><?php echo $total_messages; ?></span></p>
        </div>
      </div>

      <!-- Messages Grid -->
      <div class="messages-grid">
        <?php
          if($total_messages > 0) {
            while($message = mysqli_fetch_assoc($messages_query)) {
              $user = htmlspecialchars($message['name']);
              $email = htmlspecialchars($message['email']);
              $number = htmlspecialchars($message['number']);
              $msg_text = htmlspecialchars($message['massage']);
              $user_id_msg = intval($message['user_id']);
        ?>
        <div class="message-card">
          <div class="message-header-card">
            <div class="message-avatar">
              <?php echo strtoupper(substr($user, 0, 1)); ?>
            </div>
            <div class="message-meta">
              <h3 class="message-name"><?php echo $user; ?></h3>
              <p class="message-uid">User ID: <?php echo $user_id_msg; ?></p>
            </div>
          </div>
          
          <div class="message-content">
            <p class="message-text"><?php echo $msg_text; ?></p>
          </div>

          <div class="message-footer-card">
            <div class="message-contact">
              <a href="mailto:<?php echo $email; ?>" class="contact-link email-link" title="Send email">
                <i class="ri-mail-line"></i> <?php echo $email; ?>
              </a>
            </div>
            <div class="message-contact">
              <a href="tel:<?php echo $number; ?>" class="contact-link phone-link" title="Call">
                <i class="ri-phone-line"></i> <?php echo $number; ?>
              </a>
            </div>
          </div>
        </div>
        <?php 
            }
          } else {
            echo '<div class="no-messages"><p>No messages yet.</p></div>';
          }
        ?>
      </div>
    </section>

    <style>
      /* Messages Section Styling */
      .messages-container {
        max-width: 1200px;
        margin: 60px auto;
        padding: 20px;
      }

      .messages-header {
        text-align: center;
        margin-bottom: 50px;
      }

      .messages-title-box {
        display: inline-block;
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        color: white;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(37, 99, 235, 0.2);
      }

      .messages-title {
        margin: 0;
        font-size: 2.2rem;
        font-weight: 700;
        letter-spacing: -0.5px;
      }

      .messages-subtitle {
        margin: 10px 0 0 0;
        font-size: 1rem;
        opacity: 0.95;
        letter-spacing: 0.3px;
      }

      .badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.25);
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
        margin-left: 5px;
      }

      /* Messages Grid */
      .messages-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
      }

      /* Message Card */
      .message-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #f0f2f5;
      }

      .message-card:hover {
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.12);
        transform: translateY(-4px);
      }

      .message-header-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        border-bottom: 1px solid #f0f2f5;
        background: linear-gradient(135deg, #f8fafc 0%, #f0f2f5 100%);
      }

      .message-avatar {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 18px;
      }

      .message-meta {
        flex: 1;
      }

      .message-name {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #0f172a;
      }

      .message-uid {
        margin: 2px 0 0 0;
        font-size: 0.8rem;
        color: #6b7280;
      }

      .message-content {
        padding: 16px;
        min-height: 80px;
        display: flex;
        align-items: center;
      }

      .message-text {
        margin: 0;
        color: #334155;
        font-size: 0.95rem;
        line-height: 1.5;
        word-break: break-word;
      }

      .message-footer-card {
        padding: 14px 16px;
        border-top: 1px solid #f0f2f5;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 10px;
      }

      .message-contact {
        display: flex;
        align-items: center;
      }

      .contact-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #2563eb;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
        word-break: break-all;
      }

      .contact-link:hover {
        color: #1e40af;
        text-decoration: underline;
      }

      .contact-link i {
        font-size: 14px;
        flex-shrink: 0;
      }

      .email-link:before {
        content: '';
      }

      .phone-link:before {
        content: '';
      }

      .no-messages {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
        font-size: 1.1rem;
      }

      /* Responsive */
      @media (max-width: 768px) {
        .messages-grid {
          grid-template-columns: 1fr;
        }

        .messages-title-box {
          padding: 20px 25px;
        }

        .messages-title {
          font-size: 1.6rem;
        }

        .messages-container {
          margin: 40px auto;
        }
      }

      /* Animation for cards */
      @keyframes slideInUp {
        from {
          opacity: 0;
          transform: translateY(20px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .message-card {
        animation: slideInUp 0.5s ease-out;
      }

      .message-card:nth-child(1) { animation-delay: 0.1s; }
      .message-card:nth-child(2) { animation-delay: 0.15s; }
      .message-card:nth-child(3) { animation-delay: 0.2s; }
      .message-card:nth-child(4) { animation-delay: 0.25s; }
      .message-card:nth-child(n+5) { animation-delay: 0.3s; }
    </style>

  

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="main.js"></script>
  </body>
</html>

