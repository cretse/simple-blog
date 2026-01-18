<?php
require_once 'DB.php';

class Blog {
    
    //Категории
    public function getCategories() {
        return DB::conn()->query("SELECT * FROM categories")->fetchAll();
    }

    //Главная страница
    public function getHomeData() {
        $categories = $this->getCategories();
        foreach ($categories as &$cat) {
            $stmt = DB::conn()->prepare("
                SELECT p.* FROM posts p
                JOIN category_post cp ON p.id = cp.post_id
                WHERE cp.category_id = ?
                ORDER BY p.created_at DESC LIMIT 3
            ");
            $stmt->execute([$cat['id']]);
            $cat['posts'] = $stmt->fetchAll();
        }
        return $categories;
    }

    //Получить 1 категорию и список постов
    public function getCategory($id) {
        $stmt = DB::conn()->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getPostsByCategory($catId, $sort = 'date') {
        $orderBy = ($sort === 'views') ? 'p.views DESC' : 'p.created_at DESC';
        $stmt = DB::conn()->prepare("
            SELECT p.* FROM posts p
            JOIN category_post cp ON p.id = cp.post_id
            WHERE cp.category_id = ? ORDER BY $orderBy
        ");
        $stmt->execute([$catId]);
        return $stmt->fetchAll();
    }

    //Получить один пост
    public function getPost($id) {
        DB::conn()->prepare("UPDATE posts SET views = views + 1 WHERE id = ?")->execute([$id]);
        $stmt = DB::conn()->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        $post = $stmt->fetch();
        if ($post) {
            $catStmt = DB::conn()->prepare("SELECT c.* FROM categories c JOIN category_post cp ON c.id = cp.category_id WHERE cp.post_id = ?");
            $catStmt->execute([$id]);
            $post['categories'] = $catStmt->fetchAll();
        }
        return $post;
    }

    //Похожие посты
    public function getSimilarPosts($postId) {
        $stmt = DB::conn()->prepare("
            SELECT DISTINCT p.* FROM posts p
            JOIN category_post cp ON p.id = cp.post_id
            WHERE cp.category_id IN (SELECT category_id FROM category_post WHERE post_id = ?)
            AND p.id != ? ORDER BY RAND() LIMIT 3
        ");
        $stmt->execute([$postId, $postId]);
        return $stmt->fetchAll();
    }

    //Создание поста
    public function createPost($title, $desc, $content, $categoryId, $file) {
        $imagePath = NULL;      
        if ($file && isset($file['tmp_name']) && $file['error'] === 0) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $fileName = time() . '_' . rand(1000, 9999) . '.' . $ext;
                $uploadDir = 'uploads/';
                
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
                    $imagePath = $uploadDir . $fileName;
                }
            }
        }

        $pdo = DB::conn();
        $stmt = $pdo->prepare("INSERT INTO posts (title, description, content, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $desc, $content, $imagePath]);
        $postId = $pdo->lastInsertId();
        $pdo->prepare("INSERT INTO category_post (post_id, category_id) VALUES (?, ?)")->execute([$postId, $categoryId]);
        return $postId;
    }

    //Удаление поста
    public function deletePost($id) {
        $pdo = DB::conn();
        $stmt = $pdo->prepare("SELECT image FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        $post = $stmt->fetch();

        if ($post && !empty($post['image']) && file_exists($post['image'])) {
            if (strpos($post['image'], 'http') === false) {
                unlink($post['image']); 
            }
        }

        $pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$id]);
    }
}