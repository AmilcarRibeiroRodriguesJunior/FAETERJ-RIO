BEGIN

    UPDATE produtos p
    INNER JOIN carga_fornecedor c
        ON c.sku = p.sku
    SET p.estoque = p.estoque + c.quantidade
    WHERE c.status = 'PENDENTE';

    UPDATE carga_fornecedor
    SET status='PROCESSADO'
    WHERE status='PENDENTE';

END
