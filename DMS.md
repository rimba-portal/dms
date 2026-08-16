# Rimba DMS
Document Management System (DMS) designed for ISO 9001:2015 compliant control of documented information.
The package focuses on controlled documents, revision management, approvals, distribution, acknowledgements, retention, and audit traceability.

---
# Purpose
The DMS package provides a centralized repository for managing:
- Quality Manuals
- Procedures
- SOPs
- Work Instructions
- Forms
- Policies
- Standards
- External Documents
- Records
The system ensures that only approved and current versions are available to users while maintaining complete historical traceability.

---
# ISO 9001 Alignment
The package supports ISO 9001:2015 Clause 7.5 requirements for documented information.
Including:
- Document creation
- Review
- Approval
- Revision control
- Controlled distribution
- Access control
- Retention management
- Obsolete document control
- Audit trail tracking

---
# Package Dependencies
The DMS package is intended to integrate with existing Rimba packages.
## Core Dependencies
- rimba/versi (Version Control)
- rimba/jejak (Audit Trail)
- rimba/kerja (Review Tasks)
- rimba/boleh (Authorization)
- rimba/orang (People)
- rimba/pihak (Organization)
## Optional Dependencies
- rimba/lms (Training and Competency)
- rimba/jalan (Approval Workflows)

---
# Document Hierarchy
Typical ISO document hierarchy:
```text
Quality Manual
    ↓
Procedure
    ↓
SOP
    ↓
Work Instruction
    ↓
Forms / Records
```
Documents may be linked using parent-child relationships.
Example:
```text
QM-001
 └── PROC-QA-001
      └── SOP-QA-001
           ├── WI-QA-001
           └── WI-QA-002
```

---
# Core Models
## Document
Represents a controlled document.
Example:
```text
SOP-QA-001
Incoming Inspection Procedure
```
Suggested fields:
```text
id
parent_id
doc_number
title
document_type
category_id
status
is_controlled
team_id
owner_id
author_id
current_version_id
effective_date
next_review_date
retention_period_years
security_classification
regulatory_impact
approved_date
obsolete_date
created_at
updated_at
deleted_at
```

---
## Version
Provided by `rimba/versi`.
Every document revision is stored as a Version record.
Example:
```text
SOP-QA-001
1.0.0
1.1.0
2.0.0
```
Document uses:
```php
use HasVersions;
```
Benefits:
- Full revision history
- Semantic versioning
- Effective dates
- Release management
- Obsolete tracking

---
# Document Lifecycle
Recommended lifecycle:
```text
Draft
 ↓
Review
 ↓
Approved
 ↓
Released
 ↓
Obsolete
 ↓
Archived
```
Status definitions:
| Status | Description |
|---|---|
| Draft | Initial document preparation |
| Review | Under review |
| Approved | Approved by authorized personnel |
| Released | Official controlled copy |
| Obsolete | No longer valid |
| Archived | Historical reference only |

---
# Approvals
ISO requires documents to be reviewed and approved before release.
## DocumentApproval
```text
id
document_id
version_id
approver_id
status
approved_at
comments
```
Workflow:
```text
Draft
 ↓
Review
 ↓
Approval
 ↓
Release
```

---
# Electronic Signatures
Provide approval evidence for audits.
## DocumentSignature
```text
id
version_id
signed_by
signed_at
signature_hash
```
Captured information:
- User
- Date and time
- Version
- Approval action
- Verification hash

---
# Controlled Distribution
Track where each document version is distributed.
## DocumentDistribution
```text
id
version_id
team_id
staff_id
distributed_at
```
Example:
```text
WI-QA-001 v3.0
Distributed To:
- QA
- Production
- Warehouse
```

---
# Read and Acknowledge
Allows proof that users have read controlled documents.
## DocumentAcknowledgement
```text
id
version_id
staff_id
read_at
acknowledged_at
```
Example metrics:
```text
95% Acknowledged
5% Pending
```
Useful during audits and compliance reviews.

