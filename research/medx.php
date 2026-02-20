<?php
/**
 * AlfaisalX - MedX Unit Page
 * Medical Robotics & AI in Healthcare
 * Premium Design Version
 */

require_once '../includes/config.php';

$page_title = 'MedX: Medical Robotics & AI in Healthcare';
$page_description = 'MedX is a dedicated unit within AlfaisalX translating robotics and AI from lab to clinic, delivering medical robotics, surgical copilots, and AI-powered healthcare solutions.';

// Get MedX research area data
$medx = $db->getResearchAreaBySlug('medx');

// Get unit lead (Dr. Bahloul)
$unit_lead = $db->queryOne("SELECT * FROM team_members WHERE name LIKE '%Bahloul%' AND is_active = 1");
if ($unit_lead) {
    $unit_lead['expertise'] = json_decode($unit_lead['expertise'] ?? '[]', true) ?: [];
}

// Thematic pillars with gradient colors
$pillars = [
    [
        'icon' => 'robot',
        'title' => 'Medical Robotics',
        'description' => 'Assistive and rehabilitation robotics, image-guided surgical systems, and safe human-robot interaction for clinical environments.',
        'keywords' => ['Rehabilitation', 'Assistive Devices', 'Image-Guided', 'HRI'],
        'gradient' => 'from-red-500 to-orange-500'
    ],
    [
        'icon' => 'user-md',
        'title' => 'Surgical Copilots',
        'description' => 'Intelligent workflow recognition, safety prompts, skill assessment, and real-time performance metrics for surgical procedures.',
        'keywords' => ['Workflow AI', 'Safety', 'Skill Assessment', 'Real-time'],
        'gradient' => 'from-orange-500 to-amber-500'
    ],
    [
        'icon' => 'vr-cardboard',
        'title' => 'Medical Simulation',
        'description' => 'VR/AR/haptics simulators with objective skill scoring, procedure rehearsal, and competency-based training.',
        'keywords' => ['VR/AR', 'Haptics', 'Training', 'Simulation'],
        'gradient' => 'from-amber-500 to-yellow-500'
    ],
    [
        'icon' => 'brain',
        'title' => 'AI in Healthcare',
        'description' => 'Biomedical imaging, radiology AI, physiologic modeling, clinical decision support, and outcome optimization.',
        'keywords' => ['Medical Imaging', 'Radiology AI', 'Decision Support'],
        'gradient' => 'from-rose-500 to-pink-500'
    ],
    [
        'icon' => 'handshake',
        'title' => 'Translation & Ecosystem',
        'description' => 'V&V protocols, documentation standards, technology transfer, and healthcare partnership development.',
        'keywords' => ['V&V', 'Tech Transfer', 'Partnerships'],
        'gradient' => 'from-pink-500 to-red-500'
    ]
];

// Year-1 roadmap with status
$roadmap = [
    [
        'quarter' => 'Q1',
        'title' => 'Discovery',
        'subtitle' => 'Jan - Mar 2026',
        'items' => ['Clinical needs-finding sessions', 'Select 2 pilot use-cases', 'Define success metrics', 'Establish governance'],
        'status' => 'upcoming'
    ],
    [
        'quarter' => 'Q2',
        'title' => 'Build',
        'subtitle' => 'Apr - Jun 2026',
        'items' => ['Prototype v1 development', 'Bench/simulation testing', 'Submit joint proposal', 'Industry engagement'],
        'status' => 'upcoming'
    ],
    [
        'quarter' => 'Q3',
        'title' => 'Validate',
        'subtitle' => 'Jul - Sep 2026',
        'items' => ['Prototype v2 iteration', 'Usability studies', 'Demo day showcase', 'Clinician workshop #1'],
        'status' => 'upcoming'
    ],
    [
        'quarter' => 'Q4',
        'title' => 'Translate',
        'subtitle' => 'Oct - Dec 2026',
        'items' => ['Translation package', 'Publications/IP filing', 'Workshop series #2', 'Year-2 planning'],
        'status' => 'upcoming'
    ]
];

