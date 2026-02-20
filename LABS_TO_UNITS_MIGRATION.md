# Labs → Units Migration Strategy

## Executive Summary
Consolidate duplicate "labs" and "research_areas" tables into a unified "Research Units" system, replacing all "labs" terminology with "units".

---

## Current State Analysis

### Labs Table (3 items) - `/labs/`
| Slug | Name | Focus |
|------|------|-------|
| agentic-workflows | Agentic Workflows for Business Automation Unit | AI agents, LLMs |
| robotics-uavs | Robotics, UAVs & Autonomous Systems Unit | Ground robots, drones |
| medical-robotics | Medical & Health Robotics Unit | Surgery, rehabilitation |

### Research Areas Table (5 items) - `/research/`
| # | Slug | Name |
|---|------|------|
| 01 | robotics-autonomous-systems | Robotics & Autonomous Systems |
| 02 | uavs-aerial-autonomy | UAVs & Aerial Autonomy |
| 03 | agentic-ai-workflows | Agentic AI & Intelligent Workflows |
| 04 | commercialization-impact | Commercialization & Social Impact |
| 05 | medx | MedX: Medical Robotics & AI in Healthcare |

---

## Identified Duplications

| Labs Entry | Research Areas Entry | Action |
|------------|---------------------|--------|
| `agentic-workflows` | `agentic-ai-workflows` | **MERGE** - Keep research_areas |
| `robotics-uavs` | `robotics-autonomous-systems` + `uavs-aerial-autonomy` | **MERGE** - Combine into single unit |
| `medical-robotics` | `medx` | **REPLACE** - Use MedX |
| *(none)* | `commercialization-impact` | **KEEP** - Unique to research_areas |

---

## Proposed Final Structure: 4 Research Units

| # | Slug | Name | Icon | Color | Lead |
|---|------|------|------|-------|------|
| 01 | robotics-autonomous-systems | Robotics, UAVs & Autonomous Systems | robot | Blue | Director |
| 02 | agentic-ai-workflows | Agentic AI & Intelligent Workflows | brain | Purple | TBD |
| 03 | medx | MedX: Medical Robotics & AI in Healthcare | heartbeat | Red | Dr. Bahloul |
| 04 | commercialization-impact | Commercialization & Social Impact | chart-line | Amber | TBD |

**Key Change**: Merge `uavs-aerial-autonomy` INTO `robotics-autonomous-systems` (renamed to include UAVs)

---

## Implementation Plan

### Phase 1: Database Updates
- [ ] Rename `robotics-autonomous-systems` → Include UAVs scope
- [ ] Delete `uavs-aerial-autonomy` entry (redundant)
- [ ] Keep `labs` table but mark deprecated
- [ ] Update navigation: Remove "Labs" → merge into "Research"

### Phase 2: File Changes
- [ ] Delete or redirect `/labs/index.php`
- [ ] Update `/research/index.php` - unified units page
- [ ] Delete individual lab pages (if any exist under `/labs/`)

### Phase 3: Navigation & UI
- [ ] Remove "Labs" from navigation table
- [ ] Update `index.php` bento grid - 4 cards, direct links
- [ ] Add MedX featured card with link to `/research/medx.php`

### Phase 4: Code Cleanup
- [ ] Remove `getLabs()` calls or alias to research_areas
- [ ] Update seed.php - remove labs seeding or mark deprecated
- [ ] Rename CSS classes: `.lab-*` → `.unit-*`

---

## Files to Modify

| File | Action |
|------|--------|
| `database/alfaisalx.db` | Merge UAV into Robotics, delete UAV row |
| `database/seed.php` | Comment out seedLabs(), update research_areas |
| `includes/config.php` | Remove/alias `getLabs()` |
| `database/Database.php` | Add `getUnits()` alias |
| `navigation table` | Remove Labs entry |
| `/labs/index.php` | Delete or redirect to /research/ |
| `/research/index.php` | Already serves as units overview |
| `/index.php` | Update bento grid - 4 cards with MedX |
| `assets/css/main.css` | Rename `.lab-*` → `.unit-*` |

---

## Implementation Order

1. **Update research_areas** (merge UAVs into Robotics, renumber)
2. **Update navigation** (remove Labs)
3. **Update index.php** (4 cards with MedX link)
4. **Deprecate labs** (comment in seed.php)
5. **Redirect labs page** (to research)
6. **Clean up CSS** (rename classes)

---

## Rollback Plan
- Keep `labs` table data (just deprecated)
- Restore navigation entry if needed
- Uncommment seedLabs() if reverting




