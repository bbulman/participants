# Workshop.io Participant Database Schema Documentation

## Database Overview

**Database Name:** `u773681277_timer`  
**Character Set:** utf8mb4  
**Collation:** utf8mb4_unicode_ci  
**Purpose:** Workshop participant management system for real-time collaborative activities

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'u773681277_timer');
define('DB_USER', 'u773681277_timer');
define('DB_PASS', 'Walter@1975@');
define('DB_CHARSET', 'utf8mb4');



## Architecture Summary

The database supports both legacy workshop functionality and new timer-based session management. It consists of two main architectural patterns:

1. **Legacy Workshop System** - Original workshop management with PIN-based joining
2. **Timer Session System** - Enhanced session management with user assignments and extended features

## Core Tables

### 1. Legacy Workshop System

#### `workshops`
Main workshop container for PIN-based sessions.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique workshop identifier |
| `name` | VARCHAR(255) | NOT NULL | Workshop name/title |
| `description` | TEXT | | Workshop description |
| `facilitator_name` | VARCHAR(255) | | Facilitator full name |
| `facilitator_email` | VARCHAR(255) | | Facilitator email address |
| `pin_code` | VARCHAR(6) | UNIQUE, NOT NULL | 6-digit workshop access code |
| `status` | ENUM | 'upcoming', 'active', 'completed' | Workshop lifecycle status |
| `start_time` | DATETIME | | Scheduled start time |
| `end_time` | DATETIME | | Scheduled end time |
| `duration_minutes` | INT | | Expected duration in minutes |
| `max_participants` | INT | DEFAULT 50 | Maximum allowed participants |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Last modification timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- UNIQUE KEY on `pin_code`

#### `participants`
Workshop attendees in the legacy system.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique participant identifier |
| `name` | VARCHAR(255) | NOT NULL | Participant full name |
| `email` | VARCHAR(255) | UNIQUE, NOT NULL | Participant email address |
| `phone` | VARCHAR(20) | | Phone number |
| `avatar_url` | VARCHAR(500) | | Profile image URL |
| `status` | ENUM | 'active', 'inactive', 'away' | Participant status |
| `last_active` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Last activity timestamp |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Registration timestamp |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Last modification timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- UNIQUE KEY on `email`

#### `workshop_participants`
Junction table linking workshops and participants.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique assignment identifier |
| `workshop_id` | INT | NOT NULL, FOREIGN KEY | References workshops.id |
| `participant_id` | INT | NOT NULL, FOREIGN KEY | References participants.id |
| `status` | ENUM | 'active', 'inactive', 'left' | Participation status |
| `joined_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Join timestamp |
| `left_at` | TIMESTAMP | NULL | Leave timestamp |

**Constraints:**
- FOREIGN KEY `workshop_id` → `workshops(id)` ON DELETE CASCADE
- FOREIGN KEY `participant_id` → `participants(id)` ON DELETE CASCADE
- UNIQUE KEY `unique_workshop_participant` (`workshop_id`, `participant_id`)

### 2. Timer Session System

#### `timer_sessions`
Enhanced session management for timer-based workshops.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique session identifier |
| `session_name` | VARCHAR(255) | NOT NULL | Session name/title |
| `description` | TEXT | | Session description |
| `status` | ENUM | 'active', 'upcoming', 'completed', 'cancelled' | Session lifecycle status |
| `duration_minutes` | INT | DEFAULT 480 | Session duration in minutes |
| `start_time` | DATETIME | | Scheduled start time |
| `end_time` | DATETIME | | Scheduled end time |
| `facilitator_id` | INT | | Primary facilitator user ID |
| `organization_id` | INT | | Organization identifier |
| `max_participants` | INT | DEFAULT 50 | Maximum allowed participants |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Last modification timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- INDEX `idx_status` on `status`
- INDEX `idx_start_time` on `start_time`
- INDEX `idx_facilitator` on `facilitator_id`

#### `timer_users`
User management for timer session system.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique user identifier |
| `name` | VARCHAR(255) | NOT NULL | User full name |
| `email` | VARCHAR(255) | UNIQUE, NOT NULL | User email address |
| `role` | ENUM | 'participant', 'facilitator' | User role type |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Registration timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- UNIQUE KEY on `email`

#### `timer_user_sessions`
Junction table for user-session assignments.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique assignment identifier |
| `session_id` | INT | NOT NULL, FOREIGN KEY | References timer_sessions.id |
| `user_id` | INT | NOT NULL, FOREIGN KEY | References timer_users.id |
| `role` | ENUM | 'participant', 'facilitator', 'observer' | Session role |
| `status` | ENUM | 'active', 'inactive', 'completed' | Assignment status |
| `joined_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Join timestamp |

