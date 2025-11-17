<?php
header('Content-Type: application/json; charset=utf-8');

$host = 'localhost';
$dbname = 'albergue_la';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Erro de conexão: ' . $e->getMessage()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// CORREÇÃO PRINCIPAL: definir action corretamente
$action = $_GET['action'] ?? null;

// Endpoint para testes
if ($action === 'test') {
    echo json_encode([
        "php_input" => file_get_contents('php://input'),
        "decoded" => json_decode(file_get_contents('php://input'), true),
        "post" => $_POST,
        "get" => $_GET,
        "method" => $_SERVER['REQUEST_METHOD']
    ]);
    exit;
}

/* ============================================================
   LOGIN
============================================================ */
if ($action === 'login' && $method === 'POST') {

    if (!$input || !isset($input['username'], $input['password'], $input['role'])) {
        echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
        exit;
    }

    $username = $input['username'];
    $password = $input['password'];
    $role = $input['role'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ? AND role = ?");
    $stmt->execute([$username, $role]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']){
        unset($user['password']);
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Credenciais inválidas']);
    }
    exit;
}

/* ============================================================
   REGISTRO DE USUÁRIO
============================================================ */
if ($action === 'register' && $method === 'POST') {

    $username = $input['username'];
    $password = password_hash($input['password'], PASSWORD_DEFAULT);
    $role = 'cliente';
    $name = $input['name'];
    $email = $input['email'];
    $cpf = $input['cpf'] ?? null;
    $telefone = $input['telefone'] ?? null;

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, role, name, email, cpf, telefone)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $password, $role, $name, $email, $cpf, $telefone]);
        echo json_encode(['success' => true, 'message' => 'Usuário cadastrado com sucesso']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar: ' . $e->getMessage()]);
    }
    exit;
}

/* ============================================================
   BUSCAR QUARTOS
============================================================ */
if ($action === 'rooms' && $method === 'GET') {

    $status = $_GET['status'] ?? null;

    if ($status) {
        $stmt = $pdo->prepare("SELECT * FROM quartos WHERE status = ? ORDER BY number");
        $stmt->execute([$status]);
    } else {
        $stmt = $pdo->query("SELECT * FROM quartos ORDER BY number");
    }

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

/* ============================================================
   CADASTRAR QUARTO
============================================================ */
if ($action === 'rooms' && $method === 'POST') {

    $number = $input['number'];
    $type = $input['type'];
    $price = $input['price'];
    $capacity = $input['capacity'];
    $description = $input['description'] ?? '';

    try {
        $stmt = $pdo->prepare("INSERT INTO quartos (number, type, price, capacity, description)
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$number, $type, $price, $capacity, $description]);
        echo json_encode(['success' => true, 'message' => 'Quarto cadastrado com sucesso']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar quarto: ' . $e->getMessage()]);
    }
    exit;
}

/* ============================================================
   EDITAR QUARTO
============================================================ */
if ($action === 'rooms' && $method === 'PUT') {

    $id = $input['id'];
    $number = $input['number'];
    $type = $input['type'];
    $price = $input['price'];
    $capacity = $input['capacity'];
    $status = $input['status'];
    $description = $input['description'] ?? '';

    try {
        $stmt = $pdo->prepare("UPDATE quartos SET number = ?, type = ?, price = ?, capacity = ?, status = ?, description = ?
                               WHERE id = ?");
        $stmt->execute([$number, $type, $price, $capacity, $status, $description, $id]);
        echo json_encode(['success' => true, 'message' => 'Quarto atualizado com sucesso']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar quarto: ' . $e->getMessage()]);
    }
    exit;
}

/* ============================================================
   DELETAR QUARTO
============================================================ */
if ($action === 'rooms' && $method === 'DELETE') {

    $id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM quartos WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Quarto deletado com sucesso']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao deletar quarto: ' . $e->getMessage()]);
    }
    exit;
}

