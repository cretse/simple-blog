{include file='header.tpl'}

<div class="cat-header">
    <h1>{$category.title}</h1>

    <div>
        Сортировка по:
        <a href="index.php?page=category&id={$category.id}&sort=date">Дата</a> | 
        <a href="index.php?page=category&id={$category.id}&sort=views">Просмотры</a>
    </div>

</div>

<div class="posts-grid">

    {foreach $posts as $post}

        <div class="post-card">
            {if $post.image}
                <img src="{$post.image}" alt="Img">
            {/if}
            <h3>{$post.title}</h3>
            <p>{$post.description}</p>
            <a href="index.php?page=post&id={$post.id}" class="btn-read">Подробнее</a>
        </div>
        
    {foreachelse}
        <p>Нет постов в этой категории.</p>
    {/foreach}

</div>

{include file='footer.tpl'}