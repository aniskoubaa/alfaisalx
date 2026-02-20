# Google Scholar Publication Fetcher using SerpAPI

## Overview
This script automatically fetches publications from Google Scholar starting from 2025 and adds missing ones to the AlfaisalX database.

## Files Created/Modified

### 1. `.env` - Environment Configuration
Added SerpAPI key:
```bash
SERPAPI_API_KEY=747eddc474872d4efe61eac7ec49ad15d735085c47da2f7806de134b27c999cb
```

### 2. `scripts/fetch_serpapi_publications.php` - Main Fetcher Script
Complete PHP script that:
- Loads the SerpAPI key from `.env`
- Fetches all team members with Google Scholar profiles
- Queries SerpAPI for each author's publications from 2025 onwards
- Extracts publication details (title, authors, year, venue, DOI, abstract)
- Checks for duplicates before inserting
- Adds missing publications to the database

## Usage

### Command Line
```bash
/Applications/XAMPP/xamppfiles/bin/php /Applications/XAMPP/xamppfiles/htdocs/alfaisalx/scripts/fetch_serpapi_publications.php
```

### Web Browser
Navigate to:
```
http://localhost/alfaisalx/scripts/fetch_serpapi_publications.php
```

## Features

### Smart Filtering
- **Year Filtering**: Uses `as_ylo` parameter to fetch only publications from 2025 onwards
- **Duplicate Detection**: Checks existing publications by title before inserting
- **Author Attribution**: Properly extracts author lists from publication metadata

### Data Extraction
- **Title**: Publication title
- **Authors**: Complete author list from publication info
- **Venue**: Journal, conference, or publishing house
- **Year**: Publication year (extracted from multiple sources)
- **DOI**: Digital Object Identifier (when available)
- **URL**: Google Scholar link to publication
- **Abstract**: Publication snippet/abstract
- **Type**: Automatically determines if book, journal, or conference

### Error Handling
- Validates API responses
- Handles missing fields gracefully
- Reports errors without stopping execution
- Logs detailed progress for each author

## API Parameters Used

```php
[
    'engine' => 'google_scholar',
    'q' => 'Author Name',
    'api_key' => 'YOUR_API_KEY',
    'as_ylo' => 2025,  // Year low (from)
    'num' => 20,       // Results per page
    'start' => 0       // Pagination offset
]
```

## Example Output

```
=== AlfaisalX Google Scholar Publication Fetcher ===

Target Year: 2025 and onwards
API Key: 747eddc474...999cb

Found 3 team members with Google Scholar links

Processing: Prof. Anis Koubaa
------------------------------------------------------------
  Search query: Anis Koubaa
  Fetching from SerpAPI (start=0)...
    + Added: Enhanced Arabic Spelling Correction with Sequential Transfor... (2025)
    + Added: Dehazing-DiffGAN: Sequential fusion of diffusion models and ... (2025)
    ...
  ✓ Added: 20 | Skipped: 0

Processing: Prof. Driss Benhaddou
------------------------------------------------------------
  ...
  ✓ Added: 20 | Skipped: 0

Processing: Dr. Mohamed Bahloul
------------------------------------------------------------
  ...
  ✓ Added: 15 | Skipped: 5

=== Fetch Complete! ===
```

## Results (First Run)

- **Total Publications Added**: 55
- **Prof. Anis Koubaa**: 20 publications from 2025
- **Prof. Driss Benhaddou**: 20 publications from 2025
- **Dr. Mohamed Bahloul**: 15 publications from 2025

## Customization

### Change Target Year
Edit line 22 in `fetch_serpapi_publications.php`:
```php
private $targetYear = 2025; // Change to desired year
```

### Fetch More Results
The script currently fetches 20 results per author. To enable pagination and fetch more:

1. Remove the `break;` statement on line 144
2. Add pagination logic:
```php
// Instead of break, check for next page
if (isset($results['serpapi_pagination']['next'])) {
    $start += $numResults;
} else {
    break;
}
```

## Notes

- **API Limits**: SerpAPI has rate limits. The current implementation fetches one page (20 results) per author to conserve API calls.
- **Duplicate Handling**: The script checks for existing publications by title. If a publication already exists, it's skipped.
- **Year Extraction**: The script attempts to extract the year from multiple sources in the API response. Some publications may not have a year extracted (shown as empty in output).

## Future Enhancements

1. **Author-specific searches**: Use Google Scholar author IDs when available for more precise results
2. **Citation data**: Extract citation counts from the API response
3. **PDF links**: Store direct PDF links when available
4. **Scheduled execution**: Set up a cron job to run periodically
5. **Email notifications**: Send summaries of newly added publications

## Troubleshooting

### No publications found
- Verify the Google Scholar profile exists for the team member
- Check that the author name in the database is correct
- Ensure the SerpAPI key is valid

### API errors
- Check SerpAPI account status and remaining credits
- Verify the API key in `.env` is correct
- Check internet connectivity

### Database errors
- Ensure the database file has write permissions
- Verify the publications table schema matches the insert statement
- Check SQLite is properly installed