/* ============================================================
   CRIAR RESERVA
============================================================ */
if ($action === 'reservations' && $method === 'POST') {

    $userId = $input['user_id'];
    $roomId = $input['room_id'];
    $checkIn = $input['check_in'];
    $checkOut = $input['check_out'];
    $observations = $input['observations'] ?? '';

    $stmt = $pdo->prepare("SELECT price FROM quartos WHERE id = ?");
    $stmt->execute([$roomId]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    $date1 = new DateTime($checkIn);
    $date2 = new DateTime($checkOut);
    $dias = $date1->diff($date2)->days;
    $totalPrice = $room['price'] * $dias;

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM reservas
                               WHERE room_id = ? AND status != 'cancelada'
                               AND (
                                   (check_in BETWEEN ? AND ?)
                                   OR
                                   (check_out BETWEEN ? AND ?)
                               )");
        $stmt->execute([$roomId, $checkIn, $checkOut, $checkIn, $checkOut]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            echo json_encode(['success' => false, 'message' => 'Quarto não disponível para estas datas']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO reservas (user_id, room_id, check_in, check_out, total_price, observations, status)
                               VALUES (?, ?, ?, ?, ?, ?, 'confirmada')");
        $stmt->execute([$userId, $roomId, $checkIn, $checkOut, $totalPrice, $observations]);

        $stmt = $pdo->prepare("UPDATE quartos SET status = 'ocupado' WHERE id = ?");
        $stmt->execute([$roomId]);

        echo json_encode(['success' => true, 'message' => 'Reserva criada com sucesso', 'total_price' => $totalPrice]);

    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao criar reserva: ' . $e->getMessage()]);
    }
    exit;
}

/* ============================================================
   LISTAR RESERVAS
============================================================ */
if ($action === 'reservations' && $method === 'GET') {

    $userId = $_GET['user_id'] ?? null;

    if ($userId) {
        $stmt = $pdo->prepare("
            SELECT r.*, q.number AS room_number, q.type AS room_type, u.name AS user_name
            FROM reservas r
            JOIN quartos q ON r.room_id = q.id
            JOIN usuarios u ON r.user_id = u.id
            WHERE r.user_id = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$userId]);
    } else {
        $stmt = $pdo->query("
            SELECT r.*, q.number AS room_number, q.type AS room_type, u.name AS user_name
            FROM reservas r
            JOIN quartos q ON r.room_id = q.id
            JOIN usuarios u ON r.user_id = u.id
            ORDER BY r.created_at DESC
        ");
    }

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

/* ============================================================
   CANCELAR RESERVA
============================================================ */
if ($action === 'cancel_reservation' && $method === 'PUT') {

    $id = $input['id'];

    try {
        $stmt = $pdo->prepare("SELECT room_id FROM reservas WHERE id = ?");
        $stmt->execute([$id]);
        $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("UPDATE reservas SET status = 'cancelada' WHERE id = ?");
        $stmt->execute([$id]);

        $stmt = $pdo->prepare("UPDATE quartos SET status = 'disponivel' WHERE id = ?");
        $stmt->execute([$reserva['room_id']]);

        echo json_encode(['success' => true, 'message' => 'Reserva cancelada com sucesso']);

    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao cancelar reserva: ' . $e->getMessage()]);
    }
    exit;
}

/* ============================================================
   ESTATÍSTICAS
============================================================ */
if ($action === 'stats' && $method === 'GET') {

    $stats = [];

    $stats['total_rooms'] = $pdo->query("SELECT COUNT(*) AS total FROM quartos")->fetch(PDO::FETCH_ASSOC)['total'];
    $stats['available_rooms'] = $pdo->query("SELECT COUNT(*) AS total FROM quartos WHERE status = 'disponivel'")->fetch(PDO::FETCH_ASSOC)['total'];
    $stats['occupied_rooms'] = $pdo->query("SELECT COUNT(*) AS total FROM quartos WHERE status = 'ocupado'")->fetch(PDO::FETCH_ASSOC)['total'];
    $stats['total_reservations'] = $pdo->query("SELECT COUNT(*) AS total FROM reservas WHERE status != 'cancelada'")->fetch(PDO::FETCH_ASSOC)['total'];
    $stats['total_clients'] = $pdo->query("SELECT COUNT(*) AS total FROM usuarios WHERE role = 'cliente'")->fetch(PDO::FETCH_ASSOC)['total'];

    echo json_encode($stats);
    exit;
}

/* ============================================================
   SERVIÇOS
============================================================ */
if ($action === 'services' && $method === 'GET') {
    $services = [
        [
            'id' => 1,
            'title' => 'Wi-fi gratuito',
            'icon' => 'wifi',
            'description' => 'Temos o melhor wi-fi de Santa Teresa em nossas dependências.'
        ],
        [
            'id' => 2,
            'title' => 'Brilhantes instalações',
            'icon' => 'bed',
            'description' => 'Temos as melhores vagas para o seu conforto e descanso.'
        ],
        [
            'id' => 3,
            'title' => 'Ar condicionado',
            'icon' => 'air',
            'description' => 'Todas as acomodações possuem ar-condicionado.'
        ],
        [
            'id' => 4,
            'title' => 'Alimentação inclusa',
            'icon' => 'food',
            'description' => 'A partir de sua reserva, está garantido o seu café da manhã, almoço e jantar em nossos aposentos.'
        ],
        [
            'id' => 5,
            'title' => 'Segurança 24 horas',
            'icon' => 'security',
            'description' => 'Fique tranquilo, o Albergue LA tem câmeras em todas as suas instalações e seguranças no seu entorno.'
        ]
    ];
    
    echo json_encode($services);
    exit;
}

/* ============================================================
   LOCALIZAÇÃO
============================================================ */
if ($action === 'location' && $method === 'GET') {
    $location = [
        'title' => 'Informações sobre o Albergue LA',
        'description' => 'O Albergue LA foi projetado para oferecer a privacidade de visitantes prezando uma atmosfera de albergue. O Albergue LA tem dormitórios mistos para 4, 8 e 12 pessoas com banheiros privativos. Todos equipados com ar condicionado, guarda-roupas individuais e roupas de cama.',
        'address' => 'Santa Teresa, Rio de Janeiro, RJ',
        'map_url' => 'https://maps.google.com/?q=Parque+Gloria+Maria+Rio+de+Janeiro',
        'latitude' => -22.9160,
        'longitude' => -43.1873,
        'policies' => [
            'check_in_time' => '12h',
            'check_out_time' => '12h',
            'cancellation' => 'Os depósitos correspondentes às reservas não são reembolsados para cancelamentos com menos de 72 horas de antecedência da data e hora do check-in. Para tarifas não reembolsáveis, cobramos 100% do valor da reserva.',
            'payment' => 'Por favor, tenha em mente que todas as reservas serão feitas apenas no cartão de crédito.'
        ]
    ];
    
    echo json_encode($location);
    exit;
}

/* ============================================================
   REDES SOCIAIS / CONTATOS
============================================================ */
if ($action === 'social' && $method === 'GET') {
    $social = [
        'period' => 'Dados dos últimos 30 dias',
        'facebook' => [
            'handle' => '@alberguelasantateresa',
            'views' => 9886,
            'interactions' => 4752,
            'followers' => 891,
            'url' => 'https://facebook.com/alberguelasantateresa'
        ],
        'instagram' => [
            'handle' => '@alberguelasantateresa',
            'views' => 7297,
            'interactions' => 2154,
            'followers' => 466,
            'url' => 'https://instagram.com/alberguelasantateresa'
        ],
        'twitter' => [
            'handle' => '@alberguelasantateresa',
            'views' => 4708,
            'interactions' => 1085,
            'followers' => 253,
            'url' => 'https://twitter.com/alberguelasantateresa'
        ]
    ];
    
    echo json_encode($social);
    exit;
}

/* ============================================================
   AVALIAÇÕES (LISTAR)
============================================================ */
if ($action === 'reviews' && $method === 'GET') {
    $reviews = [
        [
            'id' => 1,
            'name' => 'Andrew Snows',
            'rating' => 5,
            'comment' => 'Adorei a receptividade do albergue!!! Tudo muito limpo e muito cheiroso. Recomendo bastante!!! Amei o quarto com 12 vagas!!',
            'avatar' => 'user1'
        ],
        [
            'id' => 2,
            'name' => 'Jamil Hallen',
            'rating' => 4,
            'comment' => 'Tive uma ótima estadia no hotel! O quarto era espaçoso e equipado com todas as comodidades necessárias. A única coisa pela qual não dei 5 estrelas foi porque houve um pouco de barulho durante a noite vindo da rua, o que foi um pouco difícil e sono.',
            'avatar' => 'user2'
        ],
        [
            'id' => 3,
            'name' => 'Claude Bishop',
            'rating' => 5,
            'comment' => 'Minha estadia neste hotel foi simplesmente perfeita! Desde a chegada, o atendimento foi acolhedor, com uma equipe simpática e prestativa. O quarto era espaçoso, limpo e muito confortável, com todas as comodidades necessárias para uma estadia relaxante.',
            'avatar' => 'user3'
        ]
    ];
    
    echo json_encode($reviews);
    exit;
}

/* ============================================================
   ADICIONAR AVALIAÇÃO
============================================================ */
if ($action === 'reviews' && $method === 'POST') {
    
    $userId = $input['user_id'];
    $rating = $input['rating'];
    $comment = $input['comment'];
    
    try {
        // Verificar se o usuário já fez uma reserva confirmada
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM reservas 
                               WHERE user_id = ? AND status = 'confirmada'");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] == 0) {
            echo json_encode(['success' => false, 'message' => 'Apenas clientes com reservas confirmadas podem avaliar']);
            exit;
        }
        
        // Criar tabela de avaliações se não existir
        $pdo->exec("CREATE TABLE IF NOT EXISTS avaliacoes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            rating INT NOT NULL,
            comment TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES usuarios(id)
        )");
        
        $stmt = $pdo->prepare("INSERT INTO avaliacoes (user_id, rating, comment) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $rating, $comment]);
        
        echo json_encode(['success' => true, 'message' => 'Avaliação enviada com sucesso']);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao enviar avaliação: ' . $e->getMessage()]);
    }
    exit;
}

/* ============================================================
   INFORMAÇÕES GERAIS (Home)
============================================================ */
if ($action === 'home_info' && $method === 'GET') {
    $homeInfo = [
        'welcome_message' => 'BEM-VINDO AO ALBERGUE LA!',
        'subtitle' => 'Albergue LA, o melhor cantinho de Santa Teresa feito para você!',
        'description' => 'ENCONTRE ACOMODAÇÕES PERSONALIZADAS COM OPÇÕES DE QUARTOS DIVERSOS. RESERVE AGORA E NÃO PERCA A MELHOR EXPERIÊNCIA DO RIO DE JANEIRO!',
        'quick_links' => [
            ['name' => 'SERVIÇOS', 'action' => 'services'],
            ['name' => 'ACOMODAÇÕES', 'action' => 'rooms'],
            ['name' => 'AVALIAÇÕES', 'action' => 'reviews'],
            ['name' => 'LOCALIZAÇÃO', 'action' => 'location'],
            ['name' => 'CONTATO', 'action' => 'social']
        ]
    ];
    
    echo json_encode($homeInfo);
    exit;
}

/* ============================================================
   fallback
============================================================ */
echo json_encode(['error' => 'Ação não encontrada']);
exit;

?>