---
# Training Integration
When a document changes, employees may require retraining.
## DocumentTraining
```text
id
document_id
version_id
course_id
```
Workflow:
```text
Document Released
        ↓
Training Assigned
        ↓
Course Completed
        ↓
Training Recorded
```
Integrated through `rimba/lms`.

---
# Review Management
Documents must be periodically reviewed.
Existing fields:
```text
effective_date
next_review_date
```
Recommended automation:
```text
Review Due
    ↓
Create Review Task
    ↓
Assign Owner
    ↓
Complete Review
    ↓
Update Review Date
```
Integrated through `rimba/kerja`.

---
# Obsolete Control
Documents should never be permanently deleted.
Recommended practice:
```text
Version 2 Released
        ↓
Version 1 Becomes Obsolete
```
Users should see:
```text
Current Released Version
```
Administrators should see:
```text
Complete Version History
```

---
# Audit Trail
All document activities should be logged.
Integrated through `rimba/jejak`.
Tracked events:
```text
Document Created
Version Uploaded
Review Started
Approved
Released
Distributed
Acknowledged
Obsoleted
Archived
```
This provides complete traceability during audits.

---
# Categories
Instead of physical folders, documents should be categorized.
## DocumentCategory
```text
id
parent_id
name
description
```
Example:
```text
Quality
 ├── Manual
 ├── Procedure
 ├── SOP
 └── Work Instruction
Manufacturing
 ├── SOP
 └── WI
Human Resources
 ├── Policies
 └── Forms
```

---
# Security Classification
Supported classifications:
```text
Public
Internal
Restricted
Highly Confidential
```
Authorization managed by:
```text
rimba/boleh
```

---
# Retention Management
Each document may define a retention period.
Example:
```text
Quality Records       7 Years
Training Records      5 Years
Calibration Records  10 Years
```
Suggested fields:
```text
retention_period_years
retention_until
```

---
# Recommended DMS Structure
```text
rimba/dms
Documents
├── Categories
├── Versions
├── Approvals
├── Signatures
├── Distributions
├── Acknowledgements
├── Trainings
├── Reviews
├── Attachments
└── Retentions
Integrations
├── versi
├── jejak
├── kerja
├── boleh
├── orang
├── pihak
├── jalan
└── lms
```

