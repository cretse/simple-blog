<?php

require 'src/DB.php';

$pdo = DB::conn();

//Очистка данных
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$pdo->exec("TRUNCATE TABLE category_post");
$pdo->exec("TRUNCATE TABLE posts");
$pdo->exec("TRUNCATE TABLE categories");
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

//Создание категорий
$categoriesData = [
    'IT' => 'Новости из мира IT',
    'Образ жизни' => 'Ежедневная рутина, здоровье, спорт',
    'Путешествия' => 'Про наш прекрасный мир',
    'Еда' => 'Вкусные рецепты'
];

$catIds = [];

foreach ($categoriesData as $title => $desc) {
    $stmt = $pdo->prepare("INSERT INTO categories (title, description) VALUES (?, ?)");
    $stmt->execute([$title, $desc]);
    $catIds[] = $pdo->lastInsertId();
}

//Создание постов

//Заглушка
$localImage = 'uploads/ranni.jpg';
//Заглушка

for ($i = 1; $i <= 20; $i++) {
    $title = "Тестовая статья №$i";
    $desc = "Подзаголовок, краткое описание $i.";
    
    $content = "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis incidunt obcaecati iste facere, quas, porro iure voluptates quaerat laboriosam commodi in, ipsa odio voluptatibus fugit id laudantium voluptatem doloribus! Sit.";
               
    $stmt = $pdo->prepare("INSERT INTO posts (title, description, content, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $desc, $content, $localImage]);
    $postId = $pdo->lastInsertId();

    $randCat = $catIds[array_rand($catIds)];
    $pdo->prepare("INSERT INTO category_post (post_id, category_id) VALUES (?, ?)")->execute([$postId, $randCat]);
}

echo "База данных заполнена";
