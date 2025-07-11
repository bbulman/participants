# Product Requirements Document (PRD) v7.0
## Workshop.io Participant Web Application

**Version:** 7.0  
**Date:** January 7, 2025  
**Product Owner:** Bill Bulman  
**Status:** Active Development  
**Platform:** Web (Mobile-First Responsive)

---

## Executive Summary

Workshop.io Participant is a mobile-first Progressive Web Application (PWA) designed for workshop attendees to engage in real-time collaborative activities. The app enables participants to capture and share insights through photos, communicate via chat, access workshop materials, and stay synchronized with workshop timing - all orchestrated by facilitators using the companion web interface.

This PRD focuses exclusively on the participant web app, which is part 1 of 2 in the Workshop.io platform ecosystem.

## Product Vision

**Mission:** Empower workshop participants with an intuitive mobile web experience that enhances engagement and captures collective insights through synchronized activities and visual documentation.

**Vision:** Become the industry-standard web platform for workshop participation, enabling seamless collaboration between facilitators and participants worldwide through any browser.

## Target Users

### Primary User Persona: Mike - Workshop Participant
- **Demographics:** 25-45 years old, tech-savvy professional
- **Behavior:** Attends 1-3 workshops monthly
- **Goals:** 
  - Quick and frictionless workshop joining via browser
  - Easy capture and sharing of insights
  - Stay synchronized with workshop activities
- **Pain Points:**
  - Juggling multiple tools during workshops
  - Missing important timing cues
  - Difficulty accessing workshop materials on mobile

## Core Features & Requirements

### 1. Workshop Dashboard (Home View)

**Purpose:** Central hub for workshop status and quick actions

**Requirements:**
- Workshop title and facilitator name display
- Real-time workshop status indicator (Active/Paused/Ended)
- Participant count with live updates
- Workshop duration display (format: "1 Day" or "2 Hours")
- Quick action grid with 6 primary functions:
  - Chat - Opens workshop chat
  - Add Photo - Camera/photo upload flow  
  - Add File - File upload flow (future capability)
  - Ask Question - Quick question submission
  - Get Help - Support/FAQ access

**UI Specifications:**
- Status pill with green "Active" indicator
- Card-based layout using CSS Grid/Flexbox
- 3x2 grid for quick actions with SVG icons
- Pull-to-refresh via JavaScript touch events

### 2. Timer Synchronization

**Purpose:** Keep participants aligned with workshop phases

**Requirements:**
- **Workshop Timer:** Overall session duration (HH:MM:SS format)
- **Exercise Timer:** Current activity countdown (MM:SS format)  
- **Break Timer:** Break period countdown (when applicable)
- Visual timer states:
  - Running (green text/indicator)
  - Paused (yellow)
  - Ended (red)
- Circular progress indicator using CSS animations
- Tab-based timer type selection
- Audio alerts via Web Audio API

**Technical Requirements:**
- Real-time sync via Server-Sent Events (SSE) (<100ms latency)
- JavaScript timer continuation in background tabs
- Local timer persistence in localStorage
- Web Push notifications for timer events

### 3. Collaborative Photo Gallery (Artifacts View)

**Purpose:** Capture and share visual insights from workshop activities

**Requirements:**
- Responsive grid layout (3 columns desktop, 2 mobile) with rounded corners
- Photo count indicator in view header
- Floating action button (+) for adding photos
- Photo capture options:
  - Camera (via MediaDevices API)
  - File upload (standard file input)
- Photo metadata:
  - Title (optional)
  - Description (optional)
  - Timestamp
  - Participant name
- Lightbox viewer with pinch-to-zoom (touch events)
- Download individual photos
- Real-time photo appearance (<3 seconds after upload)

**Technical Specifications:**
- Client-side image compression using Canvas API
- Image format conversion for compatibility
- Lazy loading for performance
- AJAX upload with progress indication

### 4. Workshop Files Access

**Purpose:** Access workshop materials and resources

**Requirements:**
- List view of available PDF documents
- File metadata display:
  - File name
  - File type badge (PDF)
  - File size
- In-browser PDF viewer using PDF.js
- Download capability
- Offline access via Service Worker caching

**UI Specifications:**
- Material Design-inspired list layout
- CSS hover effects for desktop
- Touch-optimized tap targets (44px minimum)

### 5. Real-time Chat

**Purpose:** Facilitate communication between participants and facilitator

**Requirements:**
- Message bubbles with sender identification
- Participant selector dropdown (default: All participants)
- Message types:
  - Text messages
  - System messages (join/leave notifications)
  - Facilitator announcements (highlighted)
- Message status indicators (sent/delivered)
- Auto-expanding textarea with character counter
- Auto-scroll to latest message
- Message timestamp on hover/tap
- Character limit: 500 characters

**Technical Specifications:**
- Server-Sent Events (SSE) for real-time updates
- AJAX message sending with queue
- XSS prevention via input sanitization
- Message history stored in MySQL

### 6. Participant Management

**Purpose:** View and interact with workshop attendees

**Requirements:**
- Responsive grid layout of participant cards
- Participant information:
  - Avatar with initials (CSS generated)
  - Full name
  - Online/offline status indicator
- Total participant count in header
- Search/filter functionality via JavaScript
- Click to view participant profile (future)

### 7. Workshop Joining Flow

