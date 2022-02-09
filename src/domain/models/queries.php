<?php

/* SUMMARY */
$fetch_summary = <<<'SQL'
SELECT
    sum_materials,
    sum_biscuits,
    sum_incompletes,
    sum_products,
    (
        if(sum_materials is null, 0, sum_materials) +
        if(sum_biscuits is null, 0, sum_biscuits) +
        if(sum_incompletes is null, 0, sum_incompletes) +
        if(sum_products is null, 0, sum_products)
    ) AS sum_all
FROM (
    (SELECT sum(m.current_value) AS sum_materials FROM material m) t_sm,
    (SELECT sum(b.current_value) AS sum_biscuits FROM biscuit b) t_sb,
    (SELECT sum(i.current_value) AS sum_incompletes FROM incomplete i) t_si,
    (SELECT sum(p.current_value) AS sum_products FROM product p) t_sp
)
SQL;


/* ITEMS: MATERIALS, BISCUITS, INCOMPLETES, PRODUCTS */
$insert_item_copy_deleted = <<<'SQL'
INSERT INTO {item_type}_deleted
(origin_id, code, name, note, price, current_quantity, current_value, origin_user_id, origin_timestamp, user_id, timestamp)
SELECT id, code, name, note, price, current_quantity, current_value, user_id, timestamp, :user_id, NOW()
FROM {item_type}
WHERE id = :id
SQL;

$delete_item = <<<'SQL'
DELETE FROM {item_type} WHERE id = :id
SQL;


/* MATERIALS */
$insert_material_item = <<<'SQL'
INSERT INTO material (code, name, note, package_quantity, package_measure_unit, price, user_id, timestamp)
VALUES (:code, :name, :note, :package_quantity, :package_measure_unit, :price, :user_id, NOW())
SQL;

$increase_quantity_material = <<<'SQL'
UPDATE material
SET
    current_quantity = current_quantity + :amount,
    current_quantity_in_measure_unit = package_quantity * current_quantity,
    current_value = price * current_quantity
WHERE id = :id
SQL;

$insert_materials = <<<'SQL'
INSERT INTO materials (material_id, quantity, user_id, timestamp) VALUES (:material_id, :quantity, :user_id, NOW())
SQL;

$update_material_item = <<<'SQL'
UPDATE material
SET code = :code, name = :name, note = :note
WHERE id = :id
SQL;

$insert_material_copy_deleted = <<<'SQL'
INSERT INTO material_deleted
(origin_id, code, name, note, package_quantity, package_measure_unit, price, current_quantity, current_quantity_in_measure_unit, current_value, origin_user_id, origin_timestamp, user_id, timestamp)
SELECT id, code, name, note, package_quantity, package_measure_unit, price, current_quantity, current_quantity_in_measure_unit, current_value, user_id, timestamp, :user_id, NOW()
FROM material
WHERE id = :id
SQL;

$delete_material_item = <<<'SQL'
DELETE FROM material WHERE id = :id
SQL;


/* BISCUITS */
$insert_biscuit_item = <<<'SQL'
INSERT INTO biscuit (code, name, note, work_price, materials_price, price, user_id, timestamp)
VALUES (:code, :name, :note, :work_price, :materials_price, :price, :user_id, NOW())
SQL;

$insert_biscuit_materials = <<<'SQL'
INSERT INTO biscuit_materials (biscuit_id, material_id, material_quantity_used, material_calculated_price) VALUES (?, ?, ?, ?)
SQL;

$calculate_biscuit_max_possible_entry = <<<'SQL'
SELECT
    ROUND(m.current_quantity_in_measure_unit / bm.material_quantity_used) AS max_quantity_biscuit
FROM biscuit_materials bm
JOIN material m ON
    m.id = bm.material_id
WHERE bm.biscuit_id = :biscuit_id
SQL;

$increase_biscuit_quantity = <<<'SQL'
UPDATE biscuit_materials bm
JOIN biscuit b ON
    b.id = bm.biscuit_id
JOIN biscuit b2 ON
    b2.id = bm.biscuit_id
JOIN material m ON
    m.id = bm.material_id
JOIN material m2 ON
    m2.id = bm.material_id
JOIN (SELECT :amount AS amount) t_amount
SET
    b.current_quantity = b2.current_quantity + t_amount.amount,
    b.current_value = (b2.current_quantity + t_amount.amount) * b.price,
        
    m.current_quantity_in_measure_unit = IF((m2.current_quantity_in_measure_unit - (bm.material_quantity_used * t_amount.amount)) < 0, 0, (m2.current_quantity_in_measure_unit - (bm.material_quantity_used * t_amount.amount))),
    m.current_quantity = IF(((m2.current_quantity_in_measure_unit - (bm.material_quantity_used * t_amount.amount)) / m.package_quantity) < 0, 0, ((m2.current_quantity_in_measure_unit - (bm.material_quantity_used * t_amount.amount)) / m.package_quantity)),
    m.current_value = IF((m.current_value - (bm.material_calculated_price * t_amount.amount)) < 0, 0, (m.current_value - (bm.material_calculated_price * t_amount.amount)))
