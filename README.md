# rimba/document

Controlled document and revision management for the Rimba ecosystem.

## MVP models

| Model | Workflow-enabled | Purpose |
|---|---:|---|
| `Document` | Yes | Stable identity, classification, ownership, status, and current revision pointer. |
| `DocumentType` | No | Hierarchical reference data such as Policy, Procedure, Work Instruction, Form, Manual, Drawing, and Learning Material. |
| `DocumentRevision` | Yes | Controlled file revision, approval/effective dates, checksum, change summary, and revision-specific MCQ bank. |

## Boundaries

- `rimba/document` owns controlled documents, revisions, files, and the revision question bank.
- `rimba/sifat` stores dynamic document metadata through `HasThingAttributes` on `Document`.
- `rimba/lms` decides question count, passing mark, attempts, random selection, and stores attempt snapshots/results.
- `packstub/filament-flow` provides document and revision automation.

## Question bank JSON

```json
[
  {
    "id": "q1",
    "question": "What must be done before maintenance?",
    "answers": {
      "a": "Stop production",
      "b": "Isolate the equipment",
      "c": "Clean the machine"
    },
    "correct": ["b"],
    "explanation": "Energy isolation is required before maintenance."
  },
  {
    "id": "q2",
    "question": "Which energy sources must be isolated?",
    "answers": {
      "a": "Electrical",
      "b": "Pneumatic",
      "c": "Hydraulic",
      "d": "None"
    },
    "correct": ["a", "b", "c"]
  }
]
```

Rendering rule:

```php
count($question['correct']) === 1
    ? Radio::make("answers.{$question['id']}")
    : CheckboxList::make("answers.{$question['id']}");
```

## Workflow boundary

`Document` and `DocumentRevision` use `Packstub\Flow\Concerns\HasWorkflows`.

Recommended revision path:

```text
draft -> in_review -> approved -> effective -> superseded
                         \-> rejected
```

Use Filament Flow for review, approval, reminders, expiry, notifications, and controlled publication. `MakeRevisionEffective` keeps one current effective revision and supersedes the previous effective revision in one transaction.

## Installation

```bash
composer require rimba/document
php artisan migrate
php artisan packstub-flow:install
```

Register Flow in the required Filament panel:

```php
use Packstub\Flow\FlowPlugin;

$panel->plugin(
    FlowPlugin::make()->navigationGroup('Automation'),
);
```

## Create a revision

```php
use Rimba\Document\Actions\CreateDocumentRevision;

$revision = app(CreateDocumentRevision::class)->handle(
    document: $document,
    attributes: [
        'revision' => 'A',
        'change_summary' => 'Initial controlled issue.',
        'question_bank' => $questions,
        'created_by' => auth()->id(),
    ],
    file: $uploadedFile,
);
```

## Make an approved revision effective

```php
use Rimba\Document\Actions\MakeRevisionEffective;

app(MakeRevisionEffective::class)->handle($revision);
```

## Storage

The default disk is `local`, so controlled files remain private. Configure with:

```dotenv
DOCUMENT_DISK=local
DOCUMENT_DIRECTORY=documents
```
