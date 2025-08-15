<div align="center">

# Automated SSL Tool for cPanel

**A comprehensive PHP solution for automating the issuance and installation of free SSL certificates, securing your website with HTTPS in minutes.**

</div>

<p align="center">
  <a href="https://github.com/CodeWithKhoa/AUTO_GET_SSL/blob/main/LICENSE">
    <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
  </a>
  <a href="#">
    <img src="https://img.shields.io/badge/PHP-%3E%3D5.6-blue.svg" alt="PHP Version">
  </a>
  <a href="https://github.com/CodeWithKhoa/AUTO_GET_SSL/actions">
    <img src="https://img.shields.io/github/actions/workflow/status/CodeWithKhoa/AUTO_GET_SSL/main.yml?branch=main" alt="Build Status">
  </a>
</p>

---

## 1. 🎯 Introduction & Goal

### Overview
**Auto Get SSL** is a toolset built with PHP, designed to completely eliminate the complexity of installing and maintaining SSL certificates. By leveraging the ACMEv2 protocol and integrating deeply with the cPanel API, this tool provides a "set-it-and-forget-it" solution for website security.

### The Problem to Solve
Manual SSL installation is often time-consuming, prone to errors, and requires technical knowledge. Free certificates also demand periodic renewal (typically every 90 days). Forgetting to renew can lead to website downtime and a loss of user trust.

### Our Solution
This tool automates the entire process: from proving domain ownership and obtaining a certificate from reputable providers (Let's Encrypt, Buypass, ZeroSSL, etc.) to installing it on your hosting and automatically monitoring it for renewal.

---

## 2. ✨ Key Features

-   **🚀 Full End-to-End Automation**: Provides a closed-loop process from start to finish. You only need to interact with two clicks to complete the initial setup.
-   **🧠 Smart & Automatic Renewals**: Automatically monitors and renews certificates 30 days before expiration, ensuring your website never experiences downtime due to SSL errors.
-   **🌐 Multi-CA Support**: Avoids vendor lock-in. You can easily change the Certificate Authority (CA) endpoint in the `getssl.php` file.
-   **🔗 Secure cPanel API Integration**: Uses the official cPanel API for installation, ensuring maximum compatibility and security.
-   **🔌 Simple Deployment**: No Composer or complex frameworks required. All you need is PHP and a few common extensions.
-   **🛡️ Secure by Design**: Comes with a `.htaccess` file to protect sensitive key files and encourages the use of a separate configuration file.

---

## 3. ⚙️ Architecture and Workflow

The tool consists of three main components working in coordination:

1.  **User Interface (`index.php`)**: Provides the web interface for the user to initiate the process.
2.  **SSL Issuance Core (`getssl.php`)**: Responsible for communicating with the ACME CA.
3.  **cPanel Installer Core (`installssl.php`)**: Responsible for communicating with the cPanel API.

### Detailed Workflow

#### Phase 1: Certificate Issuance and Validation
*Flow: `User -> index.php -> (AJAX Request) -> getssl.php -> ACME Communication -> CA -> (Saves .pem files)`*

1.  The user clicks the **"Get SSL"** button in the UI.
2.  `getssl.php` is triggered. The script checks if the current certificate needs renewal.
3.  If needed, the script connects to the ACME CA and requests a new certificate for the domain.
4.  The CA requests domain ownership verification via the `http-01 challenge`.
5.  `getssl.php` automatically creates a temporary file containing a special token at the `/.well-known/acme-challenge/` path.
6.  The CA accesses that path to verify it. If successful, the CA issues the certificate.
7.  The script receives and saves the certificate chain (`fullchain.pem`) and private key (`private_key.pem`) in the current directory.

#### Phase 2: Installation and Activation
*Flow: `User -> index.php -> (AJAX Request) -> installssl.php -> cPanel API Communication -> Server`*

1.  The user clicks the **"Install SSL"** button after receiving the success message.
2.  `installssl.php` is triggered. The script reads the contents of the saved `.pem` files.
3.  It uses the login credentials from `config.php` to create an API request to cPanel's `SSL::install_ssl` endpoint.
4.  The entire certificate chain and private key are sent to cPanel.
5.  cPanel processes the request, installs the certificate into the corresponding virtual host, and activates HTTPS for the domain.
6.  The response from the cPanel API is returned and displayed in the UI.

---

## 4. 🚀 Installation & Configuration Guide

### Prerequisites
Before you begin, ensure you have:
-   PHP version 5.6 or higher.
-   PHP extensions: `openssl` and `curl` enabled.
-   cPanel account credentials: Username, Password, and the server's IP address.
-   Write permissions for the PHP script in the deployment directory.

### Installation Steps
1.  **Clone the repository to your hosting:**
    ```bash
    git clone [https://github.com/CodeWithKhoa/AUTO_GET_SSL.git](https://github.com/CodeWithKhoa/AUTO_GET_SSL.git)
    ```

2.  **Navigate into the project directory:**
    ```bash
    cd AUTO_GET_SSL
    ```

3.  **Secure Configuration (Most Important Step):**
    -   Create a copy of the example configuration file and name it `config.php`. This command does it for you:
        ```bash
        cp config.example.php config.php
        ```
    -   Open `config.php` and fill in your exact cPanel login credentials:
        ```php
        <?php
        // cPanel Login Credentials
        define('CPANEL_USER', 'your_cpanel_username');
        define('CPANEL_PASSWORD', 'your_cpanel_password');
        define('CPANEL_HOST', 'your_server_ip');
        ?>
        ```
    -   The `config.php` file is already listed in `.gitignore` to ensure it is never committed to your repository.

---

## 5. 📖 Usage Guide



1.  Open your web browser and navigate to the `index.php` file on your domain.
2.  **Step 1: Get SSL**: Click the **"Get SSL"** button. This process may take 10-30 seconds. The interface will display a message like "Successfully retrieved SSL certificate..." upon completion.
3.  **Step 2: Install SSL**: Immediately after the success message appears, click the **"Install SSL"** button. The interface will show a direct response from the cPanel API. A successful response usually contains details about the installed certificate.
4.  **Verify**: Visit your website with `https://` to confirm that the certificate is active.

---

## 6. ⚠️ Security Warning & Best Practices

> **Your security is the top priority.** This tool handles extremely sensitive credentials. Please adhere strictly to the following principles:
> -   **Never** commit the `config.php` file or any `.pem` files to a public repository.
> -   **Restrict Access**: Ensure the directory containing this tool is not publicly browsable. Consider placing it under password protection (HTTP Basic Auth).
> -   **Check File Permissions**: Set permissions for PHP files to `644` and directories to `755` to mitigate security risks.
> -   **Delete After Use**: If you only need to perform a one-time installation, consider deleting this toolset from your server after a successful SSL installation to reduce the attack surface.

---

## 7. 🤝 Contributing

All contributions to improve this project are welcome. If you have an idea, a feature request, or have found a bug, please create an **Issue** so we can discuss it.

---

## 8. ✍️ Author & Contact

This project was created and is maintained by **Tran Dang Khoa**.

-   **GitHub:** [@CodeWithKhoa](https://github.com/codewithkhoa)
-   **YouTube:** [@codewithkhoa](https://youtube.com/@codewithkhoa)
-   **Email:** [trandangkhoa31122006@gmail.com](mailto:trandangkhoa31122006@gmail.com)

---

## 9. 📜 License

This project is licensed under the **MIT License**. See the `LICENSE` file for details.
