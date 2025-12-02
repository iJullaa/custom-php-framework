<?php
namespace App\Model;

use App\Service\Config;

class Product
{
    private ?int $id = null;
    private ?string $name = null;
    private ?float $price = null;
    private ?string $description = null;

    public static function fromArray(array $array): Product
    {
        $product = new self();
        $product->fill($array);
        return $product;
    }

    public function fill(array $array): void
    {
        if (isset($array['id']) && !$this->id) {
            $this->id = $array['id'];
        }
        if (isset($array['name'])) {
            $this->name = $array['name'];
        }
        if (isset($array['price'])) {
            $this->price = (float)$array['price'];
        }
        if (isset($array['description'])) {
            $this->description = $array['description'];
        }
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM product';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $products = [];
        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $products[] = self::fromArray($row);
        }
        return $products;
    }

    public static function find($id): ?Product
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM product WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? self::fromArray($row) : null;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (!$this->id) {
            $sql = "INSERT INTO product (name, price, description) VALUES (:name, :price, :desc)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['name' => $this->name, 'price' => $this->price, 'desc' => $this->description]);
            $this->id = $pdo->lastInsertId();
        } else {
            $sql = "UPDATE product SET name = :name, price = :price, description = :desc WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['name' => $this->name, 'price' => $this->price, 'desc' => $this->description, 'id' => $this->id]);
        }
    }

    public function delete(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM product WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $this->id]);
        $this->id = null;
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function getPrice(): ?float { return $this->price; }
    public function getDescription(): ?string { return $this->description; }
}