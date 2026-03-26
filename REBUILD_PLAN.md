

# REBUILD_PLAN

## 1) System Purpose

This system is an internal operations platform for **inventory control and purchase order management**.

At a high level, it handles:
- Master data administration (products, categories, suppliers, employees, requestors, IVA/tax types, users/roles).
- Purchase order creation, editing, status tracking, and history.
- Inventory in/out movement logging tied to orders and manual product outputs.
- Stock visibility by product thresholds.
- Printable purchase orders (PDF report).

---

## 2) Current Modules

### A. Authentication & Dashboard
- Laravel auth scaffold (`Auth::routes`) and authenticated dashboard/home.

### B. Access Control (RBAC)
- Roles, permissions, user-role assignment.
- Gate-based authorization (`haveaccess`) with slug checks.

### C. Master Data Administration
- Employees.
- Require Employees (requestors).
- Categories.
- Products.
- IVA types.
- Suppliers.
- Warehouses (partially present, currently disabled in routes/menu).

### D. Purchase Orders
- Create purchase order with line items and IVA calculation.
- Edit order details and status (En proceso / Comprada / Cancelada).
- Order history listing.

### E. Inventory
- Salidas (product outputs to employees).
- Entradas (inbound movement listing from warehouse movement logs).
- Stock table with min/max and color indicators.
- Warehouse movement headers/details (`movimiento_almacens`, `movimiento_almacen_detalles`).

### F. Documents / Reporting
- Purchase order PDF generation (mPDF).

### G. Legacy/Experimental Artifacts
- Legacy/draft controllers and views remain in repository (`*.txt`, duplicate order views).

---

## 3) Known Problems (Current State)

## Critical logic bugs
1. **Order status assignment bug** in update flow: status check uses assignment (`=`) instead of comparison, causing inbound inventory logic to run incorrectly.
2. **Folio assignment bug**: order `folio` is assigned a query collection/object instead of scalar value.
3. **Malformed series filter** in folio query condition likely breaks intended supplier/year sequence logic.

## Schema/application inconsistencies
4. Order code uses fields such as `orders.description` and `orders.require_employee_id`, but these are not clearly present in the visible order migrations.
5. `require_employees` migration uses typo column `last_namae`, while code expects `last_name`.
6. `movimiento_almacens` receives both `order_id` and `salida_id` FKs with no nullable intent in migration, though business logic suggests one-or-the-other relationship.

## Routing/architecture issues
7. Multiple routes reuse the name `index`, introducing route-name collisions and ambiguity.
8. Warehouse module exists but is disabled from active routing/menu.
9. Business logic heavily embedded in Blade/jQuery scripts (line pricing, status behavior, calculations), limiting maintainability.

## Security/data-quality concerns
10. Broad mass-assignment patterns (`$request->all()`) across admin CRUD endpoints with minimal validation.
11. Multi-table write operations (orders, details, movements, stock) are not wrapped in DB transactions.
12. Seeder creates permissions but does not attach all of them to roles (sync call commented), creating uncertain permission state.

## Front-end/runtime defects
13. Stock view contains invalid JavaScript (`let  = '';`) that may break rendering.
14. Debug leftovers (`debugger;`, commented legacy blocks) remain in active screens.

## Platform aging
15. Stack versions are old (Laravel 8 / PHP 7.3 era dependencies).

---

## 4) Business Rules Inferred from Existing Code

1. **Access rule**:
   - A user can perform an action if any assigned role has `full-access = yes` OR role has permission with matching slug.

2. **PO status rule**:
   - Purchase order lifecycle is intended as:
     - `En proceso` (editable)
     - `Comprada` (finalized/purchased)
     - `Cancelada` (read-only)

3. **PO numbering rule**:
   - PO number format intended as a series + year + incremental folio (supplier-specific sequence logic attempted).

4. **Tax/IVA rule**:
   - IVA rate derives from supplier’s IVA type; line IVA and totals are calculated client-side.

5. **Inventory movement rule**:
   - `movement_type_id = 1` represents inbound entry.
   - `movement_type_id = 2` represents outbound issue.

6. **Salida rule**:
   - Saving a salida creates movement header/detail and decrements stock.

7. **Entrada reporting rule**:
   - Entradas are displayed from movement detail records with inbound type, joined to related order/supplier/product.

8. **History/print rule**:
   - All historical orders can be printed; edit action behavior varies by status.

---

## 5) Rebuild Goals

## Functional
1. Preserve core capabilities:
   - PO generation/edit/history/PDF.
   - Inventory entries/exits and stock visibility.
   - Master data administration.
   - Role-based access control.

2. Ensure inventory and PO state transitions are reliable and auditable.

## Technical
3. Create a **clean domain model** and consistent schema aligned to actual business entities.
4. Move business logic from Blade scripts/controllers into dedicated service classes.
5. Introduce robust validation and explicit request DTO/FormRequests.
6. Enforce transactional integrity for multi-table operations.
7. Standardize naming conventions (English or Spanish consistently; singular/plural and table naming).
8. Implement reliable test coverage for critical flows.
9. Remove dead artifacts and stabilize deployment/runtime configuration.

