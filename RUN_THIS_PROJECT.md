# WMedi Plus Healthcare Platform - Step-by-Step Run Guide

## How to Run This Project (From Scratch)

1. **Clone the Repository**
   ```
   git clone https://github.com/Nilesh0788/wmedi-plus-healthcare-live.git
   cd wmedi-plus-healthcare-live
   ```

2. **Install Docker & Docker Compose**
   - Download and install Docker Desktop (https://www.docker.com/products/docker-desktop/)
   - Make sure Docker is running

3. **Start the Project**
   ```
   docker-compose up -d
   ```
   - This will start WordPress and MySQL containers

4. **Access WordPress Admin**
   - Open your browser and go to: http://localhost:8080/wp-admin/
   - Complete WordPress setup (set admin user if first time)

5. **Activate the Plugin**
   - Go to Plugins in admin dashboard
   - Activate "WMedi Plus Healthcare Platform"

6. **Check All Pages**
   - Visit: http://localhost:8080/welcome (Landing page)
   - Other important pages:
     - /get-started
     - /choose-doctor
     - /book-appointment
     - /dashboard
     - /doctor-dashboard

7. **If Any Page Shows Not Found**
   - Go to Settings > Permalinks, select "Post name", and Save
   - Make sure the page slug matches (e.g., /doctor-dashboard)
   - If needed, create the page manually with the correct shortcode (see copilot-instructions.md)

8. **For Any Issue**
   - Check logs: `docker-compose logs wordpress --tail=50`
   - See PROJECT_RUN_GUIDE.md and README.md for more help

---

## Notes
- All code, plugin, and theme files are included in this repo (no submodules needed)
- Just clone, run docker-compose, and follow the above steps
- For any bug or issue, check the documentation or raise an issue on GitHub

---

**Project is now production-ready and easy to share/run anywhere!**