---
# Design Principle
Document is the master record.
Version is the controlled content.
All approvals, reviews, acknowledgements, training records, and audit history are linked to a specific version.
```text
Document
    └── Versions
            ├── Approvals
            ├── Signatures
            ├── Distributions
            ├── Acknowledgements
            ├── Training Records
            └── Audit Logs
```
This provides a complete ISO 9001 compliant document control solution for the Rimba ecosystem.
```text
rimba/dms
│
├── config
│   └── dms.php
│
├── database
│   ├── migrations
│   │   ├── create_documents_table.php
│   │   ├── create_document_categories_table.php
│   │   ├── create_document_approvals_table.php
│   │   ├── create_document_signatures_table.php
│   │   ├── create_document_distributions_table.php
│   │   ├── create_document_acknowledgements_table.php
│   │   ├── create_document_reviews_table.php
│   │   ├── create_document_trainings_table.php
│   │   ├── create_document_attachments_table.php
│   │   └── create_document_retentions_table.php
│   │
│   └── seeders
│       ├── DocumentCategorySeeder.php
│       └── DocumentTypeSeeder.php
│
├── resources
│   ├── views
│   └── dms.md
│
└── src
    │
    ├── DmsServiceProvider.php
    │
    ├── Models
    │   ├── Document.php
    │   ├── DocumentCategory.php
    │   ├── DocumentApproval.php
    │   ├── DocumentSignature.php
    │   ├── DocumentDistribution.php
    │   ├── DocumentAcknowledgement.php
    │   ├── DocumentReview.php
    │   ├── DocumentTraining.php
    │   ├── DocumentAttachment.php
    │   └── DocumentRetention.php
    │
    ├── Enums
    │   ├── DocumentStatus.php
    │   ├── DocumentType.php
    │   ├── SecurityClassification.php
    │   ├── ApprovalStatus.php
    │   └── ReviewStatus.php
    │
    ├── Actions
    │   ├── CreateDocument.php
    │   ├── SubmitDocumentForReview.php
    │   ├── ApproveDocument.php
    │   ├── ReleaseDocument.php
    │   ├── ObsoleteDocument.php
    │   ├── ArchiveDocument.php
    │   ├── AcknowledgeDocument.php
    │   ├── DistributeDocument.php
    │   ├── AttachTrainingCourse.php
    │   └── ScheduleDocumentReview.php
    │
    ├── Services
    │   ├── DocumentNumberService.php
    │   ├── DocumentReviewService.php
    │   ├── DocumentDistributionService.php
    │   ├── DocumentRetentionService.php
    │   └── DocumentComplianceService.php
    │
    ├── Policies
    │   ├── DocumentPolicy.php
    │   ├── DocumentApprovalPolicy.php
    │   └── DocumentReviewPolicy.php
    │
    ├── Observers
    │   ├── DocumentObserver.php
    │   └── DocumentVersionObserver.php
    │
    ├── Jobs
    │   ├── ProcessDocumentReview.php
    │   ├── SendReviewReminder.php
    │   ├── SendAcknowledgementReminder.php
    │   └── DistributeReleasedDocument.php
    │
    ├── Events
    │   ├── DocumentCreated.php
    │   ├── DocumentReviewed.php
    │   ├── DocumentApproved.php
    │   ├── DocumentReleased.php
    │   ├── DocumentObsoleted.php
    │   └── DocumentAcknowledged.php
    │
    ├── Listeners
    │   ├── CreateAuditTrail.php
    │   ├── NotifyReviewers.php
    │   ├── NotifyDocumentOwners.php
    │   ├── GenerateTrainingAssignments.php
    │   └── ArchiveSupersededVersions.php
    │
    ├── Builders
    │   ├── DocumentBuilder.php
    │   └── DocumentCategoryBuilder.php
    │
    ├── Traits
    │   ├── HasDocuments.php
    │   ├── HasDocumentApprovals.php
    │   ├── HasDocumentReviews.php
    │   └── HasAcknowledgements.php
    │
    ├── Http
    │   └── UI
    │       ├── Admin
    │       │   ├── Resources
    │       │   │   ├── Documents
    │       │   │   │   ├── DocumentResource.php
    │       │   │   │   ├── Pages
    │       │   │   │   ├── Schemas
    │       │   │   │   ├── Tables
    │       │   │   │   └── RelationManagers
    │       │   │   │
    │       │   │   └── DocumentCategories
    │       │   │       ├── DocumentCategoryResource.php
    │       │   │       ├── Pages
    │       │   │       ├── Schemas
    │       │   │       └── Tables
    │       │
    │       │   ├── Pages
    │       │   │   ├── DocumentDashboard.php
    │       │   │   ├── MyApprovals.php
    │       │   │   ├── ReviewCalendar.php
    │       │   │   └── ComplianceDashboard.php
    │       │
    │       │   └── Widgets
    │       │       ├── DocumentsByStatusWidget.php
    │       │       ├── DocumentsDueForReviewWidget.php
    │       │       ├── PendingApprovalsWidget.php
    │       │       └── PendingAcknowledgementsWidget.php
    │       │
    │       └── Staff
    │           ├── Resources
    │           │   └── MyDocuments
    │           │       └── MyDocumentResource.php
    │           │
    │           ├── Pages
    │           │   ├── MyControlledDocuments.php
    │           │   ├── MyAcknowledgements.php
    │           │   └── MyTrainingRequirements.php
    │           │
    │           └── Widgets
    │               ├── DocumentsToReadWidget.php
    │               └── PendingAcknowledgementsWidget.php
    │
    └── Console
        └── Commands
            ├── dms:review-due
            ├── dms:retention-check
            ├── dms:distribution-audit
            └── dms:compliance-report
```