**Purpose:** Simple onboarding into workshop sessions

**Requirements:**
- Join methods:
  - 6-digit PIN code entry
  - QR code scanning (via getUserMedia)
  - Workshop link (URL parameter)
- Name/email capture form
- Workshop preview before joining
- Error handling for invalid/expired codes
- Cookie-based recent workshops

## Technical Architecture

### Frontend Stack
- **Languages:** HTML5, CSS3, JavaScript (ES6+)
- **CSS Framework:** Custom CSS with CSS Grid/Flexbox
- **JavaScript:** Vanilla JS (no framework for performance)
- **Build Process:** None (direct serving for simplicity)
- **Progressive Web App:** Service Worker, Web App Manifest

### Backend Stack
- **Language:** PHP 8.0+
- **Database:** MySQL 5.7+
- **Web Server:** Apache/Nginx with .htaccess
- **Session Management:** PHP Sessions + JWT tokens
- **Real-time:** Server-Sent Events (SSE)

### Key Web APIs
- **Camera/Media:** MediaDevices API, getUserMedia
- **File Handling:** File API, FileReader
- **Storage:** localStorage, IndexedDB
- **Notifications:** Push API, Notifications API
- **Offline:** Service Worker, Cache API

### Database Schema
```sql
-- Core tables
workshops (id, title, facilitator_id, status, pin_code, created_at)
participants (id, workshop_id, name, email, joined_at)
photos (id, workshop_id, participant_id, filename, title, description, uploaded_at)
messages (id, workshop_id, participant_id, content, sent_at)
files (id, workshop_id, filename, size, uploaded_at)
timers (id, workshop_id, type, duration, status, updated_at)
```

### API Endpoints
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

## Design Specifications

### Responsive Design
- Mobile-first approach
- Breakpoints:
  - Mobile: < 768px
  - Tablet: 768px - 1024px
  - Desktop: > 1024px
- Touch-optimized interfaces
- CSS Grid for layouts

### Navigation Structure
- Bottom navigation bar (mobile) / Side navigation (desktop):
  1. Home (house icon)
  2. Participants (people icon)
  3. Chat (chat bubble icon)
  4. Artifacts (grid icon)
  5. Timers (clock icon)
- Top header with workshop title and menu
- Modal overlays for add flows

### Visual Design
- CSS Custom Properties for theming:
  ```css
  :root {
    --primary: #FFA500;
    --success: #34C759;
    --danger: #FF3B30;
    --text: #333333;
    --background: #FFFFFF;
  }
  ```
- System font stack for performance
- Border radius: 12px for cards, 8px for buttons
- Consistent padding: 16px margins

## Performance Requirements

### Page Performance
- First Contentful Paint: <1.5s
- Time to Interactive: <3s
- Lighthouse Score: >90
- Page size: <500KB (excluding images)
- Critical CSS inlined

### Optimization Strategies
- Lazy loading for images
- Minified CSS/JS in production
- Gzip compression
- Browser caching headers
- CDN for static assets

### Reliability
- PHP error handling with try-catch
- MySQL connection pooling
- Graceful degradation
- Progressive enhancement

## Security & Privacy

### Security Measures
- Prepared statements for SQL injection prevention
- XSS protection via htmlspecialchars()
- CSRF tokens for forms
- HTTPS enforcement
- Password hashing with bcrypt
- Session security headers

### Privacy Compliance
- GDPR compliant data handling
- Cookie consent banner
- Data deletion API endpoints
- Minimal data collection
- No third-party tracking

## Browser Support

### Minimum Requirements
- Chrome 90+
- Safari 14+
- Firefox 88+
- Edge 90+
- Mobile Safari (iOS 14+)
- Chrome Mobile (Android 8+)

## Success Metrics

### Key Performance Indicators
1. **Adoption Metrics**
   - Unique visitors
   - Workshop join conversion rate
   - PWA installation rate
   - Return visitor rate

2. **Engagement Metrics**
   - Photos shared per session
   - Chat messages per participant
   - Feature usage analytics
   - Session duration

3. **Quality Metrics**
   - Page load time
   - JavaScript error rate
   - User satisfaction surveys

## Release Strategy

### MVP Scope (v1.0)
- Workshop joining (PIN only)
- Timer display
- Photo upload and viewing
- Basic chat
- Participant list

### v1.1 Enhancements
- QR code joining
- File access with PDF viewer
- Typing indicators
- Offline support

### v1.2 Advanced Features
- Break timer support
- Photo annotations
- Private messaging
- Workshop history

## Testing Requirements

### Testing Approach
- PHP Unit tests for backend
- JavaScript unit tests (Jest)
- Cross-browser testing
- Mobile device testing
- Performance testing

### Test Matrix
- Browsers: Chrome, Safari, Firefox, Edge
- Devices: iPhone, Android, iPad, Desktop
- Network: 3G, 4G, WiFi
- Screen sizes: 320px to 2560px

## Deployment

### Hosting Requirements
- Shared hosting compatible
- PHP 8.0+
- MySQL 5.7+
- 1GB+ storage for photos
- SSL certificate

### Deployment Process
- FTP/SFTP upload
- Database migration scripts
- .htaccess configuration
- Environment variables in .env

---

**Document History**
- v7.0 (2025-01): Web application version (PHP/MySQL/CSS)
- v6.0 (2025-01): iOS native app version