// Synergy partners with icons
$synergies = [
    ['name' => 'AlfaisalX', 'desc' => 'Robotics platforms & testbeds', 'icon' => 'robot'],
    ['name' => 'Alfa-HI', 'desc' => 'Clinical needs & ecosystem', 'icon' => 'hospital'],
    ['name' => 'BME Dept', 'desc' => 'Curriculum & capstones', 'icon' => 'graduation-cap'],
    ['name' => 'EE Dept', 'desc' => 'Controls & embedded AI', 'icon' => 'microchip'],
    ['name' => 'HPC Center', 'desc' => 'Compute & data infrastructure', 'icon' => 'server']
];

// KPIs with targets
$kpis = [
    ['value' => '2', 'label' => 'Pilot Prototypes', 'icon' => 'flask', 'desc' => 'Validated systems'],
    ['value' => '2+', 'label' => 'Funding Proposals', 'icon' => 'file-invoice-dollar', 'desc' => 'Submitted'],
    ['value' => '4', 'label' => 'Workshops', 'icon' => 'chalkboard-teacher', 'desc' => 'For clinicians'],
    ['value' => '2+', 'label' => 'Publications', 'icon' => 'file-alt', 'desc' => 'Or IP filings']
];

// Pipeline steps
$pipeline = [
    ['icon' => 'stethoscope', 'label' => 'Clinical Need'],
    ['icon' => 'lightbulb', 'label' => 'Concept'],
    ['icon' => 'tools', 'label' => 'Prototype'],
    ['icon' => 'clipboard-check', 'label' => 'Validation'],
    ['icon' => 'hospital', 'label' => 'Clinical Use']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/head.php'; ?>
    <style>
    /* ============================================
       MedX Premium Design Styles
       ============================================ */
    
    :root {
        --medx-primary: #ef4444;
        --medx-primary-dark: #dc2626;
        --medx-primary-light: #fca5a5;
        --medx-gradient: linear-gradient(135deg, #ef4444 0%, #f97316 50%, #fbbf24 100%);
        --medx-gradient-dark: linear-gradient(135deg, #dc2626 0%, #ea580c 100%);
    }
    
    /* Hero Section - Premium Design */
    .medx-hero {
        position: relative;
        min-height: 85vh;
        display: flex;
        align-items: center;
        background: var(--color-bg);
        overflow: hidden;
    }
    
    .medx-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 80%;
        height: 200%;
        background: radial-gradient(ellipse at center, rgba(239, 68, 68, 0.12) 0%, transparent 70%);
        animation: pulse-glow 8s ease-in-out infinite;
    }
    
    .medx-hero::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(239, 68, 68, 0.3), transparent);
    }
    
    @keyframes pulse-glow {
        0%, 100% { opacity: 0.5; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.05); }
    }
    
    .hero-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-12);
        align-items: center;
    }
    
    @media (max-width: 1024px) {
        .hero-grid {
            grid-template-columns: 1fr;
            text-align: center;
        }
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
    }
    
    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: var(--space-2);
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        padding: var(--space-2) var(--space-4);
        border-radius: var(--radius-full);
        font-size: var(--text-sm);
        font-weight: 500;
        color: var(--medx-primary);
        margin-bottom: var(--space-6);
        animation: fadeInUp 0.6s ease-out;
    }
    
    .hero-eyebrow .pulse-dot {
        width: 8px;
        height: 8px;
        background: var(--medx-primary);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
    }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .hero-title {
        font-size: clamp(3rem, 6vw, 5rem);
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: var(--space-6);
        animation: fadeInUp 0.6s ease-out 0.1s both;
    }
    
    .hero-title .gradient-text {
        background: var(--medx-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .hero-subtitle {
        font-size: var(--text-xl);
        color: var(--color-text-muted);
        line-height: 1.7;
        margin-bottom: var(--space-8);
        max-width: 540px;
        animation: fadeInUp 0.6s ease-out 0.2s both;
    }
    
    @media (max-width: 1024px) {
        .hero-subtitle {
            margin-left: auto;
            margin-right: auto;
        }
    }
    
    .hero-actions {
        display: flex;
        gap: var(--space-4);
        animation: fadeInUp 0.6s ease-out 0.3s both;
    }
    
    @media (max-width: 1024px) {
        .hero-actions {
            justify-content: center;
        }
    }
    
    .btn-medx {
        background: var(--medx-gradient);
        color: white;
        border: none;
        padding: var(--space-4) var(--space-8);
        border-radius: var(--radius-lg);
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: var(--space-2);
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(239, 68, 68, 0.3);
    }
    
    .btn-medx:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(239, 68, 68, 0.4);
    }
    
    /* Hero Visual - Medical Icon Grid */
    .hero-visual {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeInUp 0.6s ease-out 0.4s both;
    }
    
    .hero-icon-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--space-4);
        max-width: 400px;
    }
    
    .hero-icon-card {
        aspect-ratio: 1;
        background: var(--color-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--text-3xl);
        color: var(--medx-primary);
        transition: all 0.3s ease;
        animation: fadeInScale 0.5s ease-out backwards;
    }
    
    .hero-icon-card:nth-child(1) { animation-delay: 0.5s; }
    .hero-icon-card:nth-child(2) { animation-delay: 0.6s; }
    .hero-icon-card:nth-child(3) { animation-delay: 0.7s; }
    .hero-icon-card:nth-child(4) { animation-delay: 0.8s; }
    .hero-icon-card:nth-child(5) { animation-delay: 0.9s; }
    .hero-icon-card:nth-child(6) { animation-delay: 1.0s; }
    
    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.8); }
        to { opacity: 1; transform: scale(1); }
    }
    
    .hero-icon-card:hover {
        border-color: var(--medx-primary);
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(239, 68, 68, 0.15);
    }
    
    .hero-icon-card.featured {
        grid-column: span 2;
        grid-row: span 2;
        font-size: 4rem;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(249, 115, 22, 0.05) 100%);
        border-color: rgba(239, 68, 68, 0.3);
    }
    
    /* Pipeline Section */
    .pipeline-section {
        background: linear-gradient(180deg, var(--color-bg) 0%, var(--color-surface) 100%);
        padding: var(--space-16) 0;
        position: relative;
        overflow: hidden;
    }
    
    .pipeline-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        flex-wrap: wrap;
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .pipeline-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: var(--space-3);
        padding: var(--space-4);
        position: relative;
    }
    
    .pipeline-icon {
        width: 80px;
        height: 80px;
        background: var(--color-surface);
        border: 2px solid var(--border-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--text-2xl);
        color: var(--medx-primary);
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
    }
    
    .pipeline-step:hover .pipeline-icon {
        background: rgba(239, 68, 68, 0.1);
        border-color: var(--medx-primary);
        transform: scale(1.1);
    }
    
    .pipeline-label {
        font-size: var(--text-sm);
        font-weight: 600;
        color: var(--color-text);
        text-align: center;
    }
    
    .pipeline-arrow {
        width: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--medx-primary);
        opacity: 0.5;
    }
    
    @media (max-width: 768px) {
        .pipeline-arrow {
            display: none;
        }
        .pipeline-step {
            width: 33.33%;
        }
    }
    
    /* Unit Lead Card - Enhanced */
    .lead-section {
        padding: var(--space-16) 0;
    }
    
    .lead-card {
        display: flex;
        align-items: center;
        gap: var(--space-8);
        background: var(--color-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-2xl);
        padding: var(--space-8);
        max-width: 700px;
        margin: 0 auto;
        position: relative;
        overflow: hidden;
    }
    
    .lead-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--medx-gradient);
    }
    
    .lead-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid rgba(239, 68, 68, 0.2);
        flex-shrink: 0;
    }
    
    .lead-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .lead-avatar .initials {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(239, 68, 68, 0.1);
        font-size: var(--text-3xl);
        font-weight: 700;
        color: var(--medx-primary);
    }
    
    .lead-info h3 {
        font-size: var(--text-2xl);
        margin-bottom: var(--space-2);
    }
    
    .lead-role {
        display: inline-block;
        background: var(--medx-gradient);
        color: white;
        padding: var(--space-1) var(--space-3);
        border-radius: var(--radius-full);
        font-size: var(--text-sm);
        font-weight: 600;
        margin-bottom: var(--space-3);
    }
    
    .lead-bio {
        color: var(--color-text-muted);
        font-size: var(--text-sm);
        line-height: 1.6;
    }
    
    @media (max-width: 640px) {
        .lead-card {
            flex-direction: column;
            text-align: center;
        }
        .lead-card::before {
            width: 100%;
            height: 4px;
            left: 0;
            top: 0;
        }
    }
    
    /* Vision Cards - Glass Design */
    .vision-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-6);
    }
    
    @media (max-width: 768px) {
        .vision-grid { grid-template-columns: 1fr; }
    }
    
    .vision-card {
        background: var(--color-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-2xl);
        padding: var(--space-8);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .vision-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(239, 68, 68, 0.1) 0%, transparent 70%);
        pointer-events: none;
    }
    
    .vision-card:hover {
        border-color: rgba(239, 68, 68, 0.3);
        transform: translateY(-4px);
    }
    
    .vision-icon {
        width: 60px;
        height: 60px;
        background: rgba(239, 68, 68, 0.1);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--text-2xl);
        color: var(--medx-primary);
        margin-bottom: var(--space-4);
    }
    
    .vision-card h3 {
        font-size: var(--text-xl);
        margin-bottom: var(--space-3);
    }
    
    .vision-card p {
        color: var(--color-text-muted);
        line-height: 1.7;
    }
    
    /* Pillars - Bento Style */
    .pillars-bento {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: repeat(2, auto);
        gap: var(--space-4);
    }
    
    @media (max-width: 1024px) {
        .pillars-bento {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 640px) {
        .pillars-bento {
            grid-template-columns: 1fr;
        }
    }
    
    .pillar-card {
        background: var(--color-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-2xl);
        padding: var(--space-6);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .pillar-card:first-child {
        grid-row: span 2;
    }
    
    .pillar-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--medx-gradient);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .pillar-card:hover {
        border-color: rgba(239, 68, 68, 0.4);
        transform: translateY(-4px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    }
    
    .pillar-card:hover::before {
        opacity: 1;
    }
    
    .pillar-icon {
        width: 56px;
        height: 56px;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: var(--radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--text-2xl);
        color: var(--medx-primary);
        margin-bottom: var(--space-4);
        transition: all 0.3s ease;
    }
    
    .pillar-card:hover .pillar-icon {
        background: var(--medx-primary);
        color: white;
        transform: scale(1.05);
    }
    
    .pillar-card h3 {
        font-size: var(--text-lg);
        margin-bottom: var(--space-2);
    }
    
    .pillar-card p {
        color: var(--color-text-muted);
        font-size: var(--text-sm);
        line-height: 1.6;
        margin-bottom: var(--space-4);
    }
    
    .pillar-tags {
        display: flex;
        flex-wrap: wrap;
        gap: var(--space-2);
    }
    
    .pillar-tag {
        background: rgba(239, 68, 68, 0.08);
        color: var(--medx-primary);
        padding: var(--space-1) var(--space-2);
        border-radius: var(--radius-sm);
        font-size: var(--text-xs);
        font-weight: 500;
    }
    
    /* KPIs - Modern Cards */
    .kpis-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--space-4);
    }
    
    @media (max-width: 1024px) {
        .kpis-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    .kpi-card {
        background: var(--color-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-2xl);
        padding: var(--space-6);
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .kpi-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--medx-gradient);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .kpi-card:hover {
        transform: translateY(-4px);
        border-color: rgba(239, 68, 68, 0.3);
    }
    
    .kpi-card:hover::after {
        transform: scaleX(1);
    }
    
    .kpi-icon {
        width: 50px;
        height: 50px;
        background: rgba(239, 68, 68, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--text-xl);
        color: var(--medx-primary);
        margin: 0 auto var(--space-4);
    }
    
    .kpi-value {
        font-size: var(--text-4xl);
        font-weight: 800;
        background: var(--medx-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: var(--space-1);
    }
    
    .kpi-label {
        font-size: var(--text-base);
        font-weight: 600;
        margin-bottom: var(--space-1);
    }
    
    .kpi-desc {
        font-size: var(--text-sm);
        color: var(--color-text-muted);
    }
    
    /* Roadmap - Timeline Design */
    .roadmap-timeline {
        position: relative;
        padding: var(--space-8) 0;
    }
    
    .roadmap-timeline::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--border-color);
        transform: translateY(-50%);
    }
    
    @media (max-width: 768px) {
        .roadmap-timeline::before {
            display: none;
        }
    }
    
    .roadmap-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--space-6);
        position: relative;
    }
    
    @media (max-width: 1024px) {
        .roadmap-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    @media (max-width: 640px) {
        .roadmap-grid { grid-template-columns: 1fr; }
    }
    
    .roadmap-card {
        background: var(--color-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-2xl);
        padding: var(--space-6);
        position: relative;
        transition: all 0.3s ease;
    }
    
    .roadmap-card::before {
        content: '';
        position: absolute;
        top: -20px;
        left: 50%;
        transform: translateX(-50%);
        width: 16px;
        height: 16px;
        background: var(--medx-primary);
        border: 4px solid var(--color-bg);
        border-radius: 50%;
        z-index: 2;
    }
    
    @media (max-width: 768px) {
        .roadmap-card::before {
            display: none;
        }
    }
    
    .roadmap-card:hover {
        border-color: rgba(239, 68, 68, 0.4);
        transform: translateY(-4px);
    }
    
    .roadmap-quarter {
        display: inline-block;
        background: var(--medx-gradient);
        color: white;
        padding: var(--space-1) var(--space-3);
        border-radius: var(--radius-full);
        font-size: var(--text-sm);
        font-weight: 700;
        margin-bottom: var(--space-2);
    }
    
    .roadmap-card h4 {
        font-size: var(--text-lg);
        margin-bottom: var(--space-1);
    }
    
    .roadmap-subtitle {
        font-size: var(--text-sm);
        color: var(--color-text-muted);
        margin-bottom: var(--space-4);
    }
    
    .roadmap-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .roadmap-card li {
        position: relative;
        padding-left: var(--space-5);
        margin-bottom: var(--space-2);
        font-size: var(--text-sm);
        color: var(--color-text-muted);
    }
    
    .roadmap-card li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 8px;
        width: 6px;
        height: 6px;
        background: var(--medx-primary);
        border-radius: 50%;
    }
    
    /* Synergies - Icon Cards */
    .synergies-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: var(--space-4);
    }
    
    @media (max-width: 1024px) {
        .synergies-grid { grid-template-columns: repeat(3, 1fr); }
    }
    
    @media (max-width: 640px) {
        .synergies-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    .synergy-card {
        background: var(--color-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-5);
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .synergy-card:hover {
        border-color: rgba(239, 68, 68, 0.4);
        transform: translateY(-4px);
    }
    
    .synergy-icon {
        width: 50px;
        height: 50px;
        background: rgba(239, 68, 68, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--text-xl);
        color: var(--medx-primary);
        margin: 0 auto var(--space-3);
    }
    
    .synergy-card h4 {
        font-size: var(--text-sm);
        font-weight: 600;
        margin-bottom: var(--space-1);
    }
    
    .synergy-card p {
        font-size: var(--text-xs);
        color: var(--color-text-muted);
        margin: 0;
    }
    
    /* CTA Section - Gradient */
    .medx-cta {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(249, 115, 22, 0.05) 100%);
        border-top: 1px solid rgba(239, 68, 68, 0.2);
        border-bottom: 1px solid rgba(239, 68, 68, 0.2);
        padding: var(--space-16) 0;
        text-align: center;
    }
    
    .cta-content {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .cta-content h2 {
        font-size: var(--text-3xl);
        margin-bottom: var(--space-4);
    }
    
    .cta-content p {
        color: var(--color-text-muted);
        margin-bottom: var(--space-8);
    }
    
    .cta-buttons {
        display: flex;
        gap: var(--space-4);
        justify-content: center;
    }
    
    @media (max-width: 480px) {
        .cta-buttons {
            flex-direction: column;
        }
    }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main id="main-content">
        <!-- Hero Section -->
        <section class="medx-hero">
            <div class="container">
                <div class="hero-grid">
                    <div class="hero-content">
                        <div class="hero-eyebrow">
                            <span class="pulse-dot"></span>
                            AlfaisalX Research Unit
                        </div>
                        <h1 class="hero-title">
                            <span class="gradient-text">MedX</span><br>
                            Medical Robotics &<br>AI in Healthcare
                        </h1>
                        <p class="hero-subtitle">
                            Bridging the gap from lab bench to clinical use — delivering validated prototypes, surgical copilots, and AI-powered healthcare solutions.
                        </p>
                        <div class="hero-actions">
                            <a href="#pillars" class="btn-medx">
                                <i class="fas fa-flask"></i> Explore Research
                            </a>
                            <a href="<?php echo SITE_URL; ?>/contact/" class="btn btn-outline">
                                <i class="fas fa-handshake"></i> Partner With Us
                            </a>
                        </div>
                    </div>
                    <div class="hero-visual">
                        <div class="hero-icon-grid">
                            <div class="hero-icon-card featured">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <div class="hero-icon-card">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div class="hero-icon-card">
                                <i class="fas fa-brain"></i>
                            </div>
                            <div class="hero-icon-card">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div class="hero-icon-card">
                                <i class="fas fa-vr-cardboard"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pipeline Section -->
        <section class="pipeline-section">
            <div class="container">
                <div class="section-header text-center" style="margin-bottom: var(--space-8);">
                    <span class="section-tag">// Translation Pipeline</span>
                    <h2 class="section-title">From Clinical Need to Clinical Use</h2>
                </div>
                <div class="pipeline-wrapper">
                    <?php foreach ($pipeline as $i => $step): ?>
                    <div class="pipeline-step">
                        <div class="pipeline-icon">
                            <i class="fas fa-<?php echo $step['icon']; ?>"></i>
                        </div>
                        <span class="pipeline-label"><?php echo $step['label']; ?></span>
                    </div>
                    <?php if ($i < count($pipeline) - 1): ?>
                    <div class="pipeline-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Unit Lead -->
        <?php if ($unit_lead): ?>
        <section class="lead-section section">
            <div class="container">
                <div class="section-header text-center">
                    <span class="section-tag">// Leadership</span>
                    <h2 class="section-title">Unit Lead</h2>
                </div>
                <div class="lead-card">
                    <div class="lead-avatar">
                        <?php if (!empty($unit_lead['image'])): ?>
                            <img src="<?php echo ASSETS_URL; ?>/images/team/<?php echo $unit_lead['image']; ?>" alt="<?php echo $unit_lead['name']; ?>">
                        <?php else: ?>
                            <span class="initials"><?php echo $unit_lead['initials']; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="lead-info">
                        <h3><?php echo htmlspecialchars($unit_lead['name']); ?></h3>
                        <span class="lead-role">MedX Unit Lead</span>
                        <p class="lead-bio"><?php echo htmlspecialchars($unit_lead['bio'] ?? 'Leading the MedX unit in developing cutting-edge medical robotics and AI solutions for healthcare.'); ?></p>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Vision & Mission -->
        <section class="section section-alt">
            <div class="container">
                <div class="section-header text-center">
                    <span class="section-tag">// Purpose</span>
                    <h2 class="section-title">Vision & Mission</h2>
                </div>
                
                <div class="vision-grid">
                    <div class="vision-card">
                        <div class="vision-icon"><i class="fas fa-eye"></i></div>
                        <h3>Our Vision</h3>
                        <p>Build a nationally visible, clinically grounded hub delivering safe, validated, and transferable medical robotics and AI solutions that transform healthcare delivery in Saudi Arabia and beyond.</p>
                    </div>
                    <div class="vision-card">
                        <div class="vision-icon"><i class="fas fa-rocket"></i></div>
                        <h3>Year-1 Mission</h3>
                        <p>Deliver two pilot prototypes (simulation/bench validated) and at least one translation-ready package. Establish a repeatable pipeline from needs finding → prototype → V&V → clinical adoption.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Thematic Pillars -->
        <section class="section" id="pillars">
            <div class="container">
                <div class="section-header text-center">
                    <span class="section-tag">// Research Focus</span>
                    <h2 class="section-title">Thematic Pillars</h2>
                    <p class="section-subtitle">Five integrated research streams driving healthcare innovation</p>
                </div>
                
                <div class="pillars-bento">
                    <?php foreach ($pillars as $pillar): ?>
                    <div class="pillar-card">
                        <div class="pillar-icon">
                            <i class="fas fa-<?php echo $pillar['icon']; ?>"></i>
                        </div>
                        <h3><?php echo $pillar['title']; ?></h3>
                        <p><?php echo $pillar['description']; ?></p>
                        <div class="pillar-tags">
                            <?php foreach ($pillar['keywords'] as $keyword): ?>
                            <span class="pillar-tag"><?php echo $keyword; ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Year-1 KPIs -->
        <section class="section section-alt">
            <div class="container">
                <div class="section-header text-center">
                    <span class="section-tag">// Targets</span>
                    <h2 class="section-title">Year-1 KPIs</h2>
                    <p class="section-subtitle">Measurable outcomes driving our first year</p>
                </div>
                
                <div class="kpis-grid">
                    <?php foreach ($kpis as $kpi): ?>
                    <div class="kpi-card">
                        <div class="kpi-icon">
                            <i class="fas fa-<?php echo $kpi['icon']; ?>"></i>
                        </div>
                        <div class="kpi-value"><?php echo $kpi['value']; ?></div>
                        <div class="kpi-label"><?php echo $kpi['label']; ?></div>
                        <div class="kpi-desc"><?php echo $kpi['desc']; ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Roadmap -->
        <section class="section">
            <div class="container">
                <div class="section-header text-center">
                    <span class="section-tag">// 12-Month Plan</span>
                    <h2 class="section-title">Year-1 Roadmap</h2>
                    <p class="section-subtitle">Quarterly milestones from discovery to translation</p>
                </div>
                
                <div class="roadmap-timeline">
                    <div class="roadmap-grid">
                        <?php foreach ($roadmap as $phase): ?>
                        <div class="roadmap-card">
                            <span class="roadmap-quarter"><?php echo $phase['quarter']; ?></span>
                            <h4><?php echo $phase['title']; ?></h4>
                            <div class="roadmap-subtitle"><?php echo $phase['subtitle']; ?></div>
                            <ul>
                                <?php foreach ($phase['items'] as $item): ?>
                                <li><?php echo $item; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Synergies -->
        <section class="section section-alt">
            <div class="container">
                <div class="section-header text-center">
                    <span class="section-tag">// Collaboration</span>
                    <h2 class="section-title">Synergy Partners</h2>
                    <p class="section-subtitle">Cross-departmental integration for maximum impact</p>
                </div>
                
                <div class="synergies-grid">
                    <?php foreach ($synergies as $synergy): ?>
                    <div class="synergy-card">
                        <div class="synergy-icon">
                            <i class="fas fa-<?php echo $synergy['icon']; ?>"></i>
                        </div>
                        <h4><?php echo $synergy['name']; ?></h4>
                        <p><?php echo $synergy['desc']; ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="medx-cta">
            <div class="container">
                <div class="cta-content">
                    <h2>Ready to Collaborate?</h2>
                    <p>Whether you're a clinician, researcher, or industry partner, we'd love to explore how MedX can support your healthcare innovation goals.</p>
                    <div class="cta-buttons">
                        <a href="<?php echo SITE_URL; ?>/contact/" class="btn-medx">
                            <i class="fas fa-envelope"></i> Get in Touch
                        </a>
                        <a href="<?php echo SITE_URL; ?>/careers/" class="btn btn-outline">
                            <i class="fas fa-users"></i> Join the Team
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>