WHERE bm.biscuit_id = :biscuit_id
SQL;

$insert_biscuits = <<<'SQL'
INSERT INTO biscuits (biscuit_id, quantity, user_id, timestamp) VALUES (:biscuit_id, :quantity, :user_id, NOW())
SQL;

$select_biscuit_materials = <<<'SQL'
SELECT
    m.code,
    m.name,
    m.price,
    m.package_quantity,
    m.package_measure_unit,
    bm.material_quantity_used,
    m.current_quantity_in_measure_unit,
    bm.material_calculated_price
FROM biscuit_materials bm
JOIN material m ON
    m.id = bm.material_id
WHERE bm.biscuit_id = :biscuit_id
SQL;

$update_biscuit_item = <<<'SQL'
UPDATE biscuit
SET code = :code, name = :name, note = :note
WHERE id = :id
SQL;


/* INCOMPLETES */
$insert_incomplete_item = <<<'SQL'
INSERT INTO incomplete (code, name, note, work_price, biscuits_price, materials_price, price, user_id)
VALUES (:code, :name, :note, :work_price, :biscuits_price, :materials_price, :price, :user_id)
SQL;

$insert_incomplete_biscuits = <<<'SQL'
INSERT INTO incomplete_biscuits (incomplete_id, biscuit_id, biscuit_quantity_used, biscuit_calculated_price) VALUES (?, ?, ?, ?)
SQL;

$insert_incomplete_materials = <<<'SQL'
INSERT INTO incomplete_materials (incomplete_id, material_id, material_quantity_used, material_calculated_price) VALUES (?, ?, ?, ?)
SQL;

$calculate_incomplete_max_possible_entry = <<<'SQL'
SELECT LEAST(max_quantity_biscuits, max_quantity_materials) AS max_quantity_incomplete
FROM (
    SELECT
        ROUND(b.current_quantity / ib.biscuit_quantity_used) AS max_quantity_biscuits
    FROM incomplete_biscuits ib
    JOIN biscuit b ON
        b.id = ib.biscuit_id
    WHERE ib.incomplete_id = ?
) incomplete_biscuits_max,
(
    SELECT
        ROUND(m.current_quantity_in_measure_unit / CAST(im.material_quantity_used AS decimal(13,3))) AS max_quantity_materials
    FROM incomplete_materials im
    JOIN material m ON
        m.id = im.material_id
    WHERE im.incomplete_id = ?
) incomplete_materials_max 
SQL;

$increase_incomplete_quantity = <<<'SQL'
UPDATE incomplete i
JOIN incomplete i2 ON
    i2.id = i.id

JOIN incomplete_biscuits ib ON
    ib.incomplete_id = i.id
JOIN biscuit b ON
    b.id = ib.biscuit_id
    
JOIN incomplete_materials im ON
    im.incomplete_id = i.id
JOIN material m ON
    m.id = im.material_id
JOIN material m2 ON
    m2.id = im.material_id

JOIN (SELECT :amount AS amount) t_amount
SET
    i.current_quantity = i2.current_quantity + t_amount.amount,
    i.current_value = (i2.current_quantity + t_amount.amount) * i.price,
    
    b.current_quantity = b.current_quantity - (ib.biscuit_quantity_used * t_amount.amount),
    b.current_value = b.current_value - (ib.biscuit_calculated_price * t_amount.amount),
    
    m.current_quantity_in_measure_unit = IF((m2.current_quantity_in_measure_unit - (im.material_quantity_used * t_amount.amount)) < 0, 0, (m2.current_quantity_in_measure_unit - (im.material_quantity_used * t_amount.amount))),
    m.current_quantity = IF(((m2.current_quantity_in_measure_unit - (im.material_quantity_used * t_amount.amount)) / m.package_quantity) < 0, 0, ((m2.current_quantity_in_measure_unit - (im.material_quantity_used * t_amount.amount)) / m.package_quantity)),
    m.current_value = IF((m.current_value - (im.material_calculated_price * t_amount.amount)) < 0, 0, (m.current_value - (im.material_calculated_price * t_amount.amount)))
WHERE i.id = :incomplete_id
SQL;

