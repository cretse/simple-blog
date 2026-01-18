<?php
use Smarty\Smarty; 

require 'vendor/autoload.php';
require 'src/Blog.php';

$smarty = new Smarty();
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('templates_c');

$blog = new Blog();
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        $smarty->assign('categories_with_posts', $blog->getHomeData());
        $smarty->display('index.tpl');
        break;

    case 'category':
        $id = $_GET['id'] ?? 0;
        $sort = $_GET['sort'] ?? 'date';
        $smarty->assign('category', $blog->getCategory($id));
        $smarty->assign('posts', $blog->getPostsByCategory($id, $sort));
        $smarty->display('category.tpl');
        break;

    case 'post':
        $id = $_GET['id'] ?? 0;
        $smarty->assign('post', $blog->getPost($id));
        $smarty->assign('similar', $blog->getSimilarPosts($id));
        $smarty->display('post.tpl');
        break;

    case 'add_post':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $blog->createPost(
                $_POST['title'], 
                $_POST['description'], 
                $_POST['content'], 
                $_POST['category_id'], 
                $_FILES['image'] ?? null
            );
            header('Location: index.php');
            exit;
        }
        $smarty->assign('categories', $blog->getCategories());
        $smarty->display('add_post.tpl');
        break;

    case 'delete_post':
        $id = $_GET['id'] ?? 0;
        if ($id) {
            $blog->deletePost($id);
        }
        header('Location: index.php');
        exit;
        break;

    default:
        echo "404 не найдено";
}