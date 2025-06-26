<?php


namespace app\controllers;

use app\models\Product;
use app\Router;

class ProductController
{
    public function index(Router $router)
    {
        $search = $_GET['search'] ?? '';
        $products = $router->database->getAllProducts($search);
        echo $router->renderView('/products/index.twig', compact('products'));
    }

    public function create(Router $router)
    {
        $errors = [];
        $product = [
            'title' => '',
            'description' => '',
            'price' => '',
            'imageFile' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'imageFile' => $_FILES['image']
            ];

            $prod = new Product();
            $prod->load($product);
            $errors = $prod->save();
            if (empty($errors)) {
                header("Location: /products");
                exit;
            }
        }

        echo $router->renderView('/products/create.twig', compact('errors', 'product'));
    }

    public function update(Router $router)
    {
        $id = $_GET['id'] ?? null;

        if (empty($id)) {
            header("Location: /products");
            exit;
        }

        $errors = [];
        $product = $router->database->getProductsById($id);
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = [
                'id' => $id,
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'imageFile' => $_FILES['image']
            ];

            $prod = new Product();
            $prod->load($product);
            $errors = $prod->save();
            if (empty($errors)) {
                header("Location: /products");
                exit;
            }
        }

        echo $router->renderView('/products/update.twig', compact('errors', 'product'));
    }

    public function delete(Router $router)
    {

        $id = $_POST['id'] ?? null;

        if (empty($id)) {
            header("Location: /products");
            exit;
        }

        $router->database->deleteProduct($id);
        header("Location: /products");
    }
}
