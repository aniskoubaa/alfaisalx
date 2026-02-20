#!/usr/bin/env python3
"""
Send Challenge Invitations to Postdoctoral Candidates

This script sends personalized email invitations for the 7-Day Postdoctoral
Technical Challenge to all candidates in the CSV file.

Modes:
  - test: Send only to anis.koubaa@gmail.com and mbahloul@alfaisal.edu
  - live: Send to all participants from the CSV file

Environment Variables:
  - SENDGRID_API_KEY: Your SendGrid API key (required)

Usage:
  python send_challenge_invitations.py --mode test
  python send_challenge_invitations.py --mode live
"""

import os
import sys
import csv
import argparse
from datetime import datetime
from sendgrid import SendGridAPIClient
from sendgrid.helpers.mail import Mail, Email, To, Content

# Configuration
SENDER_EMAIL = "akoubaa@scalexi.ai"
SENDER_NAME = "Prof. Anis Koubaa"
CC_RECIPIENTS = [
    "akoubaa@alfaisal.edu",
    "mbahloul@alfaisal.edu"
]
TEST_RECIPIENTS = [
    "anis.koubaa@gmail.com",
    "mbahloul@alfaisal.edu"
]
CSV_FILE = "../list_of-candiates/ResearchFellowPostdoctoralPosition.csv"
CHALLENGE_URL = "https://docs.google.com/forms/d/1s9NHk9yt3c5xxSS83stFr8O8ZEYoLR8X_PBsY1-CBtI/edit"
PDF_ATTACHMENT = "postdoc_challenge_v2.pdf"

# Email Template
EMAIL_SUBJECT = "[AlfaisalX] Invitation: 7-Day Postdoctoral Technical Challenge - AI Medical Imaging"

EMAIL_TEMPLATE = """Dear {name},
<br><br>
I hope this message finds you well.

We are pleased to invite you to participate in a <b>7-Day Postdoctoral Technical Challenge</b> as part of our recruitment process for the Research Fellow/Postdoctoral position at <b>AlfaisalX: Cognitive Robotics and Autonomous Agents</b>, MedX Research Unit, Alfaisal University, Riyadh, Saudi Arabia.

<h3>Challenge Overview</h3>

This technical challenge is designed to evaluate your ability to build end-to-end AI systems for medical imaging applications. You will work on three interconnected tasks using the PneumoniaMNIST dataset:

<ol>
  <li><b>Task 1:</b> CNN Classification with Comprehensive Analysis</li>
  <li><b>Task 2:</b> Medical Report Generation using Visual Language Models</li>
  <li><b>Task 3:</b> Semantic Image Retrieval System</li>
</ol>

<p><b>Note:</b> You are required to complete <b>at least 2 out of 3 tasks</b>. Completing all three tasks is considered a bonus and will strengthen your application.</p>

<h3>Key Details</h3>

<ul>
  <li><b>Duration:</b> 7 days from the date you receive this invitation</li>
  <li><b>Submission Deadline:</b> <b>February 22, 2026</b></li>
  <li><b>Dataset:</b> MedMNIST v2 -- PneumoniaMNIST (lightweight, runnable on standard hardware)</li>
  <li><b>Submission:</b> GitHub repository + Google Colab notebook</li>
</ul>

<h3>Important Notes</h3>

<ul>
  <li>Use of AI coding assistants (GitHub Copilot, ChatGPT, Claude, etc.) is <b>allowed and encouraged</b></li>
  <li>Focus on <b>quality over quantity</b> -- partial completion of high-quality work is acceptable</li>
  <li>The challenge is designed to be completable on standard hardware (no GPU required for Task 1)</li>
  <li>Detailed instructions are attached as a PDF document</li>
</ul>

<h3>Next Steps</h3>

<ol>
  <li>Review the attached PDF document carefully</li>
  <li>Submit your GitHub repository URL and Colab notebook link via the submission form: <a href="{challenge_url}">{challenge_url}</a></li>
  <li>Ensure your submission is completed by <b>February 22, 2026</b></li>
</ol>

If you have any questions or need clarification, please don't hesitate to reach out to:
<ul>
  <li>Prof. Anis Koubaa: <a href="mailto:akoubaa@alfaisal.edu">akoubaa@alfaisal.edu</a></li>
  <li>Dr. Mohamed Bahloul: <a href="mailto:mbahloul@alfaisal.edu">mbahloul@alfaisal.edu</a></li>
</ul>

We look forward to reviewing your submission and learning about your approach to this challenge.

<p><b>Good luck!</b></p>

<p>Best regards,<br>
<b>Prof. Anis Koubaa</b><br>
AlfaisalX: Cognitive Robotics and Autonomous Agents<br>
MedX Research Unit, Medical Robotics & AI in Healthcare<br>
College of Engineering and Advanced Computing<br>
Alfaisal University, Riyadh, Saudi Arabia<br>
<a href="mailto:akoubaa@alfaisal.edu">akoubaa@alfaisal.edu</a></p>
"""