**Constraints:**
- FOREIGN KEY `session_id` → `timer_sessions(id)` ON DELETE CASCADE
- FOREIGN KEY `user_id` → `timer_users(id)` ON DELETE CASCADE

## Feature Tables

### Communication

#### `messages`
Chat messages for legacy workshop system.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique message identifier |
| `workshop_id` | INT | NOT NULL, FOREIGN KEY | References workshops.id |
| `participant_id` | INT | NOT NULL, FOREIGN KEY | References participants.id |
| `message` | TEXT | NOT NULL | Message content |
| `message_type` | ENUM | 'text', 'system', 'file' | Message type |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Creation timestamp |

**Constraints:**
- FOREIGN KEY `workshop_id` → `workshops(id)` ON DELETE CASCADE
- FOREIGN KEY `participant_id` → `participants(id)` ON DELETE CASCADE

**Indexes:**
- INDEX `idx_workshop_messages` (`workshop_id`, `created_at`)
- INDEX `idx_participant_messages` (`participant_id`, `created_at`)

#### `timer_chat_messages`
Enhanced chat system for timer sessions.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique message identifier |
| `session_id` | INT | NOT NULL, FOREIGN KEY | References timer_sessions.id |
| `user_id` | INT | NOT NULL, FOREIGN KEY | References timer_users.id |
| `message` | TEXT | NOT NULL | Message content |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Creation timestamp |

**Constraints:**
- FOREIGN KEY `session_id` → `timer_sessions(id)` ON DELETE CASCADE
- FOREIGN KEY `user_id` → `timer_users(id)` ON DELETE CASCADE

### File Management

#### `files`
File storage for legacy workshop system.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique file identifier |
| `workshop_id` | INT | NOT NULL, FOREIGN KEY | References workshops.id |
| `filename` | VARCHAR(255) | NOT NULL | System filename |
| `original_filename` | VARCHAR(255) | | Original upload filename |
| `file_path` | VARCHAR(500) | NOT NULL | File storage path |
| `file_size` | INT | | File size in bytes |
| `mime_type` | VARCHAR(100) | | MIME type |
| `title` | VARCHAR(255) | | Display title |
| `description` | TEXT | | File description |
| `file_type` | ENUM | 'pdf', 'ppt', 'doc', 'image', 'other' | File category |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Upload timestamp |

**Constraints:**
- FOREIGN KEY `workshop_id` → `workshops(id)` ON DELETE CASCADE

**Indexes:**
- INDEX `idx_workshop_files` (`workshop_id`, `created_at`)

#### `timer_files`
Enhanced file management for timer sessions.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique file identifier |
| `session_id` | INT | NOT NULL, FOREIGN KEY | References timer_sessions.id |
| `filename` | VARCHAR(255) | NOT NULL | System filename |
| `original_filename` | VARCHAR(255) | NOT NULL | Original upload filename |
| `file_path` | VARCHAR(500) | NOT NULL | File storage path |
| `file_size` | BIGINT | NOT NULL | File size in bytes |
| `mime_type` | VARCHAR(100) | NOT NULL | MIME type |
| `title` | VARCHAR(255) | NOT NULL | Display title |
| `description` | TEXT | | File description |
| `file_type` | ENUM | 'pdf', 'ppt', 'doc', 'xls', 'image', 'video', 'audio', 'other' | File category |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Upload timestamp |

**Constraints:**
- FOREIGN KEY `session_id` → `timer_sessions(id)` ON DELETE CASCADE

### Media

