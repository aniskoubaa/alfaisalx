# Postdoctoral Challenge - Email Invitation System

This directory contains the 7-Day Postdoctoral Technical Challenge and the automated email invitation system.

## Files

- `postdoc_challenge_v2.tex` - LaTeX source for the challenge document
- `postdoc_challenge_v2.pdf` - Compiled PDF (119KB, updated Feb 14, 2026)
- `send_challenge_invitations.py` - Python script to send email invitations
- `../list_of-candiates/ResearchFellowPostdoctoralPosition.csv` - Candidate database (106 candidates)

## Email Invitation System

### Prerequisites

1. **Python 3.x** installed
2. **SendGrid API Key** - Get one from [SendGrid](https://sendgrid.com/)
3. **Python packages**:
   ```bash
   pip3 install sendgrid
   ```

### Setup

Set your SendGrid API key as an environment variable:

```bash
export SENDGRID_API_KEY='your-sendgrid-api-key-here'
```

For permanent setup (add to `~/.zshrc` or `~/.bash_profile`):
```bash
echo 'export SENDGRID_API_KEY="your-sendgrid-api-key-here"' >> ~/.zshrc
source ~/.zshrc
```

### Usage

#### Test Mode (Recommended First)

Send test emails to only 2 recipients:
- anis.koubaa@gmail.com
- mbahloul@alfaisal.edu

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/alfaisalx/hiring/challenge
python3 send_challenge_invitations.py --mode test
```

**Output:**
```
✅ SendGrid API client initialized

============================================================
🧪 TEST MODE - Sending to test recipients only
============================================================

📧 Sending 2 email(s)...

[1/2] Processing anis.koubaa@gmail.com...
  ✅ [TEST] Sent to anis.koubaa@gmail.com (Anis Koubaa)
[2/2] Processing mbahloul@alfaisal.edu...
  ✅ [TEST] Sent to mbahloul@alfaisal.edu (Mbahloul)

============================================================
📊 Summary
============================================================
  ✅ Successful: 2
  ❌ Failed: 0
  📮 Total: 2
============================================================

✅ Test completed successfully!
```

#### Live Mode (Send to All Candidates)

Send to all 106 candidates from the CSV file:

```bash
python3 send_challenge_invitations.py --mode live
```

**Important:** The script will ask for confirmation before sending to all candidates:
```
⚠️  You are about to send emails to ALL candidates. Continue? (yes/no):
```

Type `yes` to proceed.

### Email Details

**Sender:** Prof. Anis Koubaa <akoubaa@scalexi.ai>

**Subject:** Invitation: 7-Day Postdoctoral Technical Challenge - AI Medical Imaging

**Attachment:** Postdoctoral_Technical_Challenge.pdf (119KB)

**Content:**
- Personalized greeting using candidate's full name
- Challenge overview (3 tasks)
- Key details (7 days, deadline: Feb 22, 2026)
- Dataset info (PneumoniaMNIST)
- Submission requirements (GitHub + Colab)
- Contact information
- Submission form link

### Testing Results

**Test Send - February 14, 2026, 20:15**
- ✅ Both test emails sent successfully
- ✅ PDF attachment included
- ✅ HTML formatting working
- ✅ Personalization working

Check the test inboxes to verify:
1. Email received
2. PDF attachment is present and readable
3. All links are working
4. Formatting is correct

### Troubleshooting

**Error: SENDGRID_API_KEY environment variable not set**
```bash
export SENDGRID_API_KEY='your-api-key'
```

**Error: Could not attach PDF**
- Make sure `postdoc_challenge_v2.pdf` exists in the same directory
- Re-compile the LaTeX file if needed: `pdflatex postdoc_challenge_v2.tex`

**Error: Could not read CSV**
- Check that `../list_of-candiates/ResearchFellowPostdoctoralPosition.csv` exists
- Verify CSV format has columns: `Name - First`, `Name - Last`, `Email`

### Candidate Statistics

- **Total Candidates:** 106
- **Countries Represented:** 20+
- **Top Countries:** Pakistan (44), India (29), Egypt (5), Sudan (5)
- **Gender:** Male (92), Female (14)

### Next Steps After Sending

1. ✅ **Monitor bounces** in SendGrid dashboard
2. ✅ **Track opens/clicks** to see engagement
3. ✅ **Prepare to answer questions** via email
4. ✅ **Set up submission tracking** as responses come in
5. ✅ **Review submissions** as deadline (Feb 22, 2026) approaches

### Recompiling the PDF

If you make changes to the LaTeX file:

```bash
pdflatex -interaction=nonstopmode postdoc_challenge_v2.tex
```

This will regenerate `postdoc_challenge_v2.pdf`.

---

**Last Updated:** February 14, 2026
**Challenge Deadline:** February 22, 2026
**Duration:** 7 days from receipt of invitation
