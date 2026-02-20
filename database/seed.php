<?php
/**
 * AlfaisalX Database Seeder
 * 
 * Populates the database with content from the proposal document.
 * Run this script once to initialize the database.
 * 
 * Usage: php seed.php
 * Or visit: http://localhost/alfaisalx/database/seed.php
 */

// Prevent timeout for CLI
set_time_limit(0);

require_once __DIR__ . '/Database.php';

class DatabaseSeeder {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function run() {
        echo "<pre>";
        echo "=== AlfaisalX Database Seeder ===\n\n";

        // Create tables
        $this->createTables();

        // Seed data
        $this->seedSettings();
        $this->seedStats();
        $this->seedTeamMembers();
        $this->seedResearchAreas();
        $this->seedObjectives();
        $this->seedPartners();
        // $this->seedLabs(); // DEPRECATED: Labs merged into research_areas (Research Units)
        $this->seedProjects();
        $this->seedPublications();
        $this->seedNews();
        $this->seedStrategicInitiatives();
        $this->seedApplicationSectors();
        $this->seedKPIs();
        $this->seedInfrastructure();
        $this->seedNavigation();

        echo "\n=== Seeding Complete! ===\n";
        echo "</pre>";
    }

    private function createTables() {
        echo "Creating tables...\n";
        
        $schema = file_get_contents(__DIR__ . '/schema.sql');
        $statements = explode(';', $schema);
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $this->db->execute($statement);
            }
        }
        
        echo "✓ Tables created\n\n";
    }

    private function seedSettings() {
        echo "Seeding settings...\n";

        $settings = [
            // Basic Info
            ['site_name', 'AlfaisalX', 'Site name'],
            ['site_full_name', 'Alfaisal Center for Cognitive Robotics and Autonomous Agents', 'Full center name'],
            ['site_tagline', 'Pioneering Cognitive Robotics and Autonomous Agents for Tomorrow', 'Main tagline'],
            ['acronym_explanation', 'Alfaisal is the name of the University, and X means Digital Transformation with GenAI and Robotics', 'What X means'],
            ['established_year', '2025', 'Year established'],
            ['proposed_by', 'Prof. Anis Koubaa', 'Proposal author'],
            ['proposal_date', 'February 2025', 'Proposal date'],
            
            // Vision & Mission
            ['vision', 'Global leadership in Robotics, Autonomous Systems, and Agents.', 'Vision statement'],
            ['vision_extended', 'To be an internationally recognized center in Robotics, Autonomous Systems, and Agentic AI, driving innovation, industrial transformation, and societal impact.', 'Extended vision'],
            ['mission', 'Innovating robotics and Agentic workflows for smarter industries and societies.', 'Mission statement'],
            ['mission_extended', 'To advance Cognitive Robotics and Autonomous Agents through cutting-edge research, UAV innovation, and agentic AI workflows, delivering intelligent systems that transform industries, enable automation, and contribute to Saudi Vision 2030 and global progress.', 'Extended mission'],
            
            // Contact
            ['contact_email', 'alfaisalx@alfaisal.edu', 'Contact email'],
            ['contact_phone', '+966 11 215 7777', 'Contact phone'],
            ['contact_address', 'Alfaisal University, College of Engineering, Riyadh, Saudi Arabia', 'Address'],
            
            // Social
            ['social_linkedin', 'https://www.linkedin.com/company/alfaisalx', 'LinkedIn'],
            ['social_github', 'https://github.com/alfaisalx', 'GitHub'],
            ['social_twitter', 'https://twitter.com/alfaisalx', 'Twitter'],
            ['social_scholar', 'https://scholar.google.com/citations?user=alfaisalx', 'Google Scholar'],
            
            // Introduction text
            ['intro_text', 'The Cognitive Robotics and Autonomous Agents Center (AlfaisalX) is established to drive next-generation digital transformation through the convergence of robotics, unmanned aerial vehicles (UAVs), and agentic AI workflows. By integrating autonomous robotic platforms with intelligent, goal-driven AI agents, the center develops adaptive systems that can perceive, decide, and act across complex environments.', 'Introduction paragraph'],
            ['intro_text_2', 'This synergy enables breakthrough applications in logistics automation, smart manufacturing, precision healthcare, aerial monitoring, and business process transformation. Aligned with Alfaisal University\'s strategic priorities, AlfaisalX serves as a premier hub for research, innovation, and industry collaboration.', 'Introduction paragraph 2'],
            
            // Budget info
            ['budget_initial_setup', '1,350,000+ SAR', 'Initial setup budget'],
            ['budget_annual_operational', '1,350,000 SAR', 'Annual operational budget'],
            ['lab_space_size', '150 sq. meters', 'Lab space requirement'],
        ];

        foreach ($settings as $s) {
            $this->db->execute(
                "INSERT OR REPLACE INTO settings (key, value, description) VALUES (?, ?, ?)",
                $s
            );
        }

        echo "✓ Settings seeded (" . count($settings) . " items)\n\n";
    }

    private function seedStats() {
        echo "Seeding stats...\n";
        
        // Clear existing stats to prevent duplicates
        $this->db->execute("DELETE FROM stats");

        $stats = [
            ['💡', 'From Idea to MVP', 'lightbulb', 1],
            ['🌍', 'Social Impact', 'globe', 2],
            ['🚀', 'Tech Advances', 'rocket', 3],
            ['2025', 'Established', 'calendar-alt', 4],
        ];

        foreach ($stats as $s) {
            $this->db->execute(
                "INSERT OR REPLACE INTO stats (value, label, icon, sort_order) VALUES (?, ?, ?, ?)",
                $s
            );
        }

        echo "✓ Stats seeded (" . count($stats) . " items)\n\n";
    }

    private function seedTeamMembers() {
        echo "Seeding team members...\n";
        
        // Clear duplicates
        $this->db->execute("DELETE FROM team_members");

        $members = [
            // Leadership
            [
                'Prof. Anis Koubaa',
                'Full Professor of Computer Science',
                'Director',
                'leadership',
                'AK',
                'anis2023.jpg',
                'Provides overall strategic direction and vision for the center. Leads partnerships with government agencies, industries, and academic institutions. Oversees research, funding initiatives, and commercialization efforts. Represents AlfaisalX in national and international AI and robotics forums.',
                null,
                'akoubaa@alfaisal.edu',
                null,
                json_encode(['Robotics', 'UAV Systems', 'Artificial Intelligence', 'Cloud Robotics', 'ROS2']),
                'https://scholar.google.com/citations?user=aEoY-0IAAAAJ',
                null,
                null,
                1, 1
            ],
            [
                'Prof. Driss Benhaddou',
                'Professor of Engineering',
                'Deputy Director',
                'leadership',
                'DB',
                'drissbenhaddou.jpg',
                'Coordinates cross-unit collaboration and alignment with university priorities. Expert in network-centric built environments and IoT systems.',
                null,
                'dbenhaddou@alfaisal.edu',
                null,
                json_encode(['Network Systems', 'Built Environment', 'IoT', 'Smart Cities']),
                'https://scholar.google.com/citations?user=EofcW38AAAAJ',
                null,
                null,
                1, 2
            ],
            // Core Faculty
            [
                'Dr. Asem Alalwan',
                'Assistant Professor',
                'Core Faculty - Robotics & UAVs',
                'core_faculty',
                'AA',
                null,
                'Specializing in autonomous systems and aerial vehicle technologies. Research focus on robotics, UAVs, and AI integration.',
                null,
                null,
                null,
                json_encode(['Robotics', 'UAVs', 'Autonomous Systems', 'AI Integration']),
                null,
                null,
                null,
                1, 1
            ],
            [
                'Dr. Mohamed Bahloul',
                'Assistant Professor',
                'MedX Unit Lead',
                'core_faculty',
                'MB',
                'MohamedBahloul.jpg',
                'Expert in medical applications of robotic systems and healthcare automation. Focus on robotic-assisted healthcare and intelligent service delivery.',
                null,
                'mbahloul@alfaisal.edu',
                null,
                json_encode(['Health Robotics', 'Medical Automation', 'AI in Healthcare', 'Service Robots']),
                'https://scholar.google.com/citations?user=GTIvdXUAAAAJ',
                null,
                null,
                1, 2
            ],
            [
                'Prof. Yassine Bouteraa',
                'Professor, Prince Sattam bin Abdulaziz University',
                'External Adjunct Member',
                'adjunct',
                'YB',
                'Yassine-Bouteraa-2.jpg',
                'Full Professor at Prince Sattam bin Abdulaziz University, Saudi Arabia, and a senior researcher with extensive expertise in robotics and intelligent systems. His research focuses on rehabilitation robotics, human-machine interfaces, and adaptive control, with particular emphasis on EMG- and EEG-driven systems for robotic rehabilitation and assistive technologies.',
                null,
                'y.bouteraa@psau.edu.sa',
                null,
                json_encode(['Rehabilitation and Assistive Robotics', 'Exoskeleton and Prosthetic Systems', 'EMG/EEG-Based Human-Machine Interfaces', 'Cyber-Physical and Embedded Systems']),
                'https://scholar.google.com/citations?user=qzABKAIAAAAJ',
                null,
                null,
                1, 3
            ],
            // Additional Core Faculty
            [
                'Prof. Abd-Elhamid Taha',
                'Professor',
                'Core Faculty - Smart Cities',
                'core_faculty',
                'AT',
                null,
                'Expert in Smart Cities research, focusing on intelligent urban systems, IoT infrastructure, and sustainable city technologies.',
                null,
                null,
                null,
                json_encode(['Smart Cities', 'IoT', 'Urban Systems', 'Sustainable Technologies']),
                null,
                null,
                null,
                1, 3
            ],
            [
                'Dr. Ahmad Sawalmeh',
                'Assistant Professor',
                'Core Faculty - UAVs & Smart Cities',
                'core_faculty',
                'AS',
                null,
                'Specializing in UAV systems and Smart Cities applications. Research focus on drone technologies, aerial autonomy, and intelligent urban infrastructure.',
                null,
                null,
                null,
                json_encode(['UAVs', 'Smart Cities', 'Drone Systems', 'Aerial Autonomy']),
                null,
                null,
                null,
                1, 4
            ],
            [
                'Dr. Omar Al-Ahmad',
                'Assistant Professor',
                'MedX Unit - AI in Healthcare',
                'core_faculty',
                'OA',
                null,
                'Expert in AI applications for healthcare under the MedX unit. Research focus on medical AI, clinical decision support, and healthcare automation.',
                null,
                null,
                null,
                json_encode(['AI in Healthcare', 'Medical AI', 'Clinical Decision Support', 'Healthcare Automation']),
                null,
                null,
                null,
                1, 5
            ],
            // Researchers
            [
                'Eng. Khaled Jabr',
                'Research Engineer',
                'Assistant Researcher - UAVs',
                'researchers',
                'KJ',
                null,
                'Supports UAV research, software development, and AI navigation systems.',
                null,
                null,
                null,
                json_encode(['UAV Software', 'AI Navigation', 'Drone Systems']),
                null,
                null,
                null,
                1, 1
            ],
        ];

        foreach ($members as $m) {
            $this->db->execute(
                "INSERT OR REPLACE INTO team_members (name, title, role, category, initials, image, bio, bio_extended, email, phone, expertise, google_scholar, linkedin, researchgate, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $m
            );
        }

        echo "✓ Team members seeded (" . count($members) . " items)\n\n";
    }

    private function seedResearchAreas() {
        echo "Seeding research areas...\n";

        $areas = [
            // Unit 01: Robotics, UAVs & Autonomous Systems (merged from separate robotics + UAV areas)
            [
                '01',
                'robotics-autonomous-systems',
                'Robotics, UAVs & Autonomous Systems',
                'Robotics & UAVs',
                'robot',
                '#0077b6',
                'Development of autonomous robotic platforms and UAV systems for logistics, manufacturing, healthcare, and smart cities, with focus on cloud robotics, fleet coordination, BVLOS operations, swarm intelligence, and resilient perception–planning–control pipelines.',
                'AlfaisalX advances the state of the art in autonomous robotics and aerial systems. Our research spans self-driving vehicles, industrial automation, healthcare robots, smart city infrastructure, and UAV platforms. We emphasize cloud robotics for scalable deployment, BVLOS operations, swarm coordination, edge-AI perception, and real-time coordination of robot and drone fleets.',
                json_encode(['Cloud Robotics', 'Fleet Management', 'UAVs & Drones', 'BVLOS Operations', 'Swarm Intelligence', 'Autonomous Navigation', 'ROS2', 'Edge-AI Perception']),
                json_encode([
                    'Design and validate autonomous robotic platforms for logistics, manufacturing, healthcare, and smart-city services',
                    'Develop BVLOS-capable UAV solutions for inspection, mapping, security, and delivery',
                    'Advance Cloud Robotics for scalable deployment, fleet management, and real-time coordination',
                    'Build swarm coordination and edge-AI perception for long-range operations',
                    'Develop Agentic AI for Robotics to enable autonomous decision-making and human–robot collaboration'
                ]),
                1, 1
            ],
            // Unit 02: Agentic AI & Intelligent Workflows
            [
                '02',
                'agentic-ai-workflows',
                'Agentic AI & Intelligent Workflows',
                'Agentic AI',
                'brain',
                '#7c3aed',
                'Design of multi-agent systems and Arabic-centric LLM/VLM models to power robotic decision-making, human–robot teaming, and business transformation workflows, supported by scalable deployment and MLOps.',
                'AlfaisalX pioneers the development of goal-driven AI agents that can plan, coordinate, and execute complex workflows. We develop Arabic-centric language and vision-language models for regional applications and build enterprise automation solutions using cutting-edge LLM technologies.',
                json_encode(['Multi-Agent Systems', 'Arabic-centric LLMs', 'Business Automation', 'LLM/VLM', 'Human-Robot Teaming', 'MLOps']),
                json_encode([
                    'Create goal-driven multi-agent systems that plan, coordinate, and execute end-to-end robotic workflows',
                    'Develop Arabic-centric language and vision-language models for human–robot teaming and regional applications',
                    'Build Business Transformation Agentic Workflows to automate complex enterprise processes and decision-making',
                    'Advance Scalable Agentic Systems through tool-use integration, simulation-to-reality transfer, and MLOps for reliable deployment at scale'
                ]),
                1, 2
            ],
            // Unit 03: MedX - Medical Robotics & AI in Healthcare (Led by Dr. Mohamed Bahloul)
            [
                '03',
                'medx',
                'MedX: Medical Robotics & AI in Healthcare',
                'MedX',
                'heartbeat',
                '#ef4444',
                'A dedicated translation engine connecting robotics and AI capabilities with clinical needs, delivering validated medical robotics, surgical copilots, medical simulators, and AI-powered healthcare solutions from lab bench to clinical use.',
                'MedX (Medical Robotics & AI in Healthcare) is a specialized unit within AlfaisalX designed to accelerate clinically relevant innovation. The unit operates as a translation engine that connects CRAS robotics/autonomy capabilities with clinical needs, academic talent pipelines, and engineering validation capacity to produce demonstrable prototypes and translation-ready packages. Led by Dr. Mohamed Bahloul, MedX focuses on five thematic pillars: Medical Robotics (assistive/rehabilitation, image-guided robotics), Surgical Robotics Copilots (workflow intelligence, safety monitoring), Medical Simulation & Training (VR/AR/haptics), AI in Healthcare (biomedical imaging, clinical decision support), and Translation & Ecosystem Engagement.',
                json_encode(['Medical Robotics', 'Surgical Copilots', 'Medical Simulation', 'AI in Healthcare', 'Rehabilitation Robotics', 'VR/AR Training', 'Clinical AI', 'Translation']),
                json_encode([
                    'Deliver two pilot prototypes (simulation/bench validated)',
                    'Establish repeatable translation pipeline from needs-finding to clinical adoption',
                    'Strengthen cross-unit collaboration through shared infrastructure and joint proposals',
                    'Build clinician-facing workshop program for healthcare ecosystem engagement',
                    'Create translation-ready documentation packages for technology transfer'
                ]),
                1, 3
            ],
            // Unit 04: Commercialization & Social Impact
            [
                '04',
                'commercialization-impact',
                'Commercialization & Social Impact',
                'Impact',
                'chart-line',
                '#f59e0b',
                'Integration of ethical and secure autonomy frameworks, industry pilots, and technology transfer to deliver real-world solutions with measurable ROI and alignment to Saudi Vision 2030.',
                'AlfaisalX is committed to translating research into real-world impact. We co-develop production pilots with government and industry, accelerate technology transfer through IP licensing and spinouts, and ensure all deployments align with ethical AI principles and Saudi Vision 2030 objectives.',
                json_encode(['Technology Transfer', 'IP Licensing', 'Startup Acceleration', 'Vision 2030', 'Ethical AI', 'Industry Pilots']),
                json_encode([
                    'Co-develop production pilots with government and industry to solve priority use cases',
                    'Accelerate technology transfer (IP, licensing, spinouts) and adopt open standards where beneficial',
                    'Quantify the ROI and environmental impact to support the outcomes of Saudi Vision 2030'
                ]),
                1, 4
            ],
        ];

        foreach ($areas as $a) {
            $this->db->execute(
                "INSERT OR REPLACE INTO research_areas (number, slug, title, short_title, icon, color, description, description_extended, tags, objectives, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $a
            );
        }

        echo "✓ Research areas seeded (" . count($areas) . " items)\n\n";
    }

    private function seedObjectives() {
        echo "Seeding objectives...\n";

        $objectives = [
            [1, 'Robotics & Autonomous Systems', 'Design, integrate, and validate autonomous robotic platforms and advance cloud robotics for scalable deployment.',
                json_encode([
                    'Design, integrate, and validate autonomous robotic platforms for self-driving cars, logistics, manufacturing, healthcare, and smart-city services',
                    'Advance Cloud Robotics for scalable deployment, fleet management, and real-time coordination',
                    'Develop Agentic AI for Robotics to enable autonomous decision-making, adaptive workflows, and human–robot collaboration'
                ]), 1, 1],
            [2, 'UAVs & Aerial Autonomy', 'Develop BVLOS-capable UAV solutions and build swarm coordination with edge-AI perception.',
                json_encode([
                    'Develop BVLOS-capable UAV solutions for inspection, mapping, security, and delivery',
                    'Build swarm coordination, resilient navigation, and edge-AI perception for long-range operations',
                    'Establish compliant testbeds and workflows supporting civil aviation regulations',
                    'Advance UAV Agentic AI for adaptive mission planning, autonomous decision-making, and coordinated multi-UAV operations'
                ]), 2, 2],
            [3, 'Agentic AI Workflows & LLM-Based Agents', 'Create goal-driven multi-agent systems and develop Arabic-centric language models.',
                json_encode([
                    'Create goal-driven multi-agent systems that plan, coordinate, and execute end-to-end robotic workflows',
                    'Develop Arabic-centric language and vision-language models for human–robot teaming and regional applications',
                    'Build Business Transformation Agentic Workflows to automate complex enterprise processes and decision-making',
                    'Advance Scalable Agentic Systems through tool-use integration, simulation-to-reality transfer, and MLOps for reliable deployment at scale'
                ]), 3, 3],
            [4, 'Industry Partnerships, Pilots & Commercialization', 'Co-develop production pilots and accelerate technology transfer.',
                json_encode([
                    'Co-develop production pilots with government and industry to solve priority use cases',
                    'Accelerate technology transfer (IP, licensing, spinouts) and adopt open standards where beneficial',
                    'Quantify the ROI and environmental impact to support the outcomes of Saudi Vision 2030'
                ]), 4, 4],
            [5, 'Education, Training & Workforce Development', 'Deliver hands-on programs and build national talent pipeline.',
                json_encode([
                    'Deliver hands-on programs (robotics, UAV operations, agentic AI engineering) and professional certifications',
                    'Mentor capstones and graduate research aligned with real industrial needs',
                    'Run hackathons and internships to build a national talent pipeline in autonomy and AI'
                ]), null, 5],
        ];

        foreach ($objectives as $o) {
            $this->db->execute(
                "INSERT OR REPLACE INTO objectives (number, title, description, bullet_points, research_area_id, sort_order) VALUES (?, ?, ?, ?, ?, ?)",
                $o
            );
        }

        echo "✓ Objectives seeded (" . count($objectives) . " items)\n\n";
    }

    private function seedPartners() {
        echo "Seeding partners...\n";
        
        // Clear existing partners to prevent duplicates
        $this->db->execute("DELETE FROM partners");

        $partners = [
            // Industry Partners
            ['HUMAIN', 'HUMAIN - AI & Technology Solutions', 'industry', null,
                'Confirmed readiness to collaborate on multiple initiatives in AI and robotics, accelerating knowledge transfer and co-development of industrial solutions.',
                'HUMAIN has expressed commitment to engage with AlfaisalX on multiple initiatives, demonstrating the industrial trust in our vision.',
                null, 1, 1],
            ['Bako Motors', 'Bako Motors - Electric Vehicle Manufacturer (Tunisia)', 'industry', null,
                'Formally pledged industrial support by providing a dedicated electric vehicle platform for research, development, and testing of autonomous driving technologies.',
                'Bako Motors will collaborate with the AlfaisalX team to integrate AI-driven autonomy modules and support real-world validation. They have committed to providing a full electric vehicle to Alfaisal University.',
                null, 1, 2],
            ['Tawuniya', 'Tawuniya Insurance', 'industry', null,
                'Industry-funded grant for Generative AI-Powered Multilingual Chatbot for Patient Engagement and Claims Processing.',
                'Won with 1.6% acceptance rate across all Saudi universities. Project addresses strategic national healthcare priorities by automating medical record generation, fraud detection, and billing compliance.',
                'https://www.tawuniya.com', 1, 3],
            ['Jahez', 'Jahez Delivery Company', 'industry', null,
                'Potential collaboration for autonomous electric vehicle delivery project.',
                'Direct connection established with Jahez COO to explore collaboration opportunities for last-mile delivery automation.',
                null, 1, 4],
            
            // Government Partners
            ['RDIA', 'Research, Development, and Innovation Authority', 'government', null,
                'Major funding source offering grants through Saudi Basic Science Initiative (up to SAR 1.6M per project) and SART Initiative (up to SAR 20M over 5 years).',
                'RDIA provides critical funding opportunities for fundamental research in health, sustainability, and future economies, as well as advancing prototypes to development stages.',
                'https://rdia.gov.sa', 1, 1],
            ['SDAIA', 'Saudi Data and Artificial Intelligence Authority', 'government', null,
                'AI Research and Development Programs aligned with national priorities.',
                'Collaboration on AI projects aligned with national AI strategies, with potential substantial funding based on project scope.',
                'https://sdaia.gov.sa', 1, 2],
            ['PSDSARC', 'Prince Sultan Defense Studies and Research Center', 'government', null,
                'Defense research grant secured for U-SCAR UAV Surveillance System project.',
                'Project develops AI-powered multimodal detection (vision and sound) for real-time drone monitoring, contributing to defense, security, and emergency response capabilities.',
                null, 1, 3],
            ['SCAI', 'Saudi Company for Artificial Intelligence', 'government', null,
                'Potential collaboration to develop AI-driven solutions addressing national priorities.',
                'Leveraging SCAI resources and expertise for mutual benefit in technology commercialization and implementation.',
                null, 1, 4],
        ];

        foreach ($partners as $p) {
            $this->db->execute(
                "INSERT OR REPLACE INTO partners (name, full_name, type, logo, description, collaboration_details, website, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $p
            );
        }

        echo "✓ Partners seeded (" . count($partners) . " items)\n\n";
    }

    private function seedLabs() {
        echo "Seeding research units...\n";

        $labs = [
            [
                'agentic-workflows',
                'Agentic Workflows for Business Automation Unit',
                'Agentic AI Unit',
                'brain',
                'AI agents and LLMs for enterprise automation and Arabic-centric solutions.',
                'Developing intelligent AI agents and Large Language Models for business process automation, aligned with Vision 2030 digital transformation.',
                json_encode([
                    'LLMs & Generative AI',
                    'Arabic NLP',
                    'Multi-Agent Systems',
                    'Workflow Automation',
                    'RAG & Knowledge Graphs',
                    'Conversational AI'
                ]),
                json_encode([
                    'GPU Servers (H100/RTX4090)',
                    'Cloud (AWS, GCP, Azure)',
                    'LLM APIs (OpenAI, Claude)',
                    'Vector Databases'
                ]),
                1, 1
            ],
            [
                'robotics-uavs',
                'Robotics, UAVs & Autonomous Systems Unit',
                'Robotics & UAVs Unit',
                'robot',
                'Autonomous robots and drones for smart cities, logistics, and precision agriculture.',
                'Building autonomous ground and aerial platforms with advanced perception and navigation for real-world deployment.',
                json_encode([
                    'Ground Robots',
                    'UAVs & Drones',
                    'SLAM & Navigation',
                    'Computer Vision',
                    'ROS2 Development',
                    'Sensor Fusion'
                ]),
                json_encode([
                    'Vicon Arena (10×10m)',
                    'Robot Platforms',
                    'Drone Fleet',
                    'LiDAR & Cameras',
                    'Edge AI (Jetson, Pi5)'
                ]),
                1, 2
            ],
            [
                'medical-robotics',
                'Medical & Health Robotics Unit',
                'Medical Robotics Unit',
                'heartbeat',
                'Healthcare robotics for surgery assistance, rehabilitation, and medical imaging.',
                'Advancing AI-powered medical robotics and diagnostic systems to improve patient outcomes and healthcare delivery.',
                json_encode([
                    'Surgical Robotics',
                    'Rehabilitation',
                    'Medical Imaging',
                    'AI Diagnostics',
                    'Telemedicine',
                    'Elderly Care'
                ]),
                json_encode([
                    'Medical Simulators',
                    'Rehab Platforms',
                    'Imaging Workstations',
                    'Biometric Sensors',
                    'VR/AR Systems'
                ]),
                1, 3
            ],
        ];

        foreach ($labs as $l) {
            $this->db->execute(
                "INSERT OR REPLACE INTO labs (slug, name, short_name, icon, focus, description, equipment, team_roles, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $l
            );
        }

        echo "✓ Labs seeded (" . count($labs) . " items)\n\n";
    }

    private function seedProjects() {
        echo "Seeding projects...\n";

        $projects = [
            [
                'autonomous-electric-vehicle',
                'Autonomous Electric Vehicle for Delivery Services',
                'Design, development, and deployment of an Autonomous Electric Vehicle (AEV) tailored to Saudi Arabia\'s logistics and delivery ecosystem.',
                'This project focuses on the design, development, and deployment of an Autonomous Electric Vehicle (AEV) tailored to Saudi Arabia\'s logistics and delivery ecosystem. Conducted in collaboration with Bako Motors (Tunisian EV manufacturer) and Jahez Company, this initiative aims to integrate advanced robotics, AI, and autonomous systems to revolutionize last-mile delivery services.',
                'proposed',
                json_encode(['Bako Motors', 'Jahez']),
                'Industry Partnership',
                'TBD',
                '2025',
                '2028',
                json_encode([
                    ['Phase 1: Planning & Design', 'Define system requirements, select sensor suite, and design software architecture', 'Months 1-3'],
                    ['Phase 2: Hardware Integration', 'Install sensors, compute units, and actuators into the Bako Motors electric vehicle', 'Months 4-9'],
                    ['Phase 3: Software Development', 'Develop ROS2-based modules for perception, localization, and path planning', 'Months 10-18'],
                    ['Phase 4: Testing & Deployment', 'Conduct controlled tests and deploy pilot vehicles with Jahez for real-world validation', 'Months 19-24'],
                    ['Phase 5: Optimization & Commercialization', 'Refine AI models, improve system efficiency, and develop commercialization strategy', 'Months 25-36']
                ]),
                json_encode([
                    'Develop an Autonomous Electric Vehicle Platform using ROS2',
                    'Integrate AI-based perception and control systems',
                    'Build a fully autonomous delivery system for Jahez',
                    'Automate logistics processes such as route planning and fleet management',
                    'Adapt AI navigation systems to Saudi Arabia\'s conditions'
                ]),
                null, 1, 1
            ],
            [
                'tawuniya-ai-chatbot',
                'Generative AI-Powered Multilingual Chatbot',
                'Patient Engagement and Claims Processing chatbot for Tawuniya Insurance.',
                'Industry-funded grant from Tawuniya to develop a Generative AI-Powered Multilingual Chatbot for Patient Engagement and Claims Processing. This project addresses strategic national healthcare priorities by automating medical record generation, fraud detection, and billing compliance. Won with 1.6% acceptance rate across all Saudi universities.',
                'ongoing',
                json_encode(['Tawuniya']),
                'Tawuniya Industry Grant',
                'TBD',
                '2024',
                null,
                null,
                json_encode([
                    'Automate medical record generation',
                    'Implement fraud detection capabilities',
                    'Ensure billing compliance',
                    'Enable multilingual patient engagement'
                ]),
                null, 1, 2
            ],
            [
                'uscar-uav-surveillance',
                'U-SCAR UAV Surveillance System',
                'AI-powered multimodal detection system for real-time drone monitoring.',
                'Defense research grant from PSDSARC for the U-SCAR UAV Surveillance System. This student-led project develops AI-powered multimodal detection (vision and sound) for real-time drone monitoring, contributing directly to the Kingdom\'s defense, security, and emergency response capabilities.',
                'ongoing',
                json_encode(['PSDSARC']),
                'PSDSARC Defense Grant',
                'TBD',
                '2024',
                null,
                null,
                json_encode([
                    'Develop AI-powered multimodal detection',
                    'Implement vision-based drone detection',
                    'Implement sound-based drone detection',
                    'Enable real-time monitoring capabilities',
                    'Train next-generation Saudi engineers'
                ]),
                null, 1, 3
            ],
        ];

        foreach ($projects as $p) {
            $this->db->execute(
                "INSERT OR REPLACE INTO projects (slug, title, short_description, description, status, partners, funding_source, funding_amount, start_date, end_date, timeline, objectives, image, is_featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $p
            );
        }

        echo "✓ Projects seeded (" . count($projects) . " items)\n\n";
    }

    private function seedPublications() {
        echo "Seeding publications...\n";
        
        // Clear existing publications to avoid duplicates
        $this->db->execute("DELETE FROM publications");

        $publications = [
            [
                'Robot Operating System (ROS) The Complete Reference (Volume 1)',
                'Anis Koubaa',
                'Springer International Publishing',
                2016, 'book',
                '10.1007/978-3-319-26054-9',
                'https://link.springer.com/book/10.1007/978-3-319-26054-9',
                'This book is the first volume of a comprehensive reference on the Robot Operating System (ROS). It covers the basic concepts, advanced variants, and applications of ROS.',
                1
            ],
            [
                'Deep learning for uavs: A comprehensive investigation of object detection and tracking benchmarks',
                'A Koubaa, A Ammar, B Benjdira, A Al-Shalfan',
                'IEEE Access',
                2020, 'journal',
                '10.1109/ACCESS.2020.3040082',
                'https://ieeexplore.ieee.org/abstract/document/9272363',
                'A comprehensive study on deep learning techniques for UAV-based object detection and tracking.',
                1
            ],
            [
                'Micro-Air Vehicle Link (MAVLink) in a Nutshell: A Survey',
                'A Koubaa, A Allouch, M Alajlan, Y Javed, A Belghith, M Khalgui',
                'IEEE Access',
                2019, 'journal',
                '10.1109/ACCESS.2019.2924510',
                'https://ieeexplore.ieee.org/abstract/document/8746124',
                'This paper provides a comprehensive survey of MAVLink, the de-facto standard protocol for micro-air vehicle communication.',
                0
            ],
            [
                'Internet of drones: A survey on architecture, protocols, and applications',
                'A Koubaa, B Qureshi',
                'IEEE Access',
                2018, 'journal',
                '10.1109/ACCESS.2018.2882193',
                'https://ieeexplore.ieee.org/abstract/document/8566050',
                'This survey explores the architecture, protocols, and applications of the Internet of Drones (IoD).',
                1
            ],
            [
                'Service-oriented architecture for wireless sensor networks: A systematic study',
                'F Alaloosy, A Koubaa, M Al-Fayez',
                'IEEE Sensors Journal',
                2014, 'journal',
                '10.1109/JSEN.2014.2323282',
                'https://ieeexplore.ieee.org/abstract/document/6817666',
                'A systematic study on Service-Oriented Architecture (SOA) integration with Wireless Sensor Networks.',
                0
            ]
        ];

        foreach ($publications as $p) {
            $this->db->execute(
                "INSERT INTO publications (title, authors, venue, year, type, doi, url, abstract, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $p
            );
        }

        echo "✓ Publications seeded (" . count($publications) . " items)\n\n";
    }

    private function seedNews() {
        echo "Seeding news...\n";

        $news = [
            [
                'alfaisalx-official-launch',
                'AlfaisalX Center Official Launch',
                'Marking a new era in AI and robotics research with the opening of our state-of-the-art facility at Alfaisal University.',
                'The Cognitive Robotics and Autonomous Agents Center (AlfaisalX) officially launches, establishing a premier hub for robotics, UAV, and agentic AI research in Saudi Arabia.',
                'announcement',
                '2025-02-01',
                null, null,
                'rocket',
                1, 1
            ],
            [
                'global-faculty-recruitment',
                'Global Faculty Recruitment Drive',
                'Seeking world-class researchers in robotics, UAV systems, and agentic AI to join our growing team.',
                'AlfaisalX is recruiting talented faculty and researchers from around the world to join our mission of pioneering cognitive robotics and autonomous agents.',
                'recruitment',
                '2025-02-15',
                null, null,
                'users',
                1, 1
            ],
            [
                'tawuniya-grant-award',
                'Tawuniya AI Chatbot Grant Awarded',
                'Alfaisal University wins competitive industry grant from Tawuniya with 1.6% acceptance rate.',
                'AlfaisalX team secures a highly competitive industry-funded grant from Tawuniya Insurance to develop a Generative AI-Powered Multilingual Chatbot for Patient Engagement and Claims Processing.',
                'research',
                '2024-12-01',
                null, null,
                'trophy',
                1, 0
            ],
            [
                'international-workshop-agentic-ai',
                'International Workshop on Agentic AI',
                'Bringing together global experts to discuss the future of intelligent autonomous systems.',
                'AlfaisalX hosts an international workshop bringing together leading researchers in agentic AI, robotics, and autonomous systems to share insights and foster collaboration.',
                'event',
                '2025-03-15',
                '2025-03-16',
                'Alfaisal University, Riyadh',
                'chalkboard-teacher',
                1, 1
            ],
            [
                'french-air-force-internship',
                'International Internship Partnership',
                'French Air and Space Force Academy requests internship placement in UAV research.',
                'AlfaisalX receives request from the French Air and Space Force Academy for a six-month final-year internship in UAVs and defense-related robotics, validating our reputation as a global destination for talent development.',
                'announcement',
                '2025-01-15',
                null, null,
                'plane',
                1, 0
            ],
        ];

        foreach ($news as $n) {
            $this->db->execute(
                "INSERT OR REPLACE INTO news (slug, title, excerpt, content, type, date, end_date, location, icon, is_published, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $n
            );
        }

        echo "✓ News seeded (" . count($news) . " items)\n\n";
    }

    private function seedStrategicInitiatives() {
        echo "Seeding strategic initiatives...\n";

        $initiatives = [
            ['Flagship Research & Innovation', 'Drive pioneering research in robotics, UAV autonomy, and agentic AI workflows, establishing Saudi Arabia as a leader in next-generation intelligent systems aligned with national and global priorities.', 'flask', 1],
            ['Robotics & Autonomous Systems Innovation Lab', 'Establish a state-of-the-art facility for prototyping, testing, and validating robotic platforms, UAVs, and multi-agent AI systems, including cloud robotics and simulation-to-reality pipelines.', 'robot', 2],
            ['Industry Partnerships & Startup Ecosystem', 'Forge strategic collaborations with industry leaders and government entities to co-develop real-world pilots, accelerate commercialization, and foster startups in robotics, UAVs, and AI-driven automation.', 'handshake', 3],
            ['Talent Development & Workforce Readiness', 'Deliver hands-on training programs, certifications, and hackathons in robotics, UAV operations, and agentic AI engineering, preparing the next generation of Saudi innovators and practitioners.', 'graduation-cap', 4],
            ['Outreach, Knowledge Sharing & Policy Impact', 'Host conferences, publish high-impact research, and engage in global collaborations to shape thought leadership in robotics and AI, while contributing to ethical, safe, and standards-driven adoption.', 'globe', 5],
        ];

        foreach ($initiatives as $i) {
            $this->db->execute(
                "INSERT OR REPLACE INTO strategic_initiatives (title, description, icon, sort_order) VALUES (?, ?, ?, ?)",
                $i
            );
        }

        echo "✓ Strategic initiatives seeded (" . count($initiatives) . " items)\n\n";
    }

    private function seedApplicationSectors() {
        echo "Seeding application sectors...\n";

        $sectors = [
            ['Smart Cities', 'Deploying AI-driven robotics and UAVs for urban mobility, public safety, and infrastructure management.', 'city', 1],
            ['Logistics & Industry', 'Enhancing supply chains and industrial operations through autonomous robots, UAVs, and adaptive AI workflows.', 'truck', 2],
            ['Healthcare & Precision Services', 'Advancing robotic-assisted healthcare, aerial health monitoring, and intelligent service delivery systems.', 'hospital', 3],
            ['Education & Workforce Development', 'Implementing AI-powered personalized learning platforms, robotic teaching assistants, and UAV-based remote education tools.', 'graduation-cap', 4],
            ['Legal & Regulatory Innovation', 'Supporting AI-driven compliance tools, autonomous system safety audits, and policy frameworks for responsible deployment.', 'balance-scale', 5],
            ['Cultural & Societal Applications', 'Leveraging AI agents and robotics to preserve, digitize, and promote regional heritage in alignment with Saudi Vision 2030.', 'landmark', 6],
        ];

        foreach ($sectors as $s) {
            $this->db->execute(
                "INSERT OR REPLACE INTO application_sectors (title, description, icon, sort_order) VALUES (?, ?, ?, ?)",
                $s
            );
        }

        echo "✓ Application sectors seeded (" . count($sectors) . " items)\n\n";
    }

    private function seedKPIs() {
        echo "Seeding KPIs...\n";

        $kpis = [
            ['Research & Publications', 'Research papers published in top-tier journals/conferences', '10+ per year', 1],
            ['Research & Publications', 'Patents filed and approved', '1-2 per year', 2],
            ['Research & Publications', 'AI datasets and models published as open-source', 'At least 3 per year', 3],
            ['Funding & Revenue', 'Total external funding secured (RDIA, SDAIA, SCAI, Industry)', 'At least SAR 1.5M per year', 4],
            ['Funding & Revenue', 'Revenue from AI consulting and R&D projects', 'Estimated SAR 500K+ per year', 5],
            ['Funding & Revenue', 'Training & certification income', 'SAR 100K+ per year', 6],
            ['Education & Student Engagement', 'Student projects, theses, and capstones mentored', '5+ per year', 7],
            ['Education & Student Engagement', 'AI and robotics training workshops conducted', '2 per year', 8],
            ['Industry & Government Engagement', 'Corporate R&D collaborations', '2 per year', 9],
            ['Industry & Government Engagement', 'AI-driven industrial applications deployed', '2 per year', 10],
            ['Infrastructure & Growth', 'Computational resources added (GPUs, robotics, UAVs)', 'Incremental upgrades annually', 11],
            ['Infrastructure & Growth', 'New researchers and faculty recruited', '2-3 per year', 12],
        ];

        foreach ($kpis as $k) {
            $this->db->execute(
                "INSERT OR REPLACE INTO kpis (category, metric, target, sort_order) VALUES (?, ?, ?, ?)",
                $k
            );
        }

        echo "✓ KPIs seeded (" . count($kpis) . " items)\n\n";
    }

    private function seedInfrastructure() {
        echo "Seeding infrastructure...\n";

        $infrastructure = [
            ['Lab Space', 'Dedicated Research Lab', 'Fully equipped research lab to support AI, robotics, and autonomous system development', '150 sq. meters minimum', 'building', 1],
            ['Computing', 'High-Performance GPU Servers', 'GPU servers for deep learning, large-scale AI model training, and simulations', 'H100/RTX4090 GPUs, storage, networking', 'server', 2],
            ['Computing', 'Workstations & Laptops', 'Development machines for AI research and software development', 'High-end workstations', 'laptop', 3],
            ['Robotics', 'Vicon Motion-Capture Arena', '10×10m arena with 12-16 Vicon cameras for millimeter-accurate UAV tracking', 'Motion capture system with software license', 'video', 4],
            ['Robotics', 'Industrial Robotic Platforms', 'Autonomous platforms for industrial, logistics, and human-robot interaction studies', 'Multiple robot platforms', 'robot', 5],
            ['UAV', 'UAV Fleet', 'Unmanned Aerial Vehicle systems for AI-driven aerial automation and surveillance', 'Various drone platforms', 'helicopter', 6],
            ['Sensors', 'Advanced Sensor Suite', 'Sensors for AI perception and environment mapping', 'LiDAR, RADAR, RGB/Thermal/Hyperspectral cameras, IMUs, GPS', 'satellite-dish', 7],
            ['Embedded', 'IoT & Edge Computing', 'Edge AI development platforms', 'Jetson Orin Nano, Raspberry Pi 5+, microcontrollers', 'microchip', 8],
            ['Prototyping', 'Innovation & Fabrication Hub', 'Rapid prototyping capabilities', '3D printers, fabrication tools, electronics lab', 'tools', 9],
            ['Cloud', 'Cloud Computing & AI Services', 'Cloud resources for scalable AI workloads', 'AWS, GCP, OpenAI, Claude, DigitalOcean', 'cloud', 10],
        ];

        foreach ($infrastructure as $inf) {
            $this->db->execute(
                "INSERT OR REPLACE INTO infrastructure (category, name, description, specifications, icon, sort_order) VALUES (?, ?, ?, ?, ?, ?)",
                $inf
            );
        }

        echo "✓ Infrastructure seeded (" . count($infrastructure) . " items)\n\n";
    }

    private function seedNavigation() {
        echo "Seeding navigation...\n";

        // Main navigation items
        $nav = [
            [null, 'Home', '/', 'home', 1, 1],
            [null, 'About', '/about/', 'info-circle', 1, 2],
            [null, 'Team', '/team/', 'users', 1, 3],
            [null, 'Research', '/research/', 'flask', 1, 4],
            [null, 'Publications', '/publications/', 'book-open', 1, 5],
            [null, 'Labs', '/labs/', 'microscope', 1, 6],
            [null, 'Partners', '/partners/', 'handshake', 1, 7],
            [null, 'Education', '/education/', 'graduation-cap', 1, 8],
            [null, 'News', '/news/', 'newspaper', 1, 9],
            [null, 'Careers', '/careers/', 'briefcase', 1, 10],
            [null, 'Contact', '/contact/', 'envelope', 1, 11],
        ];

        foreach ($nav as $n) {
            $this->db->execute(
                "INSERT OR REPLACE INTO navigation (parent_id, label, url, icon, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?)",
                $n
            );
        }

        echo "✓ Navigation seeded (" . count($nav) . " items)\n\n";
    }
}

// Run the seeder
$seeder = new DatabaseSeeder();
$seeder->run();

