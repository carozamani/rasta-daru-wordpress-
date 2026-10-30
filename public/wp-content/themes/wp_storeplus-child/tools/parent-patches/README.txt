Snapshots of PARENT theme files carrying child-theme hook lines (taken 2026-10-30).
Files are named *.snapshot so they can never be executed.
After updating wp_storeplus: run  php tools/check-parent-hooks.php
If a hook is lost, diff the updated parent file against its snapshot and re-add the
lines that mention storeplus_child_*. Every hook is guarded by function_exists(),
so a lost hook only reverts that piece to the parent default; it never breaks the site.
archive-product.php is no longer patched in the parent: it is a child-theme override
(wp_storeplus-child/woocommerce/archive-product.php).
