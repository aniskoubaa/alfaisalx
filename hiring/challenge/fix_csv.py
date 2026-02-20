#!/usr/bin/env python3
"""Fix the corrupted CSV entries."""

csv_path = "/Applications/XAMPP/xamppfiles/htdocs/alfaisalx/hiring/list_of-candiates/ResearchFellowPostdoctoralPosition.csv"

# Read the file
with open(csv_path, 'r', encoding='utf-8') as f:
    lines = f.readlines()

# Find and fix corrupted lines
fixed_lines = []
for i, line in enumerate(lines):
    line_num = i + 1
    
    # Fix line 119 (corrupted Nitasha Khan entry)
    if line_num == 119 and "K126,Nitasha,K126" in line:
        fixed_lines.append("126,Nitasha,Khan,,,khannitasha@ymail.com,,,,\n")
        print(f"Fixed line {line_num}: Nitasha Khan")
    
    # Fix line 122 (corrupted Hiba Ghrab entry merged with John Doe)
    elif line_num == 122 and "Hib129" in line:
        # Split this into two lines
        fixed_lines.append("129,Hiba,Ghrab,,,hiba.ghrab@etudiant-enit.utm.tn,,,,\n")
        fixed_lines.append("130,John,Doe,,,john.doe@email.com,,,,\n")
        print(f"Fixed line {line_num}: Split Hiba Ghrab and John Doe")
    
    else:
        fixed_lines.append(line)

# Write back
with open(csv_path, 'w', encoding='utf-8') as f:
    f.writelines(fixed_lines)

print(f"\n✅ CSV file fixed! Total lines: {len(fixed_lines)}")
