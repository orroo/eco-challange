<?php
require_once("ClassDatabase.php");

class ClassMiel {
    private ClassDatabase $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = new ClassDatabase();
        $this->pdo = $this->db->getConnection();
    }

    // Insérer un nouveau miel
    public function addMiel(string $type, string $photo, int $stock): bool {
    $type = trim($type);
    $photo = trim($photo);

    if ($type === '' || $photo === '' || $stock < 0) {
        return false;
    }

    if (strlen($type) > 255 || strlen($photo) > 255) {
        return false;
    }

    $sql = "INSERT INTO miel (type, photo, stock)
            VALUES (:type, :photo, :stock)";

    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
        'type' => $type,
        'photo' => $photo,
        'stock' => $stock
    ]);
}

    // Supprimer un miel à partir de son identifiant
    public function removeMiel(int $id): bool {
        if ($id <= 0) {
            return false;
        }

        $sql = "DELETE FROM miel WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        // true si une ligne a été supprimée
        return $stmt->rowCount() > 0;
    }

    public function getListMiels(): array {
        $sql = "SELECT id, type, photo, stock
        FROM miel
        ORDER BY type";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changerStock(int $id, int $delta): ?int {
        if ($id <= 0 || !in_array($delta, [-1, 1], true)) {
            return null;
        }

        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare(
                "SELECT stock FROM miel WHERE id = :id FOR UPDATE"
            );
            $stmt->execute(['id' => $id]);
            $stock = $stmt->fetchColumn();

            if ($stock === false) {
                $this->pdo->rollBack();
                return null;
            }

            $nextStock = max(0, (int) $stock + $delta);
            $stmt = $this->pdo->prepare(
                "UPDATE miel SET stock = :stock WHERE id = :id"
            );
            $stmt->execute([
                'stock' => $nextStock,
                'id' => $id,
            ]);

            $this->pdo->commit();
            return $nextStock;
        } catch (Throwable $error) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $error;
        }
    }

public function updateMiel(int $id, string $type, string $photo): bool {
    $type = trim($type);
    $photo = trim($photo);

    if ($id <= 0 || $type === '' || $photo === '') {
        return false;
    }

    if (strlen($type) > 255 || strlen($photo) > 255) {
        return false;
    }

    $sql = "UPDATE miel SET type = :type, photo = :photo WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
        'id' => $id,
        'type' => $type,
        'photo' => $photo
    ]);
}


}
?>
