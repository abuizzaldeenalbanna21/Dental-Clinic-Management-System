# Dental Clinic Management System

A bilingual (English and Arabic) dental clinic management system prototype with separate interfaces for clinic staff and patients.

> **Prototype notice:** This project is a demonstration, not production-ready clinic software. It stores example data in JSON files and does not provide production-grade authentication, authorization, privacy, or data protection. Do not use it with real patient information or deploy it to a public server.

## Features

- **Admin:** Dashboard, appointments, billing, clinic operations, communications, documents, patient management, reporting, staff, settings, and treatment services.
- **Patient:** Dashboard, appointments, billing and payments, communications, personal profile, documents and records, treatment history, and account settings.
- English and Arabic interface text with right-to-left layout support.
- PHP pages and handlers with JSON files used for demonstration data.

## Requirements

- PHP 8.0 or later with the standard JSON extension.
- An internet connection for pages that load Bootstrap or Font Awesome from a CDN.

No Composer or npm dependencies are currently configured.

## Run locally

From the project root, start PHP's built-in development server:

```powershell
php -S 127.0.0.1:8000
```

Then open:

- Admin dashboard: <http://127.0.0.1:8000/Admin/dashboard/>
- Patient dashboard: <http://127.0.0.1:8000/user/dashboard/>

Stop the server with `Ctrl+C`. The built-in PHP server is for local development only and must not be exposed to the internet.

## Project structure

```text
Admin/       Clinic staff and administration pages and demo data
user/        Patient pages, handlers, and demo data
style.css/   Shared stylesheet
```

Many screens are interface prototypes, and some handlers reference files or services that are not included. Review and complete those flows before relying on them.

## Data and security

The JSON files in this repository are synthetic demonstration fixtures. Keep real patient data, credentials, uploaded documents, logs, and local configuration out of Git. The included `.gitignore` excludes common local data and secret files, but it is not a substitute for checking staged files before publishing.

Before any real-world use, replace JSON-file storage and prototype authentication with an appropriately secured backend, enforce access controls, protect sensitive files from direct web access, and obtain a professional security and privacy review.
