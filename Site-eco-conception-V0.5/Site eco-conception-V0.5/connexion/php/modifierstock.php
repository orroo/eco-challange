<?php
require_once("session.php");

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function respondWithJson(int $status, array $data): never
{
    http_response_code($status);
    echo json_encode($data, JSON_THROW_ON_ERROR);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respondWithJson(405, ['error' => 'Méthode non autorisée.']);
}

if (!isset($_SESSION['user'])) {
    respondWithJson(401, ['error' => 'Connexion requise.']);
}

$csrfToken = $_POST['csrf_token'] ?? null;
if (
    !is_string($csrfToken)
    || !isset($_SESSION['csrf_token'])
    || !hash_equals($_SESSION['csrf_token'], $csrfToken)
) {
    respondWithJson(403, ['error' => 'Jeton de sécurité invalide. Rechargez la page.']);
}

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$action = $_POST['action'] ?? null;
$delta = match ($action) {
    'plus' => 1,
    'moins' => -1,
    default => null,
};

if ($id === false || $id === null || $id <= 0 || $delta === null) {
    respondWithJson(400, ['error' => 'Demande de modification invalide.']);
}

require_once("ClassMiel.php");

try {
    $miel = new ClassMiel();
    $stock = $miel->changerStock($id, $delta);
} catch (Throwable $error) {
    error_log('Échec de mise à jour du stock : ' . $error->getMessage());
    respondWithJson(500, ['error' => 'Le stock n’a pas pu être enregistré.']);
}

if ($stock === null) {
    respondWithJson(404, ['error' => 'Ce miel n’existe plus. Rechargez la page.']);
}

respondWithJson(200, ['stock' => $stock]);
?>