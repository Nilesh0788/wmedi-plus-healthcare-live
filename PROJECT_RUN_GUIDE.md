# WMedi Plus Healthcare Project - Run & Setup Guide

## Step-by-Step Project Run Instructions

1. **Clone or Download the Project**
   - `git clone <your-repo-url>`
   - Or download and extract the zip.

2. **Install Docker & Docker Compose**
   - Make sure Docker Desktop is running.

3. **Start the Project**
   - Open terminal in the project root.
   - Run: `docker-compose up -d`

4. **Access WordPress Admin**
   - Go to: http://localhost:8080/wp-admin/
   - Complete WordPress setup (set admin user if first time).

5. **Activate the Plugin**
   - Go to Plugins in admin dashboard.
   - Activate "WMedi Plus Healthcare Platform".

6. **Check the Home Page**
   - Visit: http://localhost:8080/welcome
   - The healthcare platform should load.

## Notes
- If /welcome page not found, deactivate and reactivate the plugin.
- If containers don't start, check Docker Desktop is running.
- For any issues, check logs: `docker-compose logs wordpress --tail=50`

---

## Git Commit Best Practices
- Commit after every major change (Docker config, plugin updates, bug fixes, documentation).
- Use clear commit messages, e.g.:
  - `git commit -m "Added Docker volume for plugins"`
  - `git commit -m "Fixed plugin auto-page creation bug"`
  - `git commit -m "Updated README with run steps"`

---

## Project Structure
- `docker-compose.yml` - Docker config
- `WMedi-plus-Website/wp-content/plugins/wmedi-plus-healthcare` - Main plugin code
- `.github/copilot-instructions.md` - AI coding guidelines
- `README.md` - Project overview

---

For any setup or run issues, follow the above steps or check the documentation files in the repo.
