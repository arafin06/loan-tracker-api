# Loan Tracker API

A RESTful API backend for managing commercial loan applications,
built with Laravel 13 and Laravel Sanctum token authentication.

> **Portfolio project** demonstrating clean Laravel architecture:
> Enums, Form Requests, API Resources, Eloquent scopes,
> soft deletes, and audit logging — applied to a real-world
> U.S. commercial lending domain.

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 13 |
| Auth | Laravel Sanctum (token-based) |
| Database | MySQL 8 |
| PHP | 8.3 |
| Server | Apache via Laragon (dev) |

## Features

- **Sanctum Token Auth** — register, login, logout, `/me`
- **Loan CRUD** — full create / read / update / soft-delete
- **Loan Types** — DSCR, Bridge, SBA, CMBS, Conventional
- **Status Workflow** — Draft → Submitted → Under Review → Approved / Rejected → Closed
- **Audit Log** — every status change recorded with who changed it, when, from/to values, and an optional note
- **Filtering** — by status, loan type, date range, and full-text search across applicant name, email, and property address
- **Pagination** — configurable per-page (max 50)
- **Dashboard Stats** — total count, total volume, breakdown by status and loan type
- **API Resources** — all responses shaped via dedicated Resource classes (no raw model leakage)
- **PHP Enums** — `LoanStatus`, `LoanType`, `PropertyType` with labels and color hints

## Domain Fields

Each loan application tracks:

| Field | Description |
|-------|-------------|
| `applicant_name / email / phone` | Borrower contact info |
| `loan_type` | DSCR, Bridge, SBA, CMBS, Conventional |
| `loan_amount` | USD amount |
| `property_address / type` | Subject property |
| `ltv` | Loan-to-Value % |
| `noi` | Annual Net Operating Income |
| `pitia` | Monthly Principal, Interest, Tax, Insurance & Association |
| `loan_status` | Pipeline stage |
| `notes` | Underwriting notes |

## API Endpoints

### Auth

POST /api/auth/register
POST /api/auth/login
POST /api/auth/logout [protected]
GET /api/auth/me [protected]


### Loans

GET /api/loans [protected] — list with filters & pagination
POST /api/loans [protected] — create
GET /api/loans/{id} [protected] — detail with status history
PUT /api/loans/{id} [protected] — update
DELETE /api/loans/{id} [protected] — soft delete
PATCH /api/loans/{id}/status [protected] — change status + log history
GET /api/loans/{id}/history [protected] — status history log
GET /api/loans/stats [protected] — dashboard summary


### Filter Parameters (GET /api/loans)

?status=approved
?loan_type=dscr
?search=john smith
?from=2026-01-01&to=2026-12-31
?per_page=15&page=2


## Project Structure

app/
├── Enums/
│ ├── LoanStatus.php # draft|submitted|under_review|approved|rejected|closed
│ ├── LoanType.php # dscr|bridge|sba|cmbs|conventional
│ └── PropertyType.php # residential|commercial
├── Http/
│ ├── Controllers/
│ │ ├── Auth/AuthController.php
│ │ └── LoanApplicationController.php
│ ├── Requests/ # Form validation per action
│ └── Resources/ # API response shaping
└── Models/
├── LoanApplication.php # SoftDeletes, Enum casts, query scopes
└── LoanStatusHistory.php # Immutable audit log


## Local Setup

### Requirements
- PHP 8.3
- Composer
- MySQL 8
- Laragon (Windows) or Laravel Herd / Valet

### Steps

```bash
git clone https://github.com/arafin06/loan-tracker-api.git
cd loan-tracker-api

composer install

cp .env.example .env
# Edit .env: set DB_DATABASE, DB_USERNAME, DB_PASSWORD, FRONTEND_URL

php artisan key:generate
php artisan migrate
php artisan serve
```

API will be available at `http://localhost:8000/api`

## Frontend

The Vue 3 SPA frontend lives in a separate repository:
[loan-tracker-spa](https://github.com/arafin06/loan-tracker-spa)

## Author

**Niaz Md. Arafin Haque**
Director of Operations — Global Softel Inc.
[LinkedIn](https://www.linkedin.com/in/arafin-haque/) · [GitHub](https://github.com/arafin06)
