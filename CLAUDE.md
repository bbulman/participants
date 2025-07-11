# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Workshop.io Participant is a mobile-first Progressive Web Application (PWA) for workshop attendees. The application enables real-time collaborative activities including photo sharing, chat, timer synchronization, and file access.

**Current Status:** Planning phase - repository contains PRD only, no implementation yet.

## Technology Stack (Per PRD)

### Frontend
- **Core:** HTML5, CSS3, Vanilla JavaScript (ES6+)
- **Styling:** Custom CSS with CSS Grid/Flexbox (no framework)
- **Build:** No build process - direct serving for simplicity
- **PWA:** Service Worker + Web App Manifest

### Backend
- **Language:** PHP 8.0+
- **Database:** MySQL 5.7+
- **Server:** Apache/Nginx with .htaccess
- **Real-time:** Server-Sent Events (SSE)
- **Session:** PHP Sessions + JWT tokens

### Key Web APIs
- MediaDevices API (camera/media)
- File API, FileReader
- localStorage, IndexedDB
- Push API, Notifications API
- Service Worker, Cache API

## Architecture Guidelines

### Frontend Structure
```
/assets/
  /css/         # Custom CSS files
  /js/          # Vanilla JavaScript modules
/views/         # HTML templates
/service-worker.js
/manifest.json
```

### Backend Structure
```
/api/           # API endpoints
/database/      # Migration scripts
/uploads/       # Photo storage
/includes/      # PHP includes
/.htaccess
```

### Database Schema (From PRD)
- `workshops` - Workshop sessions
- `participants` - Workshop attendees  
- `photos` - Uploaded images
- `messages` - Chat messages
- `files` - Workshop materials
- `timers` - Timer states

## Development Commands

**Note:** No build process required - direct file serving

### Database
```bash
# Setup database (when implemented)
mysql -u username -p database_name < database/schema.sql

# Run migrations
php database/migrate.php
```

### Testing (When Implemented)
```bash
# Backend tests
phpunit tests/

# Frontend tests  
npx jest tests/
```

### Deployment
```bash
# Upload via FTP/SFTP
# Configure .htaccess
# Set environment variables in .env
```

## Core Features (From PRD)

### 1. Workshop Dashboard
- Workshop status display
- Real-time participant count
- Quick action grid (6 functions)
- Pull-to-refresh functionality

### 2. Timer Synchronization
- Workshop timer (HH:MM:SS)
- Exercise timer (MM:SS)
- Break timer support
- Real-time sync via SSE (<100ms latency)

### 3. Photo Gallery (Artifacts)
- Responsive grid (3 col desktop, 2 mobile)
- Camera/upload via MediaDevices API
- Real-time photo updates
- Client-side compression

### 4. Real-time Chat
- Server-Sent Events for updates
- Message bubbles with sender ID
- Character limit: 500 chars
- XSS prevention via sanitization

### 5. File Access
- PDF viewer using PDF.js
- Offline access via Service Worker
- Download capability

### 6. Workshop Joining
- PIN code entry (6-digit)
- QR code scanning
- URL parameter joining

## API Endpoints (From PRD)

```
POST   /api/workshop/join
GET    /api/workshop/{id}/status
POST   /api/photos/upload
GET    /api/photos/list
POST   /api/messages/send
GET    /api/messages/stream (SSE)
GET    /api/participants/list
GET    /api/timers/current
```

## Performance Requirements

- First Contentful Paint: <1.5s
- Time to Interactive: <3s
- Lighthouse Score: >90
- Page size: <500KB (excluding images)
- Critical CSS inlined

## Security Requirements

- Prepared statements for SQL injection prevention
- XSS protection via htmlspecialchars()
- CSRF tokens for forms
- HTTPS enforcement
- bcrypt password hashing

## Browser Support

- Chrome 90+, Safari 14+, Firefox 88+, Edge 90+
- Mobile Safari (iOS 14+)
- Chrome Mobile (Android 8+)

## Design System

### CSS Custom Properties
```css
:root {
  --primary: #FFA500;
  --success: #34C759;
  --danger: #FF3B30;
  --text: #333333;
  --background: #FFFFFF;
}
```

### Layout Standards
- Border radius: 12px for cards, 8px for buttons
- Consistent padding: 16px margins
- Touch targets: 44px minimum
- System font stack for performance

## Development Guidelines

### Mobile-First Approach
- Breakpoints: <768px (mobile), 768-1024px (tablet), >1024px (desktop)
- Touch-optimized interfaces
- CSS Grid for layouts

### Real-time Features
- Use Server-Sent Events for updates
- Implement JavaScript timer continuation
- Store timer state in localStorage
- Handle network interruptions gracefully

### Progressive Web App
- Implement Service Worker for offline capability
- Add Web App Manifest for installation
- Cache critical resources
- Handle offline scenarios

## MVP Scope (v1.0)
- Workshop joining (PIN only)
- Timer display
- Photo upload and viewing
- Basic chat
- Participant list

## Testing Strategy
- PHP Unit tests for backend
- JavaScript unit tests (Jest)
- Cross-browser testing
- Mobile device testing
- Performance testing

## Future Enhancements
- QR code joining (v1.1)
- File access with PDF viewer (v1.1)
- Offline support (v1.1)
- Break timer support (v1.2)
- Photo annotations (v1.2)

## Claude Code Memory
- do not deviated from teh design of a screen to create whatever you want.