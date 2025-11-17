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

// Captura método HTTP
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Captura ação (action)
$action = $_GET['action'] ?? null;

// Função de resposta JSON
function resposta($success, $message, $data = null){
    echo json_encode(['success'=>$success,'message'=>$message,'data'=>$data]);
    exit();
}

/* ===============================
   ENDPOINT DE TESTE
=============================== */
if ($action === 'test'){
    echo json_encode([
        "php_input" => file_get_contents('php://input'),
        "decoded" => $input,
        "post" => $_POST,
        "get" => $_GET,
        "method" => $method
    ]);
    exit;
}

/* ===============================
   LOGIN
=============================== */
if ($action === 'login' && $method === 'POST'){

    if (!$input || !isset($input['username'],$input['password'],$input['role'])){
        resposta(false,'Dados incompletos');
    }

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username=? AND role=?");
    $stmt->execute([$input['username'],$input['role']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($input['password'],$user['password'])){
        unset($user['password']);
        resposta(true,'Login bem-sucedido',$user);
    } else {
        resposta(false,'Credenciais inválidas');
    }
}

/* ===============================
   REGISTRO DE USUÁRIO
=============================== */
if ($action === 'register' && $method === 'POST'){
    $password = password_hash($input['password'], PASSWORD_DEFAULT);
    $role = 'cliente';
    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (username,password,role,name,email,cpf,telefone) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$input['username'],$password,$role,$input['name'],$input['email'],$input['cpf'] ?? null,$input['telefone'] ?? null]);
        resposta(true,'Usuário cadastrado com sucesso');
    } catch(PDOException $e){
        resposta(false,'Erro ao cadastrar usuário: '.$e->getMessage());
    }
}

/* ===============================
   QUARTOS
=============================== */
if ($action === 'rooms'){

    if ($method === 'GET'){ // Listar quartos
        $status = $_GET['status'] ?? null;
        if($status){
            $stmt = $pdo->prepare("SELECT * FROM quartos WHERE status=? ORDER BY number");
            $stmt->execute([$status]);
        } else {
            $stmt = $pdo->query("SELECT * FROM quartos ORDER BY number");
        }
        resposta(true,'Quartos listados com sucesso',$stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    if ($method === 'POST'){ // Cadastrar quarto
        $stmt = $pdo->prepare("INSERT INTO quartos (number,type,price,capacity,description) VALUES (?,?,?,?,?)");
        $stmt->execute([$input['number'],$input['type'],$input['price'],$input['capacity'],$input['description'] ?? '']);
        resposta(true,'Quarto cadastrado com sucesso');
    }

    if ($method === 'PUT'){ // Editar quarto
        $stmt = $pdo->prepare("UPDATE quartos SET number=?,type=?,price=?,capacity=?,status=?,description=? WHERE id=?");
        $stmt->execute([$input['number'],$input['type'],$input['price'],$input['capacity'],$input['status'],$input['description'] ?? '',$input['id']]);
        resposta(true,'Quarto atualizado com sucesso');
    }

    if ($method === 'DELETE'){ // Deletar quarto
        $id = $_GET['id'] ?? null;
        if(!$id) resposta(false,'ID não informado');
        $stmt = $pdo->prepare("DELETE FROM quartos WHERE id=?");
        $stmt->execute([$id]);
        resposta(true,'Quarto deletado com sucesso');
    }
}

/* ===============================
   RESERVAS
=============================== */
if ($action === 'reservations'){

    if ($method === 'GET'){ // Listar reservas
        $userId = $_GET['user_id'] ?? null;
        if($userId){
            $stmt = $pdo->prepare("SELECT r.*, q.number AS room_number, q.type AS room_type, u.name AS user_name
                                   FROM reservas r
                                   JOIN quartos q ON r.room_id=q.id
                                   JOIN usuarios u ON r.user_id=u.id
                                   WHERE r.user_id=?
                                   ORDER BY r.created_at DESC");
            $stmt->execute([$userId]);
        } else {
            $stmt = $pdo->query("SELECT r.*, q.number AS room_number, q.type AS room_type, u.name AS user_name
                                 FROM reservas r
                                 JOIN quartos q ON r.room_id=q.id
                                 JOIN usuarios u ON r.user_id=u.id
                                 ORDER BY r.created_at DESC");
        }
        resposta(true,'Reservas listadas com sucesso',$stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    if ($method === 'POST'){ // Criar reserva
        $stmt = $pdo->prepare("SELECT price FROM quartos WHERE id=?");
        $stmt->execute([$input['room_id']]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        $dias = (new DateTime($input['check_in']))->diff(new DateTime($input['check_out']))->days;
        $totalPrice = $room['price'] * $dias;

        // Verifica disponibilidade
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM reservas WHERE room_id=? AND status!='cancelada' AND ((check_in BETWEEN ? AND ?) OR (check_out BETWEEN ? AND ?))");
        $stmt->execute([$input['room_id'],$input['check_in'],$input['check_out'],$input['check_in'],$input['check_out']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result['count']>0) resposta(false,'Quarto não disponível nessas datas');

        // Insere reserva
        $stmt = $pdo->prepare("INSERT INTO reservas (user_id,room_id,check_in,check_out,total_price,observations,status) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$input['user_id'],$input['room_id'],$input['check_in'],$input['check_out'],$totalPrice,$input['observations'] ?? '','confirmada']);

        $stmt = $pdo->prepare("UPDATE quartos SET status='ocupado' WHERE id=?");
        $stmt->execute([$input['room_id']]);

        resposta(true,'Reserva criada com sucesso',['total_price'=>$totalPrice]);
    }
}

/* ===============================
   CANCELAR RESERVA
=============================== */
if ($action==='cancel_reservation' && $method==='PUT'){
    $stmt = $pdo->prepare("SELECT room_id FROM reservas WHERE id=?");
    $stmt->execute([$input['id']]);
    $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("UPDATE reservas SET status='cancelada' WHERE id=?");
    $stmt->execute([$input['id']]);

    $stmt = $pdo->prepare("UPDATE quartos SET status='disponivel' WHERE id=?");
    $stmt->execute([$reserva['room_id']]);

    resposta(true,'Reserva cancelada com sucesso');
}

/* ===============================
   ESTATÍSTICAS
=============================== */
if ($action==='stats' && $method==='GET'){
    $stats = [];
    $stats['total_rooms'] = $pdo->query("SELECT COUNT(*) AS total FROM quartos")->fetch(PDO::FETCH_ASSOC)['total'];
    $stats['available_rooms'] = $pdo->query("SELECT COUNT(*) AS total FROM quartos WHERE status='disponivel'")->fetch(PDO::FETCH_ASSOC)['total'];
    $stats['occupied_rooms'] = $pdo->query("SELECT COUNT(*) AS total FROM quartos WHERE status='ocupado'")->fetch(PDO::FETCH_ASSOC)['total'];
    $stats['total_reservations'] = $pdo->query("SELECT COUNT(*) AS total FROM reservas WHERE status!='cancelada'")->fetch(PDO::FETCH_ASSOC)['total'];
    $stats['total_clients'] = $pdo->query("SELECT COUNT(*) AS total FROM usuarios WHERE role='cliente'")->fetch(PDO::FETCH_ASSOC)['total'];
    resposta(true,'Estatísticas carregadas',$stats);
}

// fallback
resposta(false,'Ação não encontrada');
?>
