<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: planos.php');
    exit();
}

extract($_SESSION['userData']);

if (isset($_GET['plano'], $_GET['type'], $_GET['total'])) {
    $valor = $_GET['total'];

    switch ($_GET['plano']) {
        case "1":
            $nome = "Optimização Completa";
            break;
        case "2":
            $nome = "High Pack";
            break;
        case "3":
            $nome = "Medium Pack";
            break;
        case "4":
            $nome = "Pack Movement High tps";
            break;
        case "5":
            $nome = "White Bullet";
            break;
        default:
            header('Location: pagamento.php?plano=' . $_GET['plano']);
            exit();
    }

    switch ($_GET['type']) {
        case "1":
            $pagamento = "Paypal";
            break;
        case "2":
            $pagamento = "Mbway";
            break;
        case "3":
            $pagamento = "PaySafeCard";
            break;
        default:
            header('Location: app-pagamento.php?plano=' . $_GET['plano']);
            exit();
    }

    $estado = 'A verificar se estás no nosso discord';

    addTicket($discord_id, $nome, $valor, $estado, $pagamento);

} else {
    header('Location: app-pagamento.php?plano=' . $_GET['plano']);
    exit();
}

function addTicket($discord_id, $pacote, $custo, $estado, $pagamento) {
    $servername = "localhost";
    $username = "kripsfps_root";
    $password = "AWwKDI[MG&#-";
    $database = "kripsfps_db"; 

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $check_sql = "SELECT discordid FROM users WHERE discordid = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $discord_id);
    $check_stmt->execute();
    $check_stmt->store_result();
    $count = $check_stmt->num_rows;
    $check_stmt->close();

    if ($count > 0) {
        $insert_sql = "INSERT INTO tickets (discordid, pacote, custo, estado, pagamento) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("issss", $discord_id, $pacote, $custo, $estado, $pagamento);

        if ($insert_stmt->execute() === TRUE) {
            $conn->close();
            header('Location: cliente.php');
            exit();
        } else {
            echo "Error: " . $insert_stmt->error;
        }

        $insert_stmt->close();
    } else {
        echo "User with the specified Discord ID does not exist.";
    }

    $conn->close();

    header('Location: cliente.php');
    exit();
}
?>
