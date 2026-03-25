# System Redesign Blueprint (Production-Oriented)

> Scope: redesign of a legacy Laravel inventory + purchase order system.
> Goal: clean domain model, consistent schema, reliable inventory accounting, and clear module boundaries.

---

## 1) Design Principles

1. **Inventory is movement-driven** (ledger first, stock as derived/controlled state).
2. **Master data is stable and normalized** (products, suppliers, warehouses, taxes, units).
3. **Purchasing and inventory are decoupled but linked** (PO receipt creates inventory entries).
4. **All stock-changing operations are transactional and auditable**.
5. **Identifiers are immutable and human-friendly** (PO numbering with year + sequence).

---

## 2) Clean Domain Model

## A. Master Data
- ProductCategory
- Product
- UnitOfMeasure
- TaxProfile (or TaxRate)
- Supplier
- Warehouse
- WarehouseBin (optional later)

## B. Purchasing
- PurchaseOrder
- PurchaseOrderLine
- PurchaseOrderStatus
- GoodsReceipt (header)
- GoodsReceiptLine

## C. Inventory
- InventoryMovement (header)
- InventoryMovementLine
- InventoryBalance (current on-hand per product/warehouse)
- InventoryReservation (optional future for allocations)

## D. Users & Access
- User
- Role
- Permission
- UserRole
- RolePermission

## E. Reporting (optional module)
- Read-model views/materialized tables for:
  - PO history
  - Inventory ledger
  - Stock valuation
  - Low-stock alerts

---

## 3) Proposed Normalized Database Schema

## 3.1 Core reference tables

### `users`
- id (pk)
- name
- email (unique)
- password_hash
- is_active
- created_at, updated_at

### `roles`
- id (pk)
- name (unique)
- slug (unique)
- description

### `permissions`
- id (pk)
- name
- slug (unique)
- description

### `user_roles`
- user_id (fk users)
- role_id (fk roles)
- pk(user_id, role_id)

### `role_permissions`
- role_id (fk roles)
- permission_id (fk permissions)
- pk(role_id, permission_id)

---

### `product_categories`
- id (pk)
- name (unique)
- is_active

### `units`
- id (pk)
- code (unique)  // EA, KG, L, etc.
- name

### `tax_rates`
- id (pk)
- code (unique) // IVA_16, IVA_0, EXEMPT
- name
- rate_percent (decimal 5,2)
- is_active

### `products`
- id (pk)
- sku (unique)
- name
- description (nullable)
- category_id (fk product_categories)
- unit_id (fk units)
- tax_rate_id (fk tax_rates)
- min_stock (decimal 12,3 default 0)
- max_stock (decimal 12,3 nullable)
- is_active
- created_at, updated_at

### `suppliers`
- id (pk)
- supplier_code (unique, uppercase, immutable after assignment)
- legal_name
- trade_name (nullable)
- tax_id (nullable)
- email (nullable)
- phone (nullable)
- address_line (nullable)
- city/state/country/postal_code (nullable)
- default_tax_rate_id (fk tax_rates, nullable)
- is_active
- created_at, updated_at

### `warehouses`
- id (pk)
- code (unique)
- name
- location (nullable)
- is_active
- created_at, updated_at

---

## 3.2 Purchasing tables

### `purchase_order_statuses`
- id (pk)
- code (unique) // DRAFT, APPROVED, PARTIAL_RECEIVED, RECEIVED, CANCELLED
- name

### `purchase_orders`
- id (pk)
- po_number (unique, immutable)
- supplier_id (fk suppliers)
- warehouse_id (fk warehouses) // default destination
- status_id (fk purchase_order_statuses)
- ordered_at (datetime)
- expected_at (datetime nullable)
- requested_by_user_id (fk users nullable)
- notes (text nullable)
- currency_code (char3 default 'MXN' or local)
- subtotal, tax_total, grand_total (decimal 14,2)
- created_by_user_id (fk users)
- approved_by_user_id (fk users nullable)
- approved_at (datetime nullable)
- created_at, updated_at

### `purchase_order_lines`
- id (pk)
- purchase_order_id (fk purchase_orders)
- line_no (int)
- product_id (fk products)
- description (nullable)
- qty_ordered (decimal 12,3)
- qty_received (decimal 12,3 default 0)
- unit_price (decimal 14,4)
- tax_rate_id (fk tax_rates)
- line_subtotal, line_tax, line_total (decimal 14,2)
- unique(purchase_order_id, line_no)

