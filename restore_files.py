
import re
import os

def restore():
    # Read the blob from one of the corrupted files (they should all be the same)
    with open('contact.html', 'r', encoding='utf-8') as f:
        content = f.read()
    
    parts = content.split('<!DOCTYPE html>')
    
    # Filter out empty parts (usually the first one if file starts with delimiter)
    documents = [f'<!DOCTYPE html>{p}' for p in parts if p.strip()]
    
    print(f"Found {len(documents)} documents.")
    
    # Define expected order and fallbacks
    # Filename -> Expected Title keyword (case insensitive)
    file_map = [
        ("about.html", "about"),
        ("blog-details.html", "blog details"),
        ("blog.html", "blog"),
        ("contact.html", "contact"),
        ("home.html", ["home", "index"]), # Title might be "Home" or "Index" or just "BL Capital"
        ("portfolio-details.html", "portfolio details"),
        ("portfolio.html", "portfolio"),
        ("privacy-policy.html", "privacy policy"),
        ("service-details.html", "service details"),
        ("services.html", "services"),
        ("starter-page.html", "starter page"),
        ("team.html", "team"),
        ("Terms.html", ["terms", "terms of service"]),
        ("test-contact-form.html", "contact form test"),
        ("testimonials.html", "testimonials"),
    ]
    
    for i, doc in enumerate(documents):
        if i >= len(file_map):
            print(f"Warning: More documents than mapped files! Doc index {i}")
            break
            
        filename, expected_keyword = file_map[i]
        
        # Check title
        title_match = re.search(r'<title>(.*?)</title>', doc, re.IGNORECASE)
        title = title_match.group(1) if title_match else ""
        
        print(f"Restoring {filename} from part {i} (Title: {title})")
        
        # Simple validation
        # If expected keyword is list, check any. If string, check it.
        # But allow bypass for 'home.html' if title is just generic
        
        match = False
        if isinstance(expected_keyword, list):
            if any(k.lower() in title.lower() for k in expected_keyword):
                match = True
        else:
            if expected_keyword.lower() in title.lower():
                match = True
        
        if not match and filename == "home.html":
             # Home page often has just Company Name
             match = True 

        if not match:
            print(f"  WARNING: Title '{title}' does not match expected keyword '{expected_keyword}' for {filename}. Proceeding anyway based on alpha-sort assumption.")

        with open(filename, 'w', encoding='utf-8') as f:
            f.write(doc)
            print(f"  Written {len(doc)} bytes to {filename}")

if __name__ == "__main__":
    restore()