def read_candidates(csv_path):
    """Read candidate information from CSV file."""
    candidates = []
    try:
        with open(csv_path, 'r', encoding='utf-8') as f:
            reader = csv.DictReader(f)
            for row in reader:
                candidates.append({
                    'name': f"{row['Name - First']} {row['Name - Last']}",
                    'email': row['Email'].strip(),
                    'first_name': row['Name - First'],
                    'nationality': row['Nationality']
                })
        print(f"✅ Loaded {len(candidates)} candidates from CSV")
        return candidates
    except Exception as e:
        print(f"❌ Error reading CSV: {e}")
        sys.exit(1)


def send_email(sg_client, recipient_email, recipient_name, mode="test"):
    """Send a single email using SendGrid."""
    try:
        # Prepare email content
        html_content = EMAIL_TEMPLATE.format(
            name=recipient_name,
            challenge_url=CHALLENGE_URL
        )
        
        # Create the email message using constructor
        from sendgrid.helpers.mail import Cc, Attachment, FileContent, FileName, FileType, Disposition
        import base64
        
        from_email = Email(SENDER_EMAIL, SENDER_NAME)
        to_email = To(recipient_email)
        subject = EMAIL_SUBJECT
        content = Content("text/html", html_content)
        
        mail = Mail(from_email, to_email, subject, content)
        
        # Add CC recipients (exclude the main recipient to avoid duplicates)
        for cc_email in CC_RECIPIENTS:
            if cc_email != recipient_email:
                mail.add_cc(Cc(cc_email))
        
        # Add PDF attachment
        try:
            with open(PDF_ATTACHMENT, 'rb') as f:
                pdf_data = f.read()
            encoded = base64.b64encode(pdf_data).decode()
            
            attached_file = Attachment(
                FileContent(encoded),
                FileName('Postdoctoral_Technical_Challenge.pdf'),
                FileType('application/pdf'),
                Disposition('attachment')
            )
            mail.attachment = attached_file
        except Exception as e:
            print(f"  ⚠️  Warning: Could not attach PDF: {e}")
        
        # Send email
        response = sg_client.send(mail)
        
        if response.status_code in [200, 201, 202]:
            mode_label = "TEST" if mode == "test" else "LIVE"
            print(f"  ✅ [{mode_label}] Sent to {recipient_email} ({recipient_name})")
            return True
        else:
            print(f"  ❌ Failed to send to {recipient_email}: Status {response.status_code}")
            return False
            
    except Exception as e:
        print(f"  ❌ Error sending to {recipient_email}: {e}")
        return False


def main():
    """Main execution function."""
    parser = argparse.ArgumentParser(
        description="Send challenge invitations to postdoctoral candidates"
    )
    parser.add_argument(
        "--mode",
        choices=["test", "live"],
        required=True,
        help="Mode: 'test' sends to test recipients only, 'live' sends to all candidates"
    )
    args = parser.parse_args()
    
    # Check for API key
    api_key = os.environ.get('SENDGRID_API_KEY')
    if not api_key:
        print("❌ Error: SENDGRID_API_KEY environment variable not set")
        print("   Set it with: export SENDGRID_API_KEY='your-api-key'")
        sys.exit(1)
    
    # Initialize SendGrid client
    sg = SendGridAPIClient(api_key)
    print(f"✅ SendGrid API client initialized")
    
    # Determine recipients
    if args.mode == "test":
        print(f"\n{'='*60}")
        print(f"🧪 TEST MODE - Sending to test recipients only")
        print(f"{'='*60}\n")
        recipients = [
            {'email': email, 'name': email.split('@')[0].replace('.', ' ').title(), 'first_name': email.split('@')[0]}
            for email in TEST_RECIPIENTS
        ]
    else:
        print(f"\n{'='*60}")
        print(f"🚀 LIVE MODE - Sending to ALL candidates")
        print(f"{'='*60}\n")
        confirmation = input(f"⚠️  You are about to send emails to ALL candidates. Continue? (yes/no): ")
        if confirmation.lower() != 'yes':
            print("❌ Canceled by user")
            sys.exit(0)
        recipients = read_candidates(CSV_FILE)
    
    # Send emails
    print(f"\n📧 Sending {len(recipients)} email(s)...\n")
    
    success_count = 0
    fail_count = 0
    
    for i, recipient in enumerate(recipients, 1):
        print(f"[{i}/{len(recipients)}] Processing {recipient['email']}...")
        if send_email(sg, recipient['email'], recipient['name'], mode=args.mode):
            success_count += 1
        else:
            fail_count += 1
    
    # Summary
    print(f"\n{'='*60}")
    print(f"📊 Summary")
    print(f"{'='*60}")
    print(f"  ✅ Successful: {success_count}")
    print(f"  ❌ Failed: {fail_count}")
    print(f"  📮 Total: {len(recipients)}")
    print(f"{'='*60}\n")
    
    if args.mode == "test":
        print("✅ Test completed successfully!")
        print(f"   Check your inbox at: {', '.join(TEST_RECIPIENTS)}")
    else:
        print("✅ Live send completed!")


if __name__ == "__main__":
    main()