### `goods_receipts`
- id (pk)
- receipt_number (unique)
- purchase_order_id (fk purchase_orders)
- received_at (datetime)
- received_by_user_id (fk users)
- supplier_document_ref (nullable)
- notes (nullable)
- created_at, updated_at

### `goods_receipt_lines`
- id (pk)
- goods_receipt_id (fk goods_receipts)
- purchase_order_line_id (fk purchase_order_lines)
- product_id (fk products)
- warehouse_id (fk warehouses)
- qty_received (decimal 12,3)
- unit_cost (decimal 14,4)

---

## 3.3 Inventory tables

### `movement_types`
- id (pk)
- code (unique) // IN_PURCHASE, OUT_CONSUMPTION, ADJUSTMENT_IN, ADJUSTMENT_OUT, TRANSFER_OUT, TRANSFER_IN
- direction (enum: IN, OUT)
- affects_cost (bool)

### `inventory_movements`
- id (pk)
- movement_number (unique)
- movement_type_id (fk movement_types)
- occurred_at (datetime)
- warehouse_id (fk warehouses)
- reference_type (string) // PURCHASE_ORDER, GOODS_RECEIPT, CONSUMPTION, ADJUSTMENT, TRANSFER
- reference_id (bigint nullable)
- status (enum: POSTED, VOID)
- notes (nullable)
- created_by_user_id (fk users)
- created_at, updated_at

### `inventory_movement_lines`
- id (pk)
- inventory_movement_id (fk inventory_movements)
- line_no
- product_id (fk products)
- qty (decimal 12,3 positive)
- unit_cost (decimal 14,4 nullable)
- lot_no (nullable, future)
- expires_at (nullable, future)
- unique(inventory_movement_id, line_no)

### `inventory_balances`
- id (pk)
- warehouse_id (fk warehouses)
- product_id (fk products)
- on_hand_qty (decimal 12,3)
- reserved_qty (decimal 12,3 default 0)
- available_qty (generated or maintained = on_hand - reserved)
- avg_cost (decimal 14,4 nullable)
- unique(warehouse_id, product_id)

### `stock_locks` (optional, for high concurrency)
- warehouse_id
- product_id
- lock_version
- pk(warehouse_id, product_id)

---

## 4) Keep / Rename / Merge / Remove Mapping

## Keep (concepts)
- Users, roles, permissions.
- Products, suppliers, warehouses.
- Purchase orders + lines.
- Inventory movements and stock tracking.

## Rename
- `tipos_ivas` -> `tax_rates`
- `orderstatus` -> `purchase_order_statuses`
- `orders` -> `purchase_orders`
- `order_details` -> `purchase_order_lines`
- `movimiento_almacens` -> `inventory_movements`
- `movimiento_almacen_detalles` -> `inventory_movement_lines`
- `stocks` -> `inventory_balances`
- `require_employees` -> remove/replace with `requested_by_user_id` on PO

## Merge
- Employee/requestor split should be unified into users (or a single people table) unless HR-specific requirements exist.
- Supplier tax association should be standardized using `default_tax_rate_id` instead of mixed ad-hoc tax fields.

## Remove
- Legacy/duplicate draft artifacts (`*.txt` controller drafts and duplicate order view copies).
- Redundant ambiguous columns and typo-based fields (e.g., `last_namae`).
- Hardcoded movement comments/folios not tied to real business keys.

---

## 5) Redesigned Purchase Order Numbering

## Target format
- `PO-{YYYY}-{SUPPLIERCODE}-{SEQ6}`
- Example: `PO-2026-ACME-000157`

## Rules
1. Sequence is **strictly supplier-specific and year-specific**.
2. Sequence **resets every year** for each supplier.
3. Number assigned only when PO moves from DRAFT -> APPROVED (or on creation if business requires).
4. Number is immutable once assigned.
5. PO number components must always include:
   - `year` (YYYY)
   - `supplier_code`
   - sequential number (zero-padded, fixed width)
6. Backed by dedicated sequence table:

### `document_sequences`
- id
- doc_type (PO)
- year (int)
- supplier_id (fk suppliers)
- current_value
- unique(doc_type, year, supplier_id)

## Generation approach
- Transaction + row-level lock on `(doc_type, year, supplier_id)` sequence record.
- Increment and compose `po_number` atomically in the same transaction.
- Unique constraint on `purchase_orders.po_number` as final guard.

