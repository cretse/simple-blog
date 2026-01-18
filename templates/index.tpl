{include file='header.tpl'}

{foreach $categories_with_posts as $cat}

    {if $cat.posts}
    <div class="category-section">
        <div class="cat-header">
            <h2>{$cat.title}</h2>
            <a href="index.php?page=category&id={$cat.id}">Смотреть все</a>
        </div>
        
        <div class="posts-grid">
            {foreach $cat.posts as $post}
                <div class="post-card">
                    {if $post.image}
                         <img src="{$post.image}" alt="Img">
                    {/if}
                    <h3>{$post.title|truncate:40}</h3>
                    <p>{$post.description|truncate:100}</p>
                    <a href="index.php?page=post&id={$post.id}" class="btn-read">Подробнее</a>
                </div>
            {/foreach}
        </div>
    </div>
    {/if}
{foreachelse}
    <div style="text-align:center; margin-top:50px;">
        <h2>Добро пожаловать!</h2>
        <p>Постов нет</p>
    </div>
{/foreach}

{include file='footer.tpl'}