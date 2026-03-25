# AGENTS.md — Inventory & Purchase Order System (v2)

## Project overview

This repository contains a full rebuild of a legacy Laravel 8 internal
inventory control and purchase order management system.

The legacy system had:
- critical bugs
- schema inconsistencies
- business logic embedded in Blade / jQuery
- unreliable inventory calculations
- inconsistent purchase order numbering

This version is rebuilt from scratch using Laravel 11 conventions and a
clean architecture.

---

## Repository structure

- The Laravel application lives inside `/app`
- Repository root is **not** the Laravel root
- Always run Composer, Artisan, and frontend asset commands from `/app`

Examples:
- `cd app && composer install`
- `cd app && php artisan migrate`
- `cd app && composer run dev`

---

## Read first (mandatory)

Before starting any implementation task, always read:

- `BLUEPRINT.md` — target system design, domain model, schema, business rules
- `REBUILD_PLAN.md` — implementation phases and module order
- `RISK_MATRIX.md` — legacy risks and anti-patterns to avoid

---

## Source of truth

- `BLUEPRINT.md` = system design (**must be followed strictly**)
- `REBUILD_PLAN.md` = implementation sequence
- `RISK_MATRIX.md` = reliability constraints

Do not redesign the system unless explicitly instructed.

---

## Target stack

- PHP 8.2
- Laravel 11
- MySQL 8
- Blade (server-rendered views)
- Alpine.js only for light progressive enhancement when explicitly needed
- Laravel default project structure and conventions
- Additional packages only when required by the current phase

Notes:
- Do not assume SQLite
- Do not assume a starter kit unless explicitly requested
- Prefer Laravel defaults before adding external abstractions

---

## Laravel 11 implementation principles

- Follow Laravel 11 default structure and conventions
- Keep configuration minimal unless a real project requirement demands otherwise
- Prefer first-party Laravel features before introducing third-party packages
- Use `.env` for environment-specific configuration
- Treat MySQL as the intended application database for this project

---

## Architecture rules

### Controllers
- Controllers must be thin
- Controllers must not contain business logic
- Controllers must delegate application work to services / actions
- Prefer resource controllers when appropriate

### Services
- Business logic must live in dedicated service classes under `app/Services/`
- Controllers must not directly orchestrate multi-step business operations
- Services must receive validated / typed data, not raw Request objects

Planned core services include:
- `PurchaseOrderService`
- `InventoryService`
- `StockService`
- `DocumentSequenceService`

### Validation
- Every write endpoint must use a dedicated FormRequest
- Never use `$request->all()` or `$request->only()` as the primary input boundary
- Validation messages shown to end users must be in Spanish

### Transactions
- Any multi-table write must use `DB::transaction()`

Mandatory for:
- purchase order creation
- purchase order approval / status transitions
- goods receipt
- inventory movements
- stock adjustments

### Models
- Always define `$fillable` explicitly
- Never use `$guarded = []`
- Define relationships clearly
- Use soft deletes only where defined in `BLUEPRINT.md`

---

## Database conventions

- Use English for table and column names
- Use `snake_case`
- Primary keys: `id` (bigint)
- Foreign keys: `{entity}_id`
- Include `created_at` and `updated_at`
- Use soft deletes only when explicitly required
- Boolean fields should use the `is_*` convention
- Amounts: `decimal(14,2)`
- Quantities: `decimal(12,3)`

---

## Inventory integrity rules

- Inventory is movement-based (ledger model)
- Stock must never be edited directly through arbitrary writes
- All stock changes must come from controlled inventory movements
- Negative stock must be prevented at transaction time unless an explicit business rule says otherwise
- `inventory_balances` must remain derivable / reconcilable from movements
- No direct updates to balances outside controlled services

---

## Critical bugs to never reproduce

- Never use assignment `=` where comparison `==` / `===` is intended
- Never assign a collection or query result object to a scalar field
- Never require mutually exclusive foreign keys at the same time
- Never mismatch migration column names and application code names
- Never bypass transaction boundaries for inventory or PO workflows

---

## Business rules

### Purchase order lifecycle
`DRAFT → APPROVED → PARTIAL_RECEIVED → RECEIVED`  
`      ↘ CANCELLED`

- Only DRAFT purchase orders are editable
- Status transitions must be validated in services, not controllers

### PO numbering
- Format: `PO-{YYYY}-{SUPPLIER_CODE}-{SEQ6}`
- Sequence is per supplier, per year
- Sequence resets every January 1
- Number is assigned at approval
- Number is immutable once assigned
- Generation must occur inside a transaction using row-level locking

---

## Scope rules

- Work only on the requested module or phase
- Do not generate UI unless explicitly requested
- Do not implement features outside the current phase
- Do not add extra business fields or tables not defined in `BLUEPRINT.md`
- Do not introduce packages unless justified by the current phase

---

## Assumptions

- Do not assume missing business rules
- Do not invent logic outside `BLUEPRINT.md`
- If something is ambiguous, ask for clarification before implementing

---

## UI rules

- UI is secondary to business logic
- Prefer simple Blade views first
- Do not prioritize visual polish in early phases
- Avoid admin templates unless explicitly requested
- Use Laravel / Blade conventions before adding UI complexity

---

## Development approach

- Implement one module at a time
- Follow `REBUILD_PLAN.md` strictly
- Validate each phase before moving forward
- Favor correctness, consistency, and maintainability over speed