$insert_incompletes = <<<'SQL'
INSERT INTO incompletes (incomplete_id, quantity, user_id, timestamp) VALUES (:incomplete_id, :quantity, :user_id, NOW())
SQL;

$select_incomplete_biscuits = <<<'SQL'
SELECT
    b.code,
    b.name,
    b.price,
    ib.biscuit_quantity_used,
    b.current_quantity,
    ib.biscuit_calculated_price
FROM incomplete_biscuits ib
JOIN biscuit b ON
    b.id = ib.biscuit_id
WHERE ib.incomplete_id = :incomplete_id
SQL;

$select_incomplete_materials = <<<'SQL'
SELECT
    m.code,
    m.name,
    m.price,
    m.package_quantity,
    m.package_measure_unit,
    im.material_quantity_used,
    m.current_quantity_in_measure_unit,
    im.material_calculated_price
FROM incomplete_materials im
JOIN material m ON
    m.id = im.material_id
WHERE im.incomplete_id = :incomplete_id
SQL;

$update_incomplete_item = <<<'SQL'
UPDATE incomplete
SET code = :code, name = :name, note = :note
WHERE id = :id
SQL;


/* PRODUCTS */
$insert_product_item = <<<'SQL'
INSERT INTO product (code, name, note, work_price, incompletes_price, materials_price, price, user_id)
VALUES (:code, :name, :note, :work_price, :incompletes_price, :materials_price, :price, :user_id)
SQL;

$insert_product_incompletes = <<<'SQL'
INSERT INTO product_incompletes (product_id, incomplete_id, incomplete_quantity_used, incomplete_calculated_price) VALUES (?, ?, ?, ?)
SQL;

$insert_product_materials = <<<'SQL'
INSERT INTO product_materials (product_id, material_id, material_quantity_used, material_calculated_price) VALUES (?, ?, ?, ?)
SQL;

$calculate_product_max_possible_entry = <<<'SQL'
SELECT LEAST(max_quantity_incompletes, max_quantity_materials) AS max_quantity_product
FROM (
    SELECT
        ROUND(i.current_quantity / pi.incomplete_quantity_used) AS max_quantity_incompletes
    FROM product_incompletes pi
    JOIN incomplete i ON
        i.id = pi.incomplete_id
    WHERE pi.product_id = ?
) product_incompletes_max,
(
    SELECT
        ROUND(m.current_quantity_in_measure_unit / CAST(pm.material_quantity_used AS decimal(13,3))) AS max_quantity_materials
    FROM product_materials pm
    JOIN material m ON
        m.id = pm.material_id
    WHERE pm.product_id = ?
) product_materials_max
SQL;

$increase_product_quantity = <<<'SQL'
UPDATE product p
JOIN product p2 ON
    p2.id = p.id

JOIN product_incompletes pi ON
    pi.product_id = p.id
JOIN incomplete i ON
    i.id = pi.incomplete_id
    
JOIN product_materials pm ON
    pm.product_id = p.id
JOIN material m ON
    m.id = pm.material_id
JOIN material m2 ON
    m2.id = pm.material_id

JOIN (SELECT :amount AS amount) t_amount
SET
    p.current_quantity = p2.current_quantity + t_amount.amount,
    p.current_value = (p2.current_quantity + t_amount.amount) * p.price,
    
    i.current_quantity = i.current_quantity - (pi.incomplete_quantity_used * t_amount.amount),
    i.current_value = i.current_value - (pi.incomplete_calculated_price * t_amount.amount),
    
    m.current_quantity_in_measure_unit = IF((m2.current_quantity_in_measure_unit - (pm.material_quantity_used * t_amount.amount)) < 0, 0, (m2.current_quantity_in_measure_unit - (pm.material_quantity_used * t_amount.amount))),
    m.current_quantity = IF(((m2.current_quantity_in_measure_unit - (pm.material_quantity_used * t_amount.amount)) / m.package_quantity) < 0, 0, ((m2.current_quantity_in_measure_unit - (pm.material_quantity_used * t_amount.amount)) / m.package_quantity)),
    m.current_value = IF((m.current_value - (pm.material_calculated_price * t_amount.amount)) < 0, 0, (m.current_value - (pm.material_calculated_price * t_amount.amount)))
WHERE p.id = :product_id
SQL;

$insert_products = <<<'SQL'
INSERT INTO products (product_id, quantity, user_id, timestamp) VALUES (:product_id, :quantity, :user_id, NOW())
SQL;

$select_product_incompletes = <<<'SQL'
SELECT
    i.code,
    i.name,
    i.price,
    pi.incomplete_quantity_used,
    i.current_quantity,
    pi.incomplete_calculated_price
FROM product_incompletes pi
JOIN incomplete i ON
    i.id = pi.incomplete_id
