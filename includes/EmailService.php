<?php
/**
 * AlfaisalX - Email Service
 * 
 * Handles email sending using SendGrid API.
 */

class EmailService {
    private $apiKey;
    private $fromEmail;
    private $fromName;
    
    public function __construct() {
        $this->loadEnv();
        $this->apiKey = $_ENV['SENDGRID_API_KEY'] ?? getenv('SENDGRID_API_KEY');
        $this->fromEmail = $_ENV['EMAIL_FROM'] ?? getenv('EMAIL_FROM') ?? 'noreply@alfaisalx.edu';
        $this->fromName = $_ENV['EMAIL_FROM_NAME'] ?? getenv('EMAIL_FROM_NAME') ?? 'AlfaisalX Center';
    }
    
    /**
     * Load environment variables from .env file
     */
    private function loadEnv() {
        $envFile = dirname(__DIR__) . '/.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                // Skip comments
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                // Parse key=value
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }
    }
    
    /**
     * Send an email using SendGrid API
     * 
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $htmlContent HTML email body
     * @param string $textContent Plain text email body (optional)
     * @param string $replyTo Reply-to email address (optional)
     * @return array Response with success status and message
     */
    public function send($to, $subject, $htmlContent, $textContent = '', $replyTo = null) {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'SendGrid API key not configured'
            ];
        }
        
        // Build email data
        $data = [
            'personalizations' => [
                [
                    'to' => [
                        ['email' => $to]
                    ],
                    'subject' => $subject
                ]
            ],
            'from' => [
                'email' => $this->fromEmail,
                'name' => $this->fromName
            ],
            'content' => []
        ];
        
        // Add plain text content if provided
        if (!empty($textContent)) {
            $data['content'][] = [
                'type' => 'text/plain',
                'value' => $textContent
            ];
        }
        
        // Add HTML content
        $data['content'][] = [
            'type' => 'text/html',
            'value' => $htmlContent
        ];
        
        // Add reply-to if provided
        if ($replyTo) {
            $data['reply_to'] = ['email' => $replyTo];
        }
        
        // Send request to SendGrid API
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.sendgrid.com/v3/mail/send',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return [
                'success' => false,
                'message' => 'CURL error: ' . $error
            ];
        }
        
        // SendGrid returns 202 for successful send
        if ($httpCode === 202) {
            return [
                'success' => true,
                'message' => 'Email sent successfully'
            ];
        }
        
        return [
            'success' => false,
            'message' => 'SendGrid error (HTTP ' . $httpCode . '): ' . $response
        ];
    }
    
    /**
     * Send a partnership inquiry notification email
     */
    public function sendPartnershipInquiry($formData) {
        $toEmail = $_ENV['EMAIL_TO'] ?? getenv('EMAIL_TO') ?? 'alfaisalx@alfaisal.edu';
        
        $subject = 'New Partnership Inquiry from ' . htmlspecialchars($formData['organization']);
        
        // Build HTML email
        $html = $this->buildPartnershipEmailHtml($formData);
        
        // Build plain text version
        $text = $this->buildPartnershipEmailText($formData);
        
        // Send with reply-to set to the inquirer's email
        return $this->send($toEmail, $subject, $html, $text, $formData['email']);
    }
    
    /**
     * Build HTML email for partnership inquiry
     */
    private function buildPartnershipEmailHtml($data) {
        $partnershipTypes = [
            'industry' => ['label' => 'Industry Partner', 'color' => '#0ea5e9', 'icon' => '🏭'],
            'government' => ['label' => 'Government Partner', 'color' => '#8b5cf6', 'icon' => '🏛️'],
            'academic' => ['label' => 'Academic Partner', 'color' => '#10b981', 'icon' => '🎓'],
            'technology' => ['label' => 'Technology Partner', 'color' => '#f59e0b', 'icon' => '💻'],
            'other' => ['label' => 'Other', 'color' => '#6b7280', 'icon' => '🤝']
        ];
        
        $areasOfInterest = [
            'robotics' => ['label' => 'Robotics & Autonomous Systems', 'icon' => '🤖'],
            'uavs' => ['label' => 'UAVs & Aerial Autonomy', 'icon' => '🚁'],
            'ai' => ['label' => 'Agentic AI & Intelligent Workflows', 'icon' => '🧠'],
            'health' => ['label' => 'Health & Medical Robotics', 'icon' => '🏥'],
            'multiple' => ['label' => 'Multiple Areas', 'icon' => '📊']
        ];
        
        $partnerInfo = $partnershipTypes[$data['partnership_type']] ?? ['label' => $data['partnership_type'], 'color' => '#6b7280', 'icon' => '🤝'];
        $areaInfo = $areasOfInterest[$data['areas_of_interest']] ?? ['label' => ($data['areas_of_interest'] ?? 'Not specified'), 'icon' => '📋'];
        
        $currentDate = date('F j, Y \a\t g:i A');
        
        return '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Partnership Inquiry</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, sans-serif; background-color: #0f172a; color: #e2e8f0;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #0f172a;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; max-width: 600px;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 50%, #ec4899 100%); border-radius: 16px 16px 0 0; padding: 40px 40px 30px;">
                            <table role="presentation" width="100%">
                                <tr>
                                    <td>
                                        <h1 style="margin: 0 0 8px 0; font-size: 28px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">
                                            Alfaisal<span style="background: linear-gradient(90deg, #38bdf8, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">X</span>
                                        </h1>
                                        <p style="margin: 0; font-size: 14px; color: rgba(255,255,255,0.8);">Center for Cognitive Robotics & Autonomous Agents</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Alert Banner -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 20px 40px; border-bottom: 1px solid #334155;">
                            <table role="presentation" width="100%">
                                <tr>
                                    <td style="background: linear-gradient(90deg, rgba(14, 165, 233, 0.15), rgba(139, 92, 246, 0.15)); border-radius: 12px; padding: 16px 20px; border-left: 4px solid #0ea5e9;">
                                        <p style="margin: 0; font-size: 18px; font-weight: 700; color: #ffffff;">🎯 New Partnership Inquiry</p>
                                        <p style="margin: 8px 0 0 0; font-size: 13px; color: #94a3b8;">' . $currentDate . '</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Main Content -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 30px 40px;">
                            
                            <!-- Contact Card -->
                            <table role="presentation" width="100%" style="background-color: #0f172a; border-radius: 12px; margin-bottom: 24px; border: 1px solid #334155;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <table role="presentation" width="100%">
                                            <tr>
                                                <td width="60" style="vertical-align: top;">
                                                    <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #0ea5e9, #8b5cf6); border-radius: 14px; text-align: center; line-height: 56px; font-size: 24px; font-weight: 700; color: white;">
                                                        ' . strtoupper(substr($data['name'], 0, 1)) . '
                                                    </div>
                                                </td>
                                                <td style="padding-left: 16px; vertical-align: top;">
                                                    <h2 style="margin: 0 0 4px 0; font-size: 20px; font-weight: 700; color: #ffffff;">' . htmlspecialchars($data['name']) . '</h2>
                                                    <p style="margin: 0 0 4px 0; font-size: 14px; color: #94a3b8;">' . htmlspecialchars($data['role'] ?? 'No role specified') . '</p>
                                                    <p style="margin: 0; font-size: 15px; color: #0ea5e9; font-weight: 600;">' . htmlspecialchars($data['organization']) . '</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 24px 24px;">
                                        <a href="mailto:' . htmlspecialchars($data['email']) . '" style="display: inline-block; background: linear-gradient(135deg, #0ea5e9, #0284c7); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600;">
                                            ✉️ Reply to ' . htmlspecialchars($data['email']) . '
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Partnership Details -->
                            <table role="presentation" width="100%" style="margin-bottom: 24px;">
                                <tr>
                                    <td width="50%" style="padding-right: 12px; vertical-align: top;">
                                        <div style="background-color: #0f172a; border-radius: 12px; padding: 20px; border: 1px solid #334155; height: 100%;">
                                            <p style="margin: 0 0 8px 0; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #64748b;">Partnership Type</p>
                                            <p style="margin: 0; font-size: 16px; font-weight: 600; color: ' . $partnerInfo['color'] . ';">
                                                ' . $partnerInfo['icon'] . ' ' . htmlspecialchars($partnerInfo['label']) . '
                                            </p>
                                        </div>
                                    </td>
                                    <td width="50%" style="padding-left: 12px; vertical-align: top;">
                                        <div style="background-color: #0f172a; border-radius: 12px; padding: 20px; border: 1px solid #334155; height: 100%;">
                                            <p style="margin: 0 0 8px 0; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #64748b;">Area of Interest</p>
                                            <p style="margin: 0; font-size: 16px; font-weight: 600; color: #e2e8f0;">
                                                ' . $areaInfo['icon'] . ' ' . htmlspecialchars($areaInfo['label']) . '
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Message -->
                            <table role="presentation" width="100%" style="background-color: #0f172a; border-radius: 12px; border: 1px solid #334155;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <p style="margin: 0 0 12px 0; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #64748b;">💬 Message</p>
                                        <div style="background: linear-gradient(90deg, rgba(14, 165, 233, 0.1), rgba(139, 92, 246, 0.1)); border-radius: 8px; padding: 20px; border-left: 3px solid #0ea5e9;">
                                            <p style="margin: 0; font-size: 15px; line-height: 1.7; color: #e2e8f0;">
                                                ' . nl2br(htmlspecialchars($data['message'])) . '
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                        </td>
                    </tr>
                    
                    <!-- Action Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 24px 40px; border-top: 1px solid #334155; text-align: center;">
                            <a href="mailto:' . htmlspecialchars($data['email']) . '?subject=Re: Partnership Inquiry - AlfaisalX" style="display: inline-block; background: linear-gradient(135deg, #0ea5e9, #8b5cf6); color: white; padding: 16px 40px; border-radius: 12px; text-decoration: none; font-size: 16px; font-weight: 700; box-shadow: 0 4px 14px rgba(14, 165, 233, 0.4);">
                                Reply to This Inquiry →
                            </a>
                            <p style="margin: 16px 0 0 0; font-size: 13px; color: #64748b;">Please respond within 2 business days</p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #0f172a; border-radius: 0 0 16px 16px; padding: 30px 40px; text-align: center; border: 1px solid #1e293b; border-top: none;">
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #64748b;">
                                This inquiry was submitted via the <strong style="color: #94a3b8;">AlfaisalX Partnership Form</strong>
                            </p>
                            <p style="margin: 0 0 20px 0; font-size: 12px; color: #475569;">
                                Alfaisal University, College of Engineering, Riyadh, Saudi Arabia
                            </p>
                            <table role="presentation" style="margin: 0 auto;">
                                <tr>
                                    <td style="padding: 0 8px;">
                                        <a href="https://alfaisalx.alfaisal.edu" style="color: #0ea5e9; text-decoration: none; font-size: 12px;">🌐 Website</a>
                                    </td>
                                    <td style="padding: 0 8px; color: #334155;">|</td>
                                    <td style="padding: 0 8px;">
                                        <a href="mailto:alfaisalx@alfaisal.edu" style="color: #0ea5e9; text-decoration: none; font-size: 12px;">✉️ Contact</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
    }
    
    /**
     * Build plain text email for partnership inquiry
     */
    private function buildPartnershipEmailText($data) {
        return "
NEW PARTNERSHIP INQUIRY
=======================

Contact: {$data['name']}
Email: {$data['email']}
Organization: {$data['organization']}
Role: " . ($data['role'] ?? 'Not specified') . "
Partnership Type: {$data['partnership_type']}
Area of Interest: " . ($data['areas_of_interest'] ?? 'Not specified') . "

MESSAGE:
{$data['message']}

---
This inquiry was submitted via the AlfaisalX partnership form.
Please respond within 2 business days.
        ";
    }
}
