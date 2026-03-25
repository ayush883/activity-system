# Mini Activity Tracking & Audit System (Core PHP)

## Overview

This project is a **Mini Activity Tracking & Audit System** built using **Core PHP (no frameworks)**.  
It tracks user activities, provides APIs for log analysis, detects anomalies, and offers basic insights.

---

## Tech Stack

- **Backend:** Core PHP (OOP)  
- **Database:** MySQL  
- **Frontend:** HTML, JavaScript  
- **Caching:** File-based  
- **Architecture:** Modular (API + Classes + Config)

---

## Project Structure

```
/activity_system
│── /api           → API endpoints
│── /classes       → Business logic (Logger, RateLimiter)
│── /config        → Database connection
│── /cache         → File-based cache
│── /public        → Frontend UI
│── /scripts       → Dummy data generator
│── schema.sql     → Database schema
```

---

## Setup Instructions

### Clone or Download Project
Place the project folder inside your local web server’s root directory:

```bash
# Example for XAMPP
C:\xampp\htdocs\activity_system
```

---

### Create MySQL Database
Open **phpMyAdmin** or MySQL CLI and create a new database:

```sql
CREATE DATABASE activity_db;
```

---

### Import Database Schema
Import the provided `schema.sql` file to create the required tables:

```bash
# Using phpMyAdmin: select database → Import → choose file → Go
# OR via CLI:
mysql -u root -p activity_db < schema.sql
```

---

### Configure Database Connection
Open `/config/database.php` and update credentials:


---

### Run the Project
Start Apache & MySQL in XAMPP/WAMP/LAMP and open the dashboard:

```
http://localhost/activity_system/public/index.html
```

---

### Generate Dummy Data (Optional)
To test logs and anomalies, run the dummy data generator:

```bash
php scripts/generate_logs.php
```

This will insert:
- Normal activity logs  
- High activity anomalies  
- Multi-IP anomalies  

---

### API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/log.php` | POST | Log user activity |
| `/api/logs.php` | GET | Fetch logs (filters + pagination) |
| `/api/top-users.php` | GET | Top 5 active users (cached 2 min) |
| `/api/anomalies.php` | GET | Detect anomalies (high activity / multi-IP) |

---

### Optional: Clear Cache
If you want to refresh `/top-users.php` cache:

```bash
rm /cache/top_users.json
```

---

## Features

- **Activity Logging:** Stores user activity with metadata, IP, and timestamp  
- **Dynamic Filtering:** Supports multiple filters using prepared statements (SQL injection safe)  
- **Pagination & Sorting:** Server-side pagination and sorting by timestamp  
- **Rate Limiting:** 100 requests per IP per hour  
- **File-Based Caching:** Top users API cached for 2 minutes  
- **Anomaly Detection:** Detects high activity (>10 actions/min) and multi-IP access  
- **Frontend Dashboard:** View logs, filter results, and check anomalies  

---

## Security Practices

- Prepared statements for SQL injection prevention  
- Input validation  
- Rate limiting  
- HTTP method restrictions  

---

## Performance Optimizations

- Indexed database columns  
- Server-side pagination for large datasets (100k+ logs)  
- File-based caching  

---

## Limitations

- Anomaly detection uses fixed time windows  
- No authentication (can be added with JWT)  
- Basic UI (HTML + JS, no framework)  

---

## Future Improvements

- JWT Authentication  
- Real-time anomaly alerts  
- Dashboard charts  
- Redis caching  
- Advanced filtering  

---

## Author

**Ayush Srivastava**

---

## Conclusion

This project demonstrates:

- Backend development using Core PHP  
- API design and security best practices  
- Performance optimization techniques  
- Real-world system design concepts
```



# activity-system
