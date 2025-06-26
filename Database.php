<?php

namespace app;

use app\models\Product;
use PDO;
use PDOException;

class Database
{
    private $host = 'localhost';
    private $port = 3307;
    private $dbname = 'basic_crud_1';
    private $user = 'root';
    private $pwd = '';

    private static $instance = null;
    private $pdo;

    public function __construct()
    {
        try{
            $this->pdo = new PDO("mysql:host=".$this->host.";port=".$this->port.";dbname=".$this->dbname, $this->user, $this->pwd);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e)
        {
            die("Connection Failed: ".$e->getMessage());
        }
    }

    public static function getInstance()
    {
        if(self::$instance === null)
        {
            self::$instance = new Database;
        }

        return self::$instance;
    }

    public function getAllProducts($search = '')
    {

        if (!empty($search)) {
            $statement = $this->pdo->prepare("SELECT * FROM products WHERE title LIKE :title ORDER BY created_date DESC;");
            $statement->bindValue(":title", "%$search%");
        } else {
            $statement = $this->pdo->prepare('SELECT * FROM products ORDER BY created_date DESC;');
        }

        $statement->execute();
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $products;
    }

    public function createProduct(Product $product)
    {
        $created_date = date('Y:m:d H:i:s');

        $statement = $this->pdo->prepare("INSERT INTO products(title,description,image,price,created_date) VALUES(:title,:description,:image,:price,:created_date);");
        $statement->bindValue(':title', $product->title);
        $statement->bindValue(':image', $product->imagePath);
        $statement->bindValue(':description', $product->description);
        $statement->bindValue(':price', $product->price);
        $statement->bindValue(':created_date', $created_date);
        $statement->execute();
    }

    public function updateProduct(Product $product)
    {
        
        $statement = $this->pdo->prepare("UPDATE products SET title = :title, image = :image, description = :description, price = :price WHERE id = :id");
        $statement->bindValue(':id', $product->id);
        $statement->bindValue(':title', $product->title);
        $statement->bindValue(':image', $product->imagePath);
        $statement->bindValue(':description', $product->description);
        $statement->bindValue(':price', $product->price);
        $statement->execute();
    }

    public function getProductsById($id){
        $statement = $this->pdo->prepare("SELECT * FROM products WHERE id = :id;");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $product = $statement->fetch(PDO::FETCH_ASSOC);

        return $product;
    }

    public function deleteProduct($id)
    {

        $statement = $this->pdo->prepare('DELETE FROM products WHERE id = :id;');
        $statement->bindValue(':id', $id);
        $statement->execute();
    }
}