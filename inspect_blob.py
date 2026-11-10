
import re

def inspect():
    with open('contact.html', 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Split by DOCTYPE, keeping the delimiter
    # Note: re.split might consume the delimiter, so we'll look for the start index
    parts = content.split('<!DOCTYPE html>')
    
    print(f"Found {len(parts)} parts (part 0 might be empty if file starts with doctype)")
    
    for i, part in enumerate(parts):
        if not part.strip():
            continue
            
        full_part = '<!DOCTYPE html>' + part
        
        # Extract Title
        title_match = re.search(r'<title>(.*?)</title>', full_part, re.IGNORECASE)
        title = title_match.group(1) if title_match else "No Title Found"
        
        # Extract a unique identifier / hint
        hint = full_part[0:500].replace('\n', ' ')
        
        print(f"Part {i}: Title='{title}'")
        # print(f"  Hint: {hint}...")

if __name__ == "__main__":
    inspect()
