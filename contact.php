<?php
$pageTitle = "Contact Page";
require("./functions.php");
require "./header.php";
?>

<?php if (isset($_GET['success'])): ?>
  <p style="color: green; font-weight: bold;text-align: center;margin: 1.5rem 0;">✅ Message sent successfully! Redirecting...</p>

  <script>
    // Redirect after 3 seconds
    setTimeout(() => {
      window.location.href = "index.php"; // or any page you want
    }, 3000);
  </script>

<?php elseif (isset($_GET['error'])): ?>
  <?php
    $errorMessages = [
      'invalid_email' => '❌ Invalid email address.',
      'mailer' => '❌ Failed to send message. Please try again later.',
      'invalid_request' => '❌ Invalid form submission.'
    ];
    $errorKey = $_GET['error'];
    echo '<p style="color: red; font-weight: bold;">' . ($errorMessages[$errorKey] ?? '❌ An unknown error occurred.') . '</p>';
  ?>
<?php endif; ?>

<!-- <form action="send_contact_mail.php" method="post">
  <input type="text" name="name" placeholder="Your Name" required><br>
  <input type="email" name="email" placeholder="Your Email" required><br>
  <input type="text" name="subject" placeholder="Subject" required><br>
  <textarea name="message" placeholder="Your Message" rows="5" cols="20" required style="width:100%"></textarea><br>
  <button type="submit">Send Message</button>
</form> -->

<form id="contactForm" action="send_contact_mail.php" method="post" novalidate>
  <input type="text" name="contact_name" id="contact_name" placeholder="Your Name" required><br>
  <small class="error-message" id="name_error"></small><br>

  <input type="email" name="contact_email" id="contact_email" placeholder="Your Email" required><br>
  <small class="error-message" id="email_error"></small><br>

  <input type="text" name="contact_subject" id="contact_subject" placeholder="Subject" required><br>
  <small class="error-message" id="subject_error"></small><br>

  <textarea name="contact_message" id="contact_message" placeholder="Your Message" rows="5" cols="20" required style="width:100%"></textarea><br>
  <small class="error-message" id="message_error"></small><br>

  <button type="submit">Send Message</button>
</form>

<?php require './footer.php'?>