BEGIN

    DECLARE fim INT DEFAULT 0;

    DECLARE v_id_pedido INT;
    DECLARE v_id_produto INT;
    DECLARE v_quantidade INT;
    DECLARE v_estoque INT;
    DECLARE v_itens_faltantes INT;

    DECLARE cursor_pedidos CURSOR FOR
        SELECT
            id_pedido
        FROM pedidos
        WHERE status = 'PENDENTE'
        ORDER BY valor_total DESC;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fim = 1;

    OPEN cursor_pedidos;

    loop_pedidos: LOOP

        FETCH cursor_pedidos
        INTO v_id_pedido;

        IF fim = 1 THEN
            LEAVE loop_pedidos;
        END IF;

        SELECT COUNT(*)
        INTO v_itens_faltantes
        FROM itens_pedido i
        INNER JOIN produtos p
            ON p.id_produto = i.id_produto
        WHERE i.id_pedido = v_id_pedido
          AND p.estoque < i.quantidade;

        IF v_itens_faltantes = 0 THEN

            BEGIN

                DECLARE fim_itens INT DEFAULT 0;

                DECLARE cursor_itens CURSOR FOR
                    SELECT
                        i.id_produto,
                        i.quantidade
                    FROM itens_pedido i
                    WHERE i.id_pedido = v_id_pedido;

                DECLARE CONTINUE HANDLER FOR NOT FOUND
                    SET fim_itens = 1;

                OPEN cursor_itens;

                loop_itens: LOOP

                    FETCH cursor_itens
                    INTO v_id_produto, v_quantidade;

                    IF fim_itens = 1 THEN
                        LEAVE loop_itens;
                    END IF;

                    SELECT estoque
                    INTO v_estoque
                    FROM produtos
                    WHERE id_produto = v_id_produto;

                    INSERT INTO movimentacao_estoque (
                        id_produto,
                        id_pedido,
                        quantidade,
                        estoque_anterior,
                        estoque_atual
                    )
                    VALUES (
                        v_id_produto,
                        v_id_pedido,
                        v_quantidade,
                        v_estoque,
                        v_estoque - v_quantidade
                    );

                    UPDATE produtos
                    SET estoque = estoque - v_quantidade
                    WHERE id_produto = v_id_produto;

                END LOOP;

                CLOSE cursor_itens;

            END;

            UPDATE pedidos
            SET status = 'ATENDIDO'
            WHERE id_pedido = v_id_pedido;

        END IF;

    END LOOP;

    CLOSE cursor_pedidos;

END