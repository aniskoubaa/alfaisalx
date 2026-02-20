# AlfaisalX Website

**AlfaisalX** is the official website for the **Center of Excellence for Cognitive Robotics and Autonomous Agents** at Alfaisal University. The platform is built using PHP, SQLite, and vanilla web technologies to present a performant, dark-themed academic research center website.

## 🚀 Features

- **Dynamic Content Engine**: Driven by a robust SQLite database handling units, members, publications, and open positions.
- **Academic Resources integration**: Automated workflows for fetching academic publications utilizing SerpAPI and OpenAI for formatting.
- **Admin Dashboard**: Comprehensive admin tools for managing partners, team members, careers, news, and center-specific statistics.
- **Responsive Dark UI**: Modular and lightweight CSS framework designed specifically for an immersive modern academic presence.

## 📂 Project Structure

```text
alfaisalx/
├── about/                    # About the center (vision, mission, governance)
├── admin/                    # Admin portal for data management
├── api/                      # External and internal API endpoints (e.g., partners)
├── assets/                   # Static assets: CSS, JS, Fonts, Images
├── careers/                  # Job listings and postdoctoral opportunities
├── database/                 # SQLite database file and seed scripts
├── includes/                 # Reusable PHP components (header, footer, config)
├── research/                 # Research units pages (e.g., MedX)
├── scripts/                  # Backend utilities (e.g., publication fetchers)
├── team/                     # Directory of researchers and leadership
└── dev_docs/                 # Development and project status documentation
```

## 🛠 Prerequisites

- PHP 8.0+
- SQLite3 Extension for PHP
- Web Server (Apache/Nginx/XAMPP/MAMP)
- Composer (if managing PHP dependencies)
- Python 3.9+ (For specific AI utilities in `scripts/`)

## 💻 Local Development

1. **Clone the repository**

   ```bash
   git clone https://github.com/aniskoubaa/alfaisalx.git
   cd alfaisalx
   ```

2. **Setup the Database**  
   The project ships with an initial SQLite database format. To seed or rebuild data, you can utilize the `database/seed.php` file if it hasn't been instantiated.

3. **Configure the EnvironmentVariables**  
   Copy the `sample.env` to `.env` (or create one) to define critical variables (like Sendgrid APIs, OpenAI keys). Make sure `.env` is properly hidden from version control to protect credentials.

4. **Serve the Application**  
   If using XAMPP/MAMP, place the codebase in `htdocs` or `www` folder. If using the PHP built-in server:
   ```bash
   php -S localhost:8000
   ```
   Access the site locally at: `http://localhost:8000`

## 🔄 Updates

To update your local repository, you can utilize the `update_repo.sh` automation script:

```bash
./update_repo.sh
```

## 📝 License

Proprietary to Alfaisal University. All rights reserved.
