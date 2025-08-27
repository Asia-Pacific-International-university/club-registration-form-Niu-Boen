<?php

use const Dom\VALIDATION_ERR;
// Club Registration Form Processing
// TODO: Add your PHP processing code here starting in Step 3

/* 
Step 3 Requirements:
- Process form data using $_POST
- Display submitted information back to user
- Handle name, email, and club fields
*/
echo"<h2>Your Registration Details</h2>";
echo "<p><strong>Name:</strong> " . htmlspecialchars($_POST['name']) . "</p>";
echo "<p><strong>Email:</strong> " . htmlspecialchars($_POST['email']) . "</p>";
echo "<p><strong>Club:</strong> ". htmlspecialchars($_POST["club"]) . "</p>";
/*
Step 4 Requirements:
- Add validation for all fields
- Check for empty fields
- Validate email format
- Display appropriate error messages
*/

/*
Step 5 Requirements:
- Store registration data in arrays
- Display list of all registrations
- Use loops to process array data
*/

/*
Step 6 Requirements:
- Add enhanced features like:
  - File storage for persistence
  - Additional form fields
  - Better error handling
  - Search functionality
*/

?>
