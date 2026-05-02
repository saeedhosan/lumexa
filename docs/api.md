# API Documentation

## Overview

Lumexa provides a RESTful API for lead management integration with external systems.

**Base URL:** `https://your-domain.com/api/v1`

---

## Authentication

All API endpoints require authentication using Laravel Sanctum tokens. Include the token in the `Authorization` header:

```bash
# Create token (via login endpoint)
curl -X POST https://your-domain.com/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email": "user@example.com", "password": "password"}'

# Use token in API requests
curl -X GET https://your-domain.com/api/v1/leads \
  -H "Authorization: Bearer YOUR_SANCTUM_TOKEN" \
  -H "Accept: application/json"
```

---

## Endpoints

### Leads

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/leads` | List all leads |
| GET | `/api/v1/leads/{id}` | Get single lead |

---

## Examples

### List Leads

```bash
curl -X GET "http://localhost:8000/api/v1/leads" \
  -H "Accept: application/json"
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "uuid": "abc-123",
      "title": "Lead Title",
      "status": "pending",
      "status_label": "Pending",
      "company": {
        "id": 1,
        "name": "Acme Corp"
      },
      "created_at": "2026-04-25T10:00:00+00:00",
      "updated_at": "2026-04-25T10:00:00+00:00"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 15,
    "total": 1,
    "first_page_url": "http://localhost:8000/api/v1/leads?page=1",
    "last_page_url": "http://localhost:8000/api/v1/leads?page=1",
    "next_page_url": null,
    "prev_page_url": null
  }
}
```

### Get Lead

```bash
curl -X GET "http://localhost:8000/api/v1/leads/1" \
  -H "Accept: application/json"
```

---

## Query Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `page` | integer | 1 | Page number |
| `per_page` | integer | 15 | Items per page (max: 100) |

---

## Rate Limiting

API requests are limited to 60 requests per minute per IP address.

---

## Error Responses

| Status | Description |
|--------|-------------|
| 200 | Success |
| 401 | Unauthorized (missing/invalid token) |
| 404 | Not found |
| 429 | Too many requests |
| 500 | Server error |