
import sqlite3
import re
import sys
import os
import datetime

# Check for scholarly
try:
    from scholarly import scholarly
except ImportError:
    print("Error: 'scholarly' library is not installed.")
    print("Please install it using: pip install scholarly")
    sys.exit(1)

# Configuration
DB_PATH = os.path.join(os.path.dirname(__file__), '../database/alfaisalx.db')
TARGET_YEAR = 2026

def get_scholar_id(url):
    """Extracts the Google Scholar ID from a URL."""
    if not url:
        return None
    # Match user=ID pattern
    match = re.search(r'user=([^&]+)', url)
    if match:
        return match.group(1)
    return None

def fetch_publications():
    print(f"Connecting to database at {DB_PATH}...")
    try:
        conn = sqlite3.connect(DB_PATH)
        cursor = conn.cursor()
    except sqlite3.Error as e:
        print(f"Database error: {e}")
        return

    # Get team members with scholar links
    cursor.execute("SELECT name, google_scholar FROM team_members WHERE google_scholar IS NOT NULL AND google_scholar != ''")
    members = cursor.fetchall()
    
    print(f"Found {len(members)} team members with Google Scholar links.")
    print(f"Targeting publications from year: {TARGET_YEAR}")

    for name, url in members:
        scholar_id = get_scholar_id(url)
        author = None
        
        print(f"\nProcessing {name}...")
        
        try:
            if scholar_id:
                print(f"  - Using ID: {scholar_id}")
                author = scholarly.search_author_id(scholar_id)
            else:
                # If no ID but we have a URL (like search_authors), try to search by name or extract name
                print(f"  - No ID found in URL ({url}). Searching by name '{name}'...")
                
                # Clean name (remove titles)
                clean_name = name.replace('Prof.', '').replace('Dr.', '').strip()
                if clean_name != name:
                    print(f"    (Cleaned name: '{clean_name}')")
                
                search_query = scholarly.search_author(clean_name)
                try:
                    author = next(search_query)
                    print(f"  - Found author: {author['name']} (ID: {author['scholar_id']})")
                except StopIteration:
                    print(f"  - Author not found via search.")
                    continue

            # Fill publication data
            print("  - Fetching publications (this may take a moment)...")
            author = scholarly.fill(author, sections=['publications'])
            
            pubs = author['publications']
            print(f"  - Found {len(pubs)} total publications.")
            
            count = 0
            for pub in pubs:
                bib = pub['bib']
                title = bib.get('title', 'Unknown Title')
                pub_year = bib.get('pub_year')
                
                # Check year
                if str(pub_year) != str(TARGET_YEAR):
                    continue
                    
                # Check duplicates based on title
                cursor.execute("SELECT id FROM publications WHERE title = ?", (title,))
                if cursor.fetchone():
                    print(f"    * Skipping duplicate: {title[:30]}...")
                    continue 

                # Prepare data
                venue = bib.get('citation', '') 
                # Try better venue extraction if possible, or fallback
                
                authors_str = name # Default
                # Note: 'author' list is often missing in the shallow 'publications' list unless we fill specific pub
                # But filling specific pub is slow. We will skip author list for now or use the prof name.

                # Determine type guess
                pub_type = 'journal' # Default

                # Insert
                cursor.execute("""
                    INSERT INTO publications (title, authors, venue, year, type, url, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, datetime('now'))
                """, (
                    title, 
                    authors_str, # In a real full scrape we'd get all authors
                    venue, 
                    pub_year, 
                    pub_type, 
                    f"https://scholar.google.com{pub.get('author_pub_id', '')}"
                )) 
                
                print(f"    + Added: {title[:50]}...")
                count += 1
                
            print(f"  - Successfully added {count} publications from {TARGET_YEAR}.")
            conn.commit()

        except Exception as e:
            print(f"  ! Error processing {name}: {e}")

    conn.close()
    print("\nDone!")

if __name__ == "__main__":
    fetch_publications()