#### `photos`
Photo gallery for workshop artifacts.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique photo identifier |
| `workshop_id` | INT | NOT NULL, FOREIGN KEY | References workshops.id |
| `participant_id` | INT | NOT NULL, FOREIGN KEY | References participants.id |
| `filename` | VARCHAR(255) | NOT NULL | System filename |
| `original_filename` | VARCHAR(255) | | Original upload filename |
| `file_path` | VARCHAR(500) | NOT NULL | Photo storage path |
| `file_size` | INT | | File size in bytes |
| `mime_type` | VARCHAR(100) | | MIME type |
| `title` | VARCHAR(255) | | Photo title |
| `description` | TEXT | | Photo description |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Upload timestamp |

**Constraints:**
- FOREIGN KEY `workshop_id` → `workshops(id)` ON DELETE CASCADE
- FOREIGN KEY `participant_id` → `participants(id)` ON DELETE CASCADE

**Indexes:**
- INDEX `idx_workshop_photos` (`workshop_id`, `created_at`)
- INDEX `idx_participant_photos` (`participant_id`, `created_at`)

#### `timer_photos`
Enhanced photo management for timer sessions.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique photo identifier |
| `session_id` | INT | NOT NULL, FOREIGN KEY | References timer_sessions.id |
| `user_id` | INT | NOT NULL, FOREIGN KEY | References timer_users.id |
| `filename` | VARCHAR(255) | NOT NULL | System filename |
| `original_filename` | VARCHAR(255) | NOT NULL | Original upload filename |
| `file_path` | VARCHAR(500) | NOT NULL | Photo storage path |
| `file_size` | BIGINT | NOT NULL | File size in bytes |
| `mime_type` | VARCHAR(100) | NOT NULL | MIME type |
| `title` | VARCHAR(255) | NOT NULL | Photo title |
| `description` | TEXT | | Photo description |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Upload timestamp |

**Constraints:**
- FOREIGN KEY `session_id` → `timer_sessions(id)` ON DELETE CASCADE
- FOREIGN KEY `user_id` → `timer_users(id)` ON DELETE CASCADE

### Timer Management

#### `timers`
Workshop timer functionality.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique timer identifier |
| `workshop_id` | INT | NOT NULL, FOREIGN KEY | References workshops.id |
| `timer_type` | ENUM | 'workshop', 'exercise', 'break' | Timer purpose |
| `duration_seconds` | INT | NOT NULL | Total duration in seconds |
| `remaining_seconds` | INT | NOT NULL | Remaining time in seconds |
| `status` | ENUM | 'running', 'paused', 'stopped', 'completed' | Timer state |
| `started_at` | TIMESTAMP | NULL | Start timestamp |
| `paused_at` | TIMESTAMP | NULL | Pause timestamp |
| `completed_at` | TIMESTAMP | NULL | Completion timestamp |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Last modification timestamp |

**Constraints:**
- FOREIGN KEY `workshop_id` → `workshops(id)` ON DELETE CASCADE

**Indexes:**
- INDEX `idx_workshop_timers` (`workshop_id`, `created_at`)

## Enhanced Tables (Created by Scripts)

The following tables have been enhanced with additional fields through database migration scripts:

### `timer_chat_messages_enhanced`
Extended chat functionality with threading, reactions, and moderation.

**Key Features:**
- Message threading and replies
- Reaction tracking and emoji responses
- Read receipts and delivery status
- Message editing and version history
- Mentions and notifications
- File attachments support
- Content moderation and flagging
- Search indexing and analytics

### `timer_files_enhanced`
Advanced file management with versioning and access control.

**Key Features:**
- Version control and file history
- Access permissions and sharing
- Download tracking and analytics
- File annotations and comments
- Thumbnail generation
- Content extraction for search
- Expiration and scheduling

### Supporting Enhancement Tables

- `timer_message_reactions_enhanced` - Message reactions
- `timer_message_read_receipts_enhanced` - Read tracking
- `timer_message_attachments` - File attachments
- `timer_message_mentions` - User mentions
- `timer_message_flags` - Content moderation
- `timer_message_search_index` - Search optimization
- `timer_file_access_logs` - File access tracking
- `timer_file_permissions` - Access control
- `timer_file_comments` - File annotations

## Database Relationships

### Primary Relationships

```
workshops (1) ↔ (n) workshop_participants ↔ (1) participants
workshops (1) ↔ (n) messages
workshops (1) ↔ (n) files  
workshops (1) ↔ (n) photos
workshops (1) ↔ (n) timers

timer_sessions (1) ↔ (n) timer_user_sessions ↔ (1) timer_users
timer_sessions (1) ↔ (n) timer_chat_messages
timer_sessions (1) ↔ (n) timer_files
timer_sessions (1) ↔ (n) timer_photos
```

