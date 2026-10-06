BEGIN

    INSERT INTO itens_pedido(
        id_pedido,
        id_produto,
        order_item_id,
        quantidade,
        preco_unitario
    )
    SELECT
        p.id_pedido,
        pr.id_produto,
        c.order_item_id,
        c.quantity_purchased,
        c.item_price
    FROM carga_pedidos c
    INNER JOIN pedidos p
        ON p.order_id = c.order_id
    INNER JOIN produtos pr
        ON pr.sku=c.sku
    WHERE NOT EXISTS(
        SELECT 1
        FROM itens_pedido i
        WHERE i.id_pedido=p.id_pedido
          AND i.order_item_id = c.order_item_id
    );

END
