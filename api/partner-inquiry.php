<?php
/**
 * AlfaisalX - Partnership Inquiry API Endpoint
 * 
 * Handles form submissions from the become-partner.php page.
 */

header('Content-Type: application/json');

// Load the email service
require_once dirname(__DIR__) . '/includes/EmailService.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
    exit;
}

// Get form data
$formData = [
    'name' => trim($_POST['name'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'organization' => trim($_POST['organization'] ?? ''),
    'role' => trim($_POST['role'] ?? ''),
    'partnership_type' => trim($_POST['partnership_type'] ?? ''),
    'areas_of_interest' => trim($_POST['areas_of_interest'] ?? ''),
    'message' => trim($_POST['message'] ?? '')
];

// Validation
$errors = [];

if (empty($formData['name'])) {
    $errors[] = 'Name is required';
}

if (empty($formData['email'])) {
    $errors[] = 'Email is required';
} elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email address';
}

if (empty($formData['organization'])) {
    $errors[] = 'Organization is required';
}

if (empty($formData['partnership_type'])) {
    $errors[] = 'Partnership type is required';
}

if (empty($formData['message'])) {
    $errors[] = 'Message is required';
}

// Check for validation errors
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $errors
    ]);
    exit;
}

// Send email
try {
    $emailService = new EmailService();
    $result = $emailService->sendPartnershipInquiry($formData);
    
    if ($result['success']) {
        // For form submissions, redirect back with success message
        if (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            // AJAX request - return JSON
            echo json_encode([
                'success' => true,
                'message' => 'Thank you for your inquiry! We will respond within 2 business days.'
            ]);
        } else {
            // Regular form submission - redirect with success
            header('Location: ' . dirname($_SERVER['HTTP_REFERER'] ?? '/alfaisalx/partners/become-partner.php') . '/become-partner.php?success=1');
            exit;
        }
    } else {
        // Log the error
        error_log('Partnership inquiry email failed: ' . $result['message']);
        
        http_response_code(500);
        if (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to send inquiry. Please try again or contact us directly.'
            ]);
        } else {
            header('Location: ' . dirname($_SERVER['HTTP_REFERER'] ?? '/alfaisalx/partners/become-partner.php') . '/become-partner.php?error=1');
            exit;
        }
    }
} catch (Exception $e) {
    error_log('Partnership inquiry exception: ' . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An unexpected error occurred. Please try again later.'
    ]);
}