## Required database constraints for safe uniqueness
1. `suppliers.supplier_code` -> `UNIQUE`, `NOT NULL`, normalized to uppercase.
2. `document_sequences` -> `UNIQUE(doc_type, year, supplier_id)`.
3. `purchase_orders.po_number` -> `UNIQUE`, `NOT NULL`.
4. `purchase_orders.supplier_id` -> FK to `suppliers(id)`.
5. `purchase_orders.ordered_at` or `po_year` persisted/derivable for audit and sequence troubleshooting.

## Supplier code policy (recommended)
- **Recommended safest option:** fixed **manual** code assignment with validation and governance.
- **Do not derive from company name at runtime** as primary key identity (names change, spelling/localization drift, merger/rebrand cases).
- Make `supplier_code` immutable once used in any PO number (block edits; allow deactivation instead).
- Suggested format: 3-10 uppercase alphanumeric chars, enforced by validation + unique index.
- If legal rename occurs, keep historical `supplier_code`; use `trade_name` / `legal_name` for display changes only.

---

## 6) Redesigned Inventory Handling

## Movement-based logic
1. **Only posted movements affect stock**.
2. Every stock change is represented by an inventory movement + line(s).
3. Purchase receipt creates `IN_PURCHASE` movement.
4. Consumption/dispatch creates `OUT_CONSUMPTION` movement.
5. Adjustments and transfers are explicit movement types.

## Stock consistency rules
1. `inventory_balances` is the current state per product/warehouse.
2. Balance updates happen in same transaction as movement posting.
3. `on_hand_qty = sum(IN) - sum(OUT)` must reconcile with ledger (scheduled audit job).
4. `available_qty = on_hand_qty - reserved_qty`.

## Preventing negative stock
1. For all OUT movements, validate `available_qty >= required_qty` before posting.
2. Fail transaction if any line violates stock rule.
3. Optionally support “allow_negative_stock” as a controlled, audited override (default false).
4. Add concurrency protection (row lock/version) for high-frequency product updates.

---

## 7) Clear Module Boundaries

## Purchasing Module
- Owns: suppliers (read), POs, PO lines, approvals, receipts integration trigger.
- Outputs: approved PO, pending receipt quantities.

## Inventory Module
- Owns: warehouses, movement posting, balances, stock validation, adjustments, transfers.
- Inputs: goods receipts, consumption requests, adjustments.
- Outputs: stock state, ledger, low-stock events.

## Master Data Module
- Owns: products, categories, units, taxes, suppliers, warehouses.
- Serves authoritative reference data to Purchasing and Inventory.

## Users & Roles Module
- Owns authentication, role/permission mapping, policy enforcement.

## Reporting Module (optional)
- Read-only projections for operational and audit reporting.
- No direct writes to transactional tables.

---

## 8) Phased Rebuild Plan (Recommended)

## Phase 1 — Master Data + Users/Roles
**Why first:** all transactional modules depend on trusted reference data and access control.
- Build clean master entities and RBAC.
- Migrate/clean product, supplier, warehouse, tax catalogs.
- Establish `supplier_code` governance: format, uniqueness, immutability policy.

## Phase 2 — Inventory Core (Ledger + Balances)
**Why second:** inventory consistency is highest operational risk.
- Implement movement posting engine + negative stock protections.
- Deliver stock inquiry and ledger screens early.

## Phase 3 — Purchasing Core
**Why third:** purchasing can now safely post receipts into stable inventory engine.
- Implement PO lifecycle + supplier-year sequence numbering + line totals + receipt processing.
- Integrate goods receipt -> inventory movement posting transactionally.

## Phase 4 — Reporting + PDF + Historical Migration
**Why fourth:** after transactional correctness is stable.
- Rebuild PO printouts and reports on new schema.
- Validate historical data migration and reconciliation reports.

## Phase 5 — Hardening & Cutover
- Performance tuning, audit checks, reconciliation jobs.
- Freeze legacy writes, final migration delta, production cutover.

---

## 9) Pre-Build Verification Checklist

1. Confirm canonical status lifecycle and approval workflow.
2. Confirm supplier-specific yearly sequencing policy with business owners (no global fallback).
3. Confirm whether multi-warehouse is required now or later.
4. Define valuation method (moving average vs FIFO) for cost reporting.
5. Confirm whether requestors must be users or separate business entities.
6. Validate compliance constraints for PDF/tax fields.