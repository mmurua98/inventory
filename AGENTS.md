# AGENTS.md — Inventory & Purchase Order System (v2)

## Project overview

This is a full rebuild of a legacy Laravel 8 internal inventory control
and purchase order management system.

The legacy system had:
- critical bugs
- schema inconsistencies
- business logic inside Blade/jQuery
- unreliable inventory calculations

This version is rebuilt from scratch with a clean architecture.

---

## Read first (mandatory)

Before starting any task, always read:

- `BLUEPRINT.md` — system design, normalized schema, domain model, PO logic
- `REBUILD_PLAN.md` — implementation phases and module order
- `RISK_MATRIX.md` — known failure patterns to avoid

---

## Source of truth

- `BLUEPRINT.md` = **system design (must be followed strictly)**
- `REBUILD_PLAN.md` = **implementation sequence**
- `RISK_MATRIX.md` = **reliability constraints**

Do not override these documents unless explicitly instructed.

---

## Target stack

- PHP 8.3 / Laravel 11
- MySQL 8
- Blade + Bootstrap 5 (AdminLTE)
- Alpine.js (light UI interactions only)
- Spatie Laravel-Permission (RBAC)
- DomPDF or mPDF (PDF generation)

---

## Architecture rules

### Controllers
- Controllers must be thin
- Controllers must not contain business logic
- Controllers must not implement domain rules
- Controllers must delegate to service classes
- Prefer resource controllers

### Services
- All business logic must live in `app/Services/`
- Controllers must not directly perform business operations
- All write operations must go through services
- Services must receive typed parameters (not raw Request)

Core services:
- PurchaseOrderService
- InventoryService
- StockService
- DocumentSequenceService

---

## Validation

- Every write endpoint must use a FormRequest
- Never use `$request->all()` or `$request->only()`
- Validation messages must be in Spanish

---

## Transactions

- Any multi-table write must use `DB::transaction()`

Mandatory for:
- purchase order creation
- status transitions
- goods receipt
- inventory movements
- stock adjustments

---

## Models

- Always define `$fillable`
- Never use `$guarded = []`
- Define relationships with return types
- Use soft deletes for:
  - products
  - suppliers
  - purchase_orders

---

## Database conventions

- Use English for table/column names
- Use `snake_case`
- Primary keys: `id` (bigint)
- Foreign keys: `{entity}_id`
- Always include timestamps
- Soft deletes where required
- Boolean fields: `is_*`
- Amounts: `decimal(14,2)`
- Quantities: `decimal(12,3)`

---

## Inventory integrity rules

- Inventory is movement-based (ledger system)
- Stock must never be edited directly
- All changes must come from inventory movements
- Prevent negative stock at transaction time (hard validation)
- Inventory balances must be derivable from movements
- No direct updates to `inventory_balances` outside services

---

## Critical bugs to never reproduce

1. Never use assignment `=` instead of comparison `==` / `===`
2. Never assign collections (`->get()`) to scalar fields — use `->value()`
3. Never enforce mutually exclusive foreign keys as both required
4. Never mismatch column names (typos between migration and code)

---

## Business rules

### Purchase order lifecycle

DRAFT → APPROVED → PARTIAL_RECEIVED → RECEIVED  
                 ↘ CANCELLED

- Only DRAFT orders are editable
- Status transitions must be validated in services

---

### PO numbering

- Format: `PO-{YYYY}-{SUPPLIERCODE}-{SEQ6}`
- Sequence is per supplier per year
- Resets every January 1
- Assigned at approval
- Immutable after assignment
- Must be generated inside a transaction with row-level locking

---

## Scope rules

- Work only on the requested module or phase
- Do not generate UI unless explicitly requested
- Do not implement features outside the current phase
- Do not redesign the system unless explicitly instructed
- Do not add extra fields or tables not defined in BLUEPRINT.md

---

## Assumptions

- Do not assume missing business rules
- Do not invent logic not defined in BLUEPRINT.md
- Ask for clarification if something is unclear

---

## UI guidelines

- UI is secondary to business logic
- Do not prioritize styling early
- Use simple Blade views for functional validation
- UI refinement happens after core logic is stable

---

## Development approach

- Implement one module at a time
- Follow REBUILD_PLAN.md strictly
- Validate each module before moving to the next
- Favor correctness over speed

---