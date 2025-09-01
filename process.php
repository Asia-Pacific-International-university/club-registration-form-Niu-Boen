<?php
// Step 4: Data Validation & Step 5: Array Storage
session_start();

// File storage for persistence (Step 6)
define('DATA_FILE', 'registrations.dat');

// Load registrations from file
function loadRegistrations() {
    return file_exists(DATA_FILE) ? unserialize(file_get_contents(DATA_FILE)) : [];
}

// Save registrations to file
function saveRegistrations($data) {
    file_put_contents(DATA_FILE, serialize($data));
}

// Initialize storage
$registrations = loadRegistrations();

// Step 3: Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $club = $_POST['club'] ?? '';
    $membership = $_POST['membership'] ?? 'basic';
    $interests = $_POST['interests'] ?? [];
    $message = trim($_POST['message'] ?? '');

    // Step 4: Validation
    if (empty($name)) $errors[] = "Name is required";
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($club)) $errors[] = "Please select a club";

    if (empty($errors)) {
        // Step 5: Store in array
        $registration = [
            'name' => $name,
            'email' => $email,
            'club' => $club,
            'membership' => $membership,
            'interests' => $interests,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $registrations[] = $registration;
        saveRegistrations($registrations);
        
        // Step 3: Display submitted info
        displaySuccess($registration);
    } else {
        displayErrors($errors);
    }
}

// Step 6: Search functionality
elseif (isset($_GET['action']) && $_GET['action'] === 'search' && isset($_GET['query'])) {
    $query = strtolower($_GET['query']);
    $results = array_filter($registrations, function($reg) use ($query) {
        return stripos($reg['name'], $query) !== false || 
               stripos($reg['email'], $query) !== false ||
               stripos($reg['club'], $query) !== false;
    });
    displayRegistrations($results, "Search Results for: $query");
}

// View all registrations
elseif (isset($_GET['action']) && $_GET['action'] === 'view') {
    displayRegistrations($registrations, "All Registrations");
}

// Default: show form
else {
    header('Location: index.html');
    exit;
}

// Custom functions (Step 6)
function displaySuccess($reg) {
    echo "<h2>Registration Successful!</h2>";
    echo "<p><strong>Name:</strong> {$reg['name']}</p>";
    echo "<p><strong>Email:</strong> {$reg['email']}</p>";
    echo "<p><strong>Club:</strong> " . ucfirst($reg['club']) . "</p>";
    echo "<p><strong>Membership:</strong> " . ucfirst($reg['membership']) . "</p>";
    if (!empty($reg['interests'])) {
        echo "<p><strong>Interests:</strong> " . implode(', ', $reg['interests']) . "</p>";
    }
    if (!empty($reg['message'])) {
        echo "<p><strong>Message:</strong> {$reg['message']}</p>";
    }
    echo '<p><a href="index.html">Register Another</a> | ';
    echo '<a href="process.php?action=view">View All</a></p>';
}

function displayErrors($errors) {
    echo "<h2>Error!</h2>";
    foreach ($errors as $error) {
        echo "<p style='color: red;'>$error</p>";
    }
    echo '<p><a href="index.html">Go Back</a></p>';
}

function displayRegistrations($data, $title) {
    echo "<h2>$title</h2>";
    echo '<form action="process.php" method="GET" style="margin: 20px 0;">
            <input type="hidden" name="action" value="search">
            <input type="text" name="query" placeholder="Search...">
            <button type="submit">Search</button>
          </form>';

    if (empty($data)) {
        echo "<p>No registrations found.</p>";
    } else {
        echo "<table border='1' style='width:100%; border-collapse: collapse;'>";
        echo "<tr><th>#</th><th>Name</th><th>Email</th><th>Club</th><th>Membership</th><th>Date</th></tr>";
        
        // Step 5: Use loop to process array data
        foreach ($data as $index => $reg) {
            echo "<tr>
                    <td>" . ($index + 1) . "</td>
                    <td>{$reg['name']}</td>
                    <td>{$reg['email']}</td>
                    <td>" . ucfirst($reg['club']) . "</td>
                    <td>" . ucfirst($reg['membership']) . "</td>
                    <td>{$reg['timestamp']}</td>
                  </tr>";
        }
        echo "</table>";
    }
    echo '<p><a href="index.html">New Registration</a></p>';
}
?>