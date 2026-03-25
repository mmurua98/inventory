# Reliability Risk Matrix

This matrix summarizes reliability risks identified from the current repository implementation.

---

## Critical

| Issue | Why it matters | Affected module | Recommended verification before rebuilding |
|---|---|---|---|
| Order status check uses assignment (`=`) instead of comparison in update flow. | Causes the inbound inventory/stock branch to execute regardless of actual status intent, corrupting stock and movement records. | Purchase Orders + Inventory Integration | Reproduce update for each status (`En proceso`, `Comprada`, `Cancelada`) and trace created movement/stock rows for each case. |
| Order `folio` is set from a query result object/collection instead of scalar value. | Can persist malformed folio values, break PO sequencing, and invalidate numbering/reporting consistency. | Purchase Orders | Create multiple POs for same supplier/year and validate stored `folio` datatype/value and resulting `po_number` sequence. |
| `orders.description` and `orders.require_employee_id` are used by code but not clearly present in visible order migrations. | Strong indicator of schema drift; app may depend on undocumented/manual DB changes and fail in clean environments. | Purchase Orders / Data Model | Compare production schema vs migrations (`SHOW CREATE TABLE orders`), identify missing migrations/manual patches, and reconcile canonical schema. |
| `require_employees` migration creates `last_namae`, but code expects `last_name`. | Read/write mismatch can break requestor name display/joins and cause runtime SQL errors depending on DB state. | Master Data (Requestors) + Purchase Orders/PDF | Inspect actual DB column names and run create/edit/list paths for requestors, order screens, and PDF joins. |

---

## High

| Issue | Why it matters | Affected module | Recommended verification before rebuilding |
|---|---|---|---|
| `movimiento_almacens` adds both `order_id` and `salida_id` FKs with non-null style migration intent. | Data model appears to require mutually exclusive relationships simultaneously; may force invalid dummy data or break inserts. | Inventory Movements | Validate actual nullability/constraints in DB and test creating movements from order-only and salida-only flows. |
| Multi-table writes are not wrapped in transactions (orders/details/movements/stocks). | Partial writes can leave system in inconsistent state on exceptions/timeouts/concurrent activity. | Purchase Orders + Inventory | Simulate failures during mid-flow operations and verify rollback behavior (or lack thereof). |
| Route-name collisions from repeated `->name('index')` across many GET routes. | Named route ambiguity can cause wrong redirects/links and brittle behavior in future changes/tests. | Routing/System-wide | Run `php artisan route:list` and audit duplicate names and current view usage of named routes. |
| Stock page JS contains invalid statement (`let  = '';`). | Script failure can break stock rendering/UX and hide inventory visibility. | Inventory (Stock UI) | Load stock page in browser with console open and verify script execution and rendering integrity. |

---

## Medium

| Issue | Why it matters | Affected module | Recommended verification before rebuilding |
|---|---|---|---|
| Heavy use of `$request->all()` with limited validation in CRUD controllers. | Increases risk of unintended/malformed data persistence and weak input hygiene. | Master Data + User Admin | Inventory all request fields accepted per endpoint and perform negative tests for invalid/extra payload fields. |
| Permission seeder creates permissions but does not sync them to role (commented). | Fresh environments may boot with incomplete authorization mapping and inconsistent access behavior. | Access Control (RBAC) | Seed from scratch in clean DB and verify effective permissions per role with real login/session tests. |
| Important business rules implemented mainly in Blade/jQuery scripts. | Domain behavior is hard to test and easy to bypass/diverge from backend validation. | Purchase Orders (UI logic) | Compare UI-calculated totals/IVA against backend persisted values across edge cases (price edits, quantity edits, deleted lines). |
| Hardcoded movement metadata (`warehouse_id = 1`, generic comments/folio). | Weak traceability and assumptions reduce reliability for multi-warehouse or audit scenarios. | Inventory Movements | Verify whether production is single-warehouse only and whether audit requirements require explicit references/comments/folios. |

---

## Low

| Issue | Why it matters | Affected module | Recommended verification before rebuilding |
|---|---|---|---|
| Legacy artifacts/draft files (`*.txt`, duplicate order views) remain in repository. | Raises confusion and maintenance overhead; risk of accidental reference to stale logic. | Codebase hygiene | Confirm runtime paths do not reference these files and classify as archive/remove candidates. |
| Debug leftovers (`debugger;`, commented blocks) in active views. | Mostly developer-noise but can interrupt frontend debugging sessions and readability. | Purchase Orders UI | Open affected screens and ensure no breakpoints or commented legacy blocks interfere with workflows. |
| Aging platform baseline (Laravel 8 / PHP 7.3-era deps). | Long-term security/support risk more than immediate runtime blocker if environment is stable. | Platform/Operations | Check current production PHP/framework versions, security advisories, and package support status before migration planning. |

---

## Suggested reliability baseline checks before rebuild kickoff

1. Schema truth capture from production (`orders`, `require_employees`, movement tables).
2. End-to-end transaction trace for: create PO, finalize PO, salida, entradas listing, stock rendering.
3. Permission sanity check in a fresh seeded environment.
4. Route/name audit and browser console audit on order/stock pages.
5. Data consistency audit for existing movement/stock records against PO status history.