## Operational
10. Support maintainable admin operations, predictable permissions, and safe future evolution.

---

## 6) Open Questions / Schema Mismatches to Verify

1. **Orders columns**:
   - Are `description` and `require_employee_id` already present in production DB via missing migration(s) or manual changes?

2. **Require employees naming**:
   - Is production column `last_name` or typo `last_namae`?

3. **Movement linkage**:
   - Should `movimiento_almacens` reference either an order OR a salida (nullable polymorphic pattern), not both mandatory?

4. **PO folio strategy**:
   - Is folio sequence per supplier, per year, global, or per series+supplier+year?

5. **Order status master data**:
   - Which exact IDs/names are canonical for status values?

6. **Warehouse model usage**:
   - Single warehouse assumption or multi-warehouse roadmap?

7. **Tax behavior**:
   - Is IVA always supplier-level or can line-level overrides exist?

8. **Permission model completeness**:
   - Should every menu/action be permission-guarded and seeded by default?

9. **Legacy files**:
   - Are draft files intentionally retained for reference, or safe to archive/remove during rebuild?

10. **PDF output spec**:
    - Is current PDF layout legally/operationally required as-is, or can it be redesigned?

---

## 7) Proposed Modernization Direction (Laravel + MySQL + Blade)

## Target stack
- **Laravel (current LTS at rebuild time)**
- **MySQL 8+**
- **Blade + Bootstrap/AdminLTE (or equivalent admin theme)**
- Optional: lightweight Alpine.js for progressive enhancement where needed.

## Architecture direction
1. **Layered monolith** (modular domain folders):
   - Modules: Auth/RBAC, MasterData, Purchasing, Inventory, Reporting.
2. **Service-oriented application layer**:
   - `PurchaseOrderService`, `InventoryService`, `StockService` for core workflows.
3. **Strict request validation** using Form Requests.
4. **Repository/query separation** where query complexity is high (history/reporting).
5. **Transaction boundaries** around order finalization and inventory mutations.
6. **Domain events/audit trail** for stock and status changes.

## Data direction
7. Rebuild migrations from a validated schema map.
8. Add proper indexes and unique constraints (e.g., PO number uniqueness).
9. Normalize status/movement enums with controlled seed data.
10. Add nullable/foreign key semantics matching true business cardinality.

## UI direction (Blade-first)
11. Keep server-rendered Blade views for admin simplicity.
12. Move pricing/status logic to backend validations and service methods; keep JS only for UX enhancements.
13. Standardize route names and resource patterns.

## Security/quality direction
14. Harden authorization policies and gate usage consistency.
15. Replace broad `$request->all()` with explicit fillable mapping.
16. Build test suites:
    - Feature tests: order lifecycle, stock in/out, permission checks, PDF route access.
    - Unit tests: tax/total calculations, status transition rules.

## Migration strategy
17. Execute phased rebuild:
    - Phase 1: schema truth + read-only parity screens.
    - Phase 2: transactional write flows (PO + inventory).
    - Phase 3: RBAC hardening + reporting parity.
    - Phase 4: cleanup legacy artifacts + cutover.

---

## 8) Definition of Done for Rebuild Planning (Draft)

- Confirmed schema dictionary (tables, columns, constraints, enums).
- Verified business process diagrams for PO and inventory.
- Prioritized backlog with critical bug fixes and parity requirements.
- Approved target architecture and phased delivery plan.
- Signed data migration and rollback strategy.

---

## 9) Implementation Status Update — Phase 1 (Master Data)

### Status
- **Phase 1 is implemented and validated** for the scoped master data module.

### Completed scope
- Core tables and relationships implemented:
  - `product_categories`
  - `units`
  - `tax_rates`
  - `suppliers`
  - `products`
  - `warehouses`
- Eloquent models created with explicit `$fillable` and relationship mappings for the above entities.
- CRUD application layer completed for Phase 1:
  - Resource controllers
  - Store/Update `FormRequest` validation with Spanish user-facing messages
  - Web resource routes under `master-data`
  - Minimal Blade + Bootstrap UI (`index`, `create`, `edit`) per entity.

### Notable fixes applied during Phase 1 hardening
- Fixed boolean form handling so `is_active` can be reliably set to `false` from UI (create/update).
- Enforced `supplier_code` immutability on update (validation + update flow protections).
- Prevented edit-form forced reassignment by preserving currently selected inactive related records in selector datasets.

### Remaining minor follow-up items
- Add automated Feature tests for CRUD flows and validation edge cases in master data.
- Replace current `show -> edit` redirects with dedicated show pages (or remove `show` routes if not needed).
- Add access control (RBAC/policies) once Phase 3 RBAC hardening is executed.
