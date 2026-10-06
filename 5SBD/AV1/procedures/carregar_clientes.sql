BEGIN

    INSERT INTO clientes(
        nome,
        email,
        cpf,
        telefone,
        endereco,
        numero,
        complemento,
        cidade,
        estado,
        cep,
        pais
    )
    SELECT DISTINCT
        c.buyer_name,
        c.buyer_email,
        c.cpf,
        c.buyer_phone_number,
        c.ship_address_1,
        NULL,
        c.ship_address_2,
        c.ship_city,
        c.ship_state,
        c.ship_postal_code,
        c.ship_country
    FROM carga_pedidos c
    WHERE c.cpf IS NOT NULL
      AND NOT EXISTS(
          SELECT 1
          FROM clientes cl
          WHERE cl.cpf = c.cpf
      )
    GROUP BY
        c.cpf,
        c.buyer_name,
        c.buyer_email,
        c.buyer_phone_number,
        c.ship_address_1,
        c.ship_address_2,
        c.ship_city,
        c.ship_state,
        c.ship_postal_code,
        c.ship_country;

END