### Foreign Key Constraints

- **CASCADE DELETE**: All dependent records deleted when parent is removed
- **SET NULL**: Foreign key set to NULL when referenced record is deleted
- **RESTRICT**: Prevents deletion if dependent records exist

## Data Types and Constraints

### ENUM Values

| Table | Column | Values |
|-------|--------|--------|
| `workshops` | `status` | 'upcoming', 'active', 'completed' |
| `participants` | `status` | 'active', 'inactive', 'away' |
| `workshop_participants` | `status` | 'active', 'inactive', 'left' |
| `timer_sessions` | `status` | 'active', 'upcoming', 'completed', 'cancelled' |
| `timer_users` | `role` | 'participant', 'facilitator' |
| `timer_user_sessions` | `role` | 'participant', 'facilitator', 'observer' |
| `timer_user_sessions` | `status` | 'active', 'inactive', 'completed' |
| `messages` | `message_type` | 'text', 'system', 'file' |
| `files` | `file_type` | 'pdf', 'ppt', 'doc', 'image', 'other' |
| `timer_files` | `file_type` | 'pdf', 'ppt', 'doc', 'xls', 'image', 'video', 'audio', 'other' |
| `timers` | `timer_type` | 'workshop', 'exercise', 'break' |
| `timers` | `status` | 'running', 'paused', 'stopped', 'completed' |

### Character Sets
- **Database**: utf8mb4 (full Unicode support)
- **Collation**: utf8mb4_unicode_ci (case-insensitive Unicode)

### Timestamp Behavior
- **created_at**: Automatically set on INSERT
- **updated_at**: Automatically updated on modification
- **Timezone**: Server default (recommended: UTC)

## Performance Considerations

### Indexing Strategy

1. **Primary Keys**: All tables use AUTO_INCREMENT INT primary keys
2. **Foreign Keys**: Indexed automatically for join performance
3. **Temporal Queries**: Composite indexes on (foreign_key, created_at)
4. **Unique Constraints**: Email addresses and pin codes
5. **Status Filtering**: Indexes on status columns for lifecycle queries

### Query Optimization

- Use composite indexes for common filter combinations
- Leverage foreign key indexes for JOIN operations
- Consider partitioning for large message/photo tables
- Implement proper pagination for list views

## Security Features

### Data Protection
- **Foreign Key Constraints**: Prevent orphaned records
- **Unique Constraints**: Prevent duplicate critical data
- **NOT NULL Constraints**: Ensure required data integrity
- **ENUM Constraints**: Validate status and type values

### Access Control
- **Role-based Access**: Facilitator vs Participant roles
- **Session Isolation**: Data scoped to specific workshops/sessions
- **Soft Deletes**: Enhanced tables support soft deletion patterns

## Migration and Deployment

### Database Creation
```sql
CREATE DATABASE u773681277_timer CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Table Creation Order
1. Core tables (workshops, participants, timer_sessions, timer_users)
2. Junction tables (workshop_participants, timer_user_sessions)
3. Feature tables (messages, files, photos, timers)
4. Enhanced tables (via migration scripts)

### Sample Data
The schema includes comprehensive sample data for development and testing:
- 3 sample workshops with different statuses
- 8 sample participants
- Workshop-participant assignments
- Sample messages and files
- Active timer demonstration

## Monitoring and Maintenance

### Regular Maintenance Tasks
- Monitor table sizes and growth patterns
- Analyze query performance and optimize indexes
- Archive old workshop/session data
- Clean up orphaned file references
- Update table statistics for query optimizer

### Backup Strategy
- Full database backups before major deployments
- Regular incremental backups for data protection
- Test restoration procedures
- Document recovery procedures

## Version History

- **v1.0**: Initial workshop system with basic functionality
- **v1.1**: Added timer sessions and enhanced user management
- **v1.2**: Enhanced chat system with reactions and threading
- **v1.3**: Advanced file management with versioning
- **Current**: Comprehensive system supporting both legacy and modern features

---

*Generated automatically from database schema analysis*  
*Last Updated: $(date)*