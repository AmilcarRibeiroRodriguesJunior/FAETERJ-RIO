BEGIN

    INSERT INTO pedidos(
        order_id,
        id_cliente,
        data_compra,
        data_pagamento,
        valor_total
    )
    SELECT
        c.order_id,
        cl.id_cliente,
        MIN(c.purchase_date),
        MIN(c.payments_date),
        SUM(c.quantity_purchased * c.item_price)
    FROM carga_pedidos c
    INNER JOIN clientes cl
        ON cl.cpf = c.cpf
    WHERE NOT EXISTS(
        SELECT 1
        FROM pedidos p
        WHERE p.order_id = c.order_id
    )
    GROUP BY
        c.order_id,
        cl.id_cliente;

END
