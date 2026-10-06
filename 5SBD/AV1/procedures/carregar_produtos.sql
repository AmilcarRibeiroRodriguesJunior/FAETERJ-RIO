BEGIN

    INSERT INTO produtos(
        id_produto,
        sku,
        nome,
        preco,
        estoque
    )
    SELECT
        NULL,
        c.sku,
        MAX(c.product_name),
        MAX(c.item_price),
        0
    FROM carga_pedidos c
    WHERE NOT EXISTS(
        SELECT 1
        FROM produtos p
        WHERE p.sku = c.sku
    )
    GROUP BY c.sku;

END
