{include file='header.tpl'}

<div class="single-post">
    {* Кнопки: Назад и Удалить *}
    <div class="actions">
        <a href="index.php" class="btn-back">&larr; На главную</a>
        
        <a href="index.php?page=delete_post&id={$post.id}" 
           class="btn-delete" 
           onclick="return confirm('Are you sure you want to delete this post?');">
           Удалить
        </a>
    </div>

    <div class="meta">
        Просмотры: {$post.views} &bull; Дата: {$post.created_at|date_format:"%d.%m.%Y"}
        &bull; Категория: 
        {foreach $post.categories as $c}
            <a href="index.php?page=category&id={$c.id}"><b>{$c.title}</b></a> 
        {/foreach}
    </div>

    <h1>{$post.title}</h1>
    
    {if $post.image}
        <img src="{$post.image}" style="max-height: 500px; object-fit: cover; border-radius: 8px; margin: 20px 0;">
    {/if}
    
    <div class="post-content">
        {$post.content|nl2br}
    </div>
</div>

<h3 style="margin-top: 40px;">Вам также может понравиться:</h3>
<div class="posts-grid">
    {foreach $similar as $s}
        <div class="post-card">
            {if $s.image}
                <img src="{$s.image}">
            {/if}
            <h3>{$s.title}</h3>
            <a href="index.php?page=post&id={$s.id}" class="btn-read">Читать</a>
        </div>
    {/foreach}
</div>

{include file='footer.tpl'}