WHERE pi.product_id = :product_id
SQL;

$select_product_materials = <<<'SQL'
SELECT
    m.code,
    m.name,
    m.price,
    m.package_quantity,
    m.package_measure_unit,
    pm.material_quantity_used,
    m.current_quantity_in_measure_unit,
    pm.material_calculated_price
FROM product_materials pm
JOIN material m ON
    m.id = pm.material_id
WHERE pm.product_id = :product_id
SQL;

$update_product_item = <<<'SQL'
UPDATE product p
SET p.code = :code, p.name = :name, p.note = :note
WHERE p.id = :id
SQL;


/* FACTUREDS */
$insert_factured_item = <<<'SQL'
INSERT INTO factured (id, product_id, current_quantity, current_value) VALUES ((select f.id FROM factured f WHERE f.product_id = ?), ?, 0, 0)
ON DUPLICATE KEY
UPDATE id = id
SQL;

$select_factured_items = <<<'SQL'
SELECT
    f.id,
    t_product.code,
    t_product.name,
    t_product.price,
    f.current_quantity,
    f.current_value,
    t_product.is_deleted
FROM factured f
JOIN (
    SELECT p.id, p.code, p.name, p.price, p.current_quantity, false AS is_deleted
    FROM product p
    UNION
    SELECT pd.origin_id AS id, pd.code, pd.name, pd.price, pd.current_quantity, true AS is_deleted
    FROM product_deleted pd
) t_product ON
    t_product.id = f.product_id
/*LEFT JOIN factured f2 ON
    f2.id = f.id*/
WHERE t_product.current_quantity > 0 or f.current_quantity > 0
ORDER BY t_product.code
SQL;

$select_product_id = <<<'SQL'
SELECT p.id
FROM factured f
JOIN product p ON
    p.id = f.product_id
WHERE f.id = :factured_id
SQL;

$calculate_factured_max_possible_entry = <<<'SQL'
SELECT p.current_quantity
FROM product p
WHERE p.id = (SELECT f.product_id FROM factured f WHERE f.id = :factured_id)
SQL;

$increase_factured_quantity = <<<'SQL'
UPDATE factured f
JOIN factured f2 ON
    f2.id = f.id
JOIN product p ON
    p.id = f.product_id
JOIN (SELECT :amount AS amount) t_amount
SET
    f.current_quantity = f2.current_quantity + t_amount.amount,
    f.current_value = f2.current_value + (t_amount.amount * p.price),
    
    p.current_quantity = p.current_quantity - t_amount.amount,
    p.current_value = p.current_value - (t_amount.amount * p.price)
WHERE f.id = :factured_id
SQL;

$insert_factureds = <<<'SQL'
INSERT INTO factureds (factured_id, quantity, user_id, timestamp) VALUES (:factured_id, :quantity, :user_id, NOW())
SQL;


/* OVERVIEWS */
$select_storage_added_items = <<<'SQL'
SELECT t_rows.*
FROM (
    SELECT
        t_item.code,
        t_item.name,
        t_item.price,
        SUM(i_add.quantity) AS quantity,
        (SUM(i_add.quantity) * IF(t_item.price < 0, 1, t_item.price)) AS value
    FROM {item_type}s i_add
    JOIN (
        SELECT i.id, i.code, i.name, i.price, i.current_quantity
        FROM {item_type} i
        UNION
        SELECT id.origin_id AS id, id.code, id.name, id.price, id.current_quantity
        FROM {item_type}_deleted id
    ) t_item ON
        i_add.{item_type}_id = t_item.id
    WHERE DATE(i_add.timestamp) BETWEEN ? AND ?
    GROUP BY t_item.id
) t_rows
ORDER BY t_rows.quantity DESC, t_rows.code
SQL;

$select_factureds_added_items = <<<'SQL'
SELECT t_rows.*
FROM (
    SELECT
        t_product.code,
        t_product.name,
        t_product.price,
        SUM(f_add.quantity) AS quantity,
        (SUM(f_add.quantity) * IF(t_product.price < 0, 1, t_product.price)) AS value
    FROM factureds f_add
    JOIN factured f ON
        f.id = f_add.factured_id
    JOIN (
        SELECT p.id, p.code, p.name, p.price
        FROM product p
        UNION
        SELECT pd.origin_id AS id, pd.code, pd.name, pd.price
        FROM product_deleted pd
    ) t_product ON
        t_product.id = f.product_id
    WHERE DATE(f_add.timestamp) BETWEEN ? AND ?
    GROUP BY t_product.id
) t_rows
ORDER BY t_rows.quantity DESC, t_rows.code
SQL;

?>
