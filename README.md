# 🛡️ Mini-SOAR: Automated Phishing Responder

## 📖 Overview
This project is a lightweight, proof-of-concept **Security Orchestration, Automation, and Response (SOAR)** platform. It was built to demonstrate how modern Security Operations Centers (SOC) automate threat intelligence and incident response to combat "Alert Fatigue" and close the "Speed Gap."

The system simulates receiving a suspicious URL alert, automatically orchestrates a threat-intelligence scan via the VirusTotal API, and executes a response playbook to instantly block critical threats.

## 🚀 Features
* **Real-Time Orchestration:** Uses JavaScript `fetch()` to asynchronously communicate with a PHP backend without reloading the dashboard.
* **Automated Threat Intelligence:** Automatically queries the VirusTotal API to determine if a domain is malicious.
* **Playbook Execution:** Uses conditional logic to assign actionable responses (e.g., `BLOCK_IP_AND_ISOLATE_HOST` vs. `IGNORE_FALSE_ALARM`).
* **Modern UI:** A clean, dark-mode SOC dashboard built entirely with Tailwind CSS.

## 🛠️ Tech Stack
* **Frontend:** HTML5, Tailwind CSS, JavaScript (Vanilla)
* **Backend:** PHP
* **API/Integrations:** VirusTotal API (v3)

## ⚙️ How to Run Locally
1. Clone this repository to your local server environment (e.g., `htdocs` in XAMPP).
2. Create a file named `config.php` in the root directory.
3. Add your free VirusTotal API key to the config file:
   ```php
   <?php
   // config.php
   $apiKey = 'YOUR_VIRUSTOTAL_API_KEY_HERE';
   ?>
