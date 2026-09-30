BEGIN

    INSERT INTO compras (
        id_produto,
        id_pedido,
        quantidade,
        status
    )
    SELECT
        i.id_produto,
        i.id_pedido,
        i.quantidade - p.estoque,
        'PENDENTE'
    FROM itens_pedido i
    INNER JOIN produtos p
        ON p.id_produto = i.id_produto
    INNER JOIN pedidos ped
        ON ped.id_pedido = i.id_pedido
    WHERE ped.status = 'PENDENTE'
      AND p.estoque < i.quantidade
      AND NOT EXISTS (
          SELECT 1
          FROM compras c
          WHERE c.id_produto = i.id_produto
            AND c.id_pedido = i.id_pedido
            AND c.status = 'PENDENTE'
      );

END