document.getElementById('contactForm').addEventListener('submit', function(event) {
  let isValid = true;

  // Clear previous errors
  ['name', 'email', 'subject', 'message'].forEach(field => {
    document.getElementById(`${field}_error`).textContent = '';
  });

  // Get values
  const name = document.getElementById('contact_name').value.trim();
  const email = document.getElementById('contact_email').value.trim();
  const subject = document.getElementById('contact_subject').value.trim();
  const message = document.getElementById('contact_message').value.trim();

  // Regex patterns
  const namePattern = /^[A-Za-z\s]+$/;
  const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  const subjectPattern = /^[A-Za-z\s]{15,}$/;

  // Name validation
  if (!name) {
    document.getElementById('name_error').textContent = "Name is required.";
    isValid = false;
  } else if (!namePattern.test(name)) {
    document.getElementById('name_error').textContent = "Name can only contain letters and spaces.";
    isValid = false;
  }

  // Email validation
  if (!email) {
    document.getElementById('email_error').textContent = "Email is required.";
    isValid = false;
  } else if (!emailPattern.test(email)) {
    document.getElementById('email_error').textContent = "Please enter a valid email address.";
    isValid = false;
  }

  // Subject validation
  if (!subject) {
    document.getElementById('subject_error').textContent = "Subject is required.";
    isValid = false;
  } else if (!subjectPattern.test(subject)) {
    document.getElementById('subject_error').textContent = "Subject must be at least 15 letters long and contain only letters and spaces.";
    isValid = false;
  }

  // Message validation
  if (!message) {
    document.getElementById('message_error').textContent = "Message is required.";
    isValid = false;
  } else if (message.length < 10) {
    document.getElementById('message_error').textContent = "Message is too short. Please provide more detail.";
    isValid = false;
  }

  // If any validation failed, prevent submission
  if (!isValid) {
    event.preventDefault();
  }
});