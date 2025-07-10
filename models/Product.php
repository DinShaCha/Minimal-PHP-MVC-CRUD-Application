<?php

namespace app\models;

use app\Database;
use app\helpers\StringHelper;

class Product
{
    public $id = null;
    public $title = null;
    public $description = null;
    public $price = null;
    public $imagePath = null;
    public $imageFile = null;

    public function load($data)
    {
        $this->id = $data['id'] ?? null;
        $this->title = $data['title'];
        $this->description = $data['description'] ?? '';
        $this->price = $data['price'];
        $this->imagePath = $data['imagePath'] ?? null;
        $this->imageFile = $data['imageFile'] ?? null;
    }

    public function save()
    {

        $errors = [];

        if (empty($this->title)) {
            $errors[] = "The title is required";
        }

        if (empty($this->price)) {
            $errors[] = "The price is required";
        }

        if (!is_dir(__DIR__ . '/../public/images')) {
            mkdir(__DIR__ . '/../public/images');
        }

        if (empty($errors)) {

            if ($this->imageFile && $this->imageFile['tmp_name']) {

                if (!empty($this->imagePath)) {
                    unlink(__DIR__ . '/../public/' . $this->imagePath);
                }

                $this->imagePath = 'images/' . StringHelper::randomString(8) . '/' . $this->imageFile['name'];
                mkdir(dirname(__DIR__ . '/../public/' . $this->imagePath));
                move_uploaded_file($this->imageFile['tmp_name'], __DIR__ . '/../public/' . $this->imagePath);
            }

            $databaseObject = Database::getInstance();

            if(!empty($this->id))
            {
                $databaseObject->updateProduct($this);
            }else{
                $databaseObject->createProduct($this);
            }
        }
        return $errors;
    }
}
