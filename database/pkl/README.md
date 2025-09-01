# PKL Database Schema

This directory contains the database schema for the PKL (Praktek Kerja Lapangan) module.

## Files

- `table.sql` - Original database schema
- `updated_table.sql` - Updated database schema with enhancements for 5-stage PKL process
- `migrations/` - Migration scripts for updating the database schema

## Schema Changes

The updated schema includes several enhancements to support the 5-stage PKL process:

1. **Enhanced Status Flow**: Added more granular status values to track progress through all 5 stages
2. **New Document Types**: Added support for all required PKL forms (B-1, B-2, B-3, B-4, B-5, C-1, D-1, D-3, D-4, E-1)
3. **Seminar Management**: Added columns for seminar scheduling directly in the applications table
4. **Workflow Tracking**: Enhanced workflow status values for better process tracking

## Migration

To update an existing database to the new schema:

1. Run the migration script `migrations/001_update_pkl_schema.php`
2. This will update all table structures to match the new schema

## Tables

### pkl_semesters
Stores academic semesters for PKL applications.

### pkl_applications
Main table storing PKL application data with enhanced status tracking.

### pkl_assessments
Stores assessment scores from both field supervisors and academic supervisors.

### pkl_documents
Stores all documents related to PKL applications, including all required forms.

### pkl_logs
Stores daily activity logs (logbook entries) for ongoing PKL activities.

### pkl_places
Stores predefined internship locations.

### pkl_reports
Stores report versions (though this may be redundant with documents table).

### pkl_seminars
Stores seminar scheduling information.

### pkl_workflow
Tracks the approval workflow for PKL applications.