📅 Date: 31 July 2026
👨💻 Name: Aditya Vishvakarma

Work Updates

1=> Configured and completed local code environment setup.
2=> Configured local database (DB) setup and environment settings.
3=> Fixed `cod_paid.csv` download issue ("Failed - No file") by creating a dedicated Laravel download route `admin.order.download_cod_paid_format`.
4=> Added `downloadCodPaidFormat` controller action in `OrderController` for safe CSV format file downloads.
5=> Updated `codcreate.blade.php` to use the named route and created fallback `cod_paid.csv` in project root.
6=> Improved `server.php` static asset routing to properly handle root and public static files under `php artisan serve`.
7=> Fixed "You have uploaded a wrong format file" error on CSV/XLSX imports by adding `importExcelOrCsv` helper that preserves original extensions for FastExcel and handles UTF-8 BOM headers with native CSV parsing fallback.
8=> Fixed database SQL error (`Unknown column 'cod_paid_amount'`) by creating migration `2026_07_31_000000_add_cod_fields_to_orders_table`, migrating it to DB, and adding `Schema::hasColumn` checks in `OrderController`.
9=> Enabled live search filtering in User / Seller selection dropdowns across COD, Remittance, Reports, Weight Reconciliation, Lost Shipments, and Broadcast modules.
10=> Fixed Remittance List query by removing strict `orders.status = 3` and `payment_mode = 6` filters so all remitted orders properly appear in the COD Remittance List.
11=> Removed "Last Remittance" card from the COD Remittance List view as requested.
12=> Confirmed and verified duplicate/same UTR support across multiple orders during bulk COD remittance import and updates.
13=> Updated CSV import process in `OrderController` (`storecod`) so that re-uploading an existing AWB updates its details (Paid Amount, Payment Date, UTR, Remark, and Status) instead of skipping it.
14=> Added dynamic `status` column parsing (`pending` vs `paid`/`success`) from CSV imports to set remittance and order statuses accordingly.
15=> Fixed Remittance List empty `-` rows issue by enforcing valid `remittances` join (`remittance_id > 0`), ensuring only actual remitted records appear.
16=> Added Status dropdown (`Paid` vs `Unpaid / Pending`) in Edit Remittance modal and updated `updateremittance` controller method to update status on Order & Remittance records.
17=> Fixed `Call to undefined function importExcelOrCsv()` by regenerating Composer autoloader (`composer dump-autoload`) and clearing application caches (`php artisan optimize:clear`).
18=> Renamed "Payments" menu option in the admin sidebar to "Finance" across layout files (`admin_layouts.blade.php` and `employee_dashboard.blade.php`).

