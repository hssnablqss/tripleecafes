<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item = $_POST['item'];
    $qty = $_POST['quantity'];
    $cust_id = 1; // Replace with session later

    // Get menu details from MENU table
    $menuSql = "SELECT MENU_ID, MENU_PRICE FROM MENU WHERE MENU_NAME = :item";
    $menuStid = oci_parse($conn, $menuSql);
    oci_bind_by_name($menuStid, ":item", $item);
    oci_execute($menuStid);
    $menuData = oci_fetch_assoc($menuStid);

    if (!$menuData) {
        echo json_encode(["success" => false, "error" => "Item not found"]);
        exit;
    }

    $menu_id = $menuData['MENU_ID'];
    $menu_price = $menuData['MENU_PRICE'];
    $total_price = $qty * $menu_price;

    // Insert into ORDERS (simplified — 1 item per order for now)
    $orderSql = "INSERT INTO ORDERS (ORDER_ID, ORDER_DATE, ORDER_TIME, ORDER_TOTAL, ORDER_STATUS, CUST_ID)
                 VALUES (ORDERS_SEQ.NEXTVAL, SYSDATE, TO_CHAR(SYSDATE, 'HH24:MI:SS'), :total, 'Pending', :cust_id)
                 RETURNING ORDER_ID INTO :order_id";

    $orderStid = oci_parse($conn, $orderSql);
    oci_bind_by_name($orderStid, ":total", $total_price);
    oci_bind_by_name($orderStid, ":cust_id", $cust_id);
    oci_bind_by_name($orderStid, ":order_id", $order_id, 10);

    $orderResult = oci_execute($orderStid);

    if (!$orderResult) {
        $e = oci_error($orderStid);
        echo json_encode(["success" => false, "error" => $e['message']]);
        exit;
    }

    // Insert into ORDERS_DETAILS
    $detailSql = "INSERT INTO ORDERS_DETAILS (ORDER_ID, MENU_ID, ORDER_QUANTITY, ORDER_DESC, ITEM_TOTAL)
                  VALUES (:order_id, :menu_id, :qty, '-', :total)";

    $detailStid = oci_parse($conn, $detailSql);
    oci_bind_by_name($detailStid, ":order_id", $order_id);
    oci_bind_by_name($detailStid, ":menu_id", $menu_id);
    oci_bind_by_name($detailStid, ":qty", $qty);
    oci_bind_by_name($detailStid, ":total", $total_price);

    $detailResult = oci_execute($detailStid);

    if ($detailResult) {
        echo json_encode(["success" => true]);
    } else {
        $e = oci_error($detailStid);
        echo json_encode(["success" => false, "error" => $e['message']]);
    }

    oci_free_statement($menuStid);
    oci_free_statement($orderStid);
    oci_free_statement($detailStid);
    oci_close($conn);
}
?>
