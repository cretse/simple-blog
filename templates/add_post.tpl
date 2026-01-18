{include file='header.tpl'}

<div class="form-container">
    <h1>Создать новый пост</h1>

    <form action="index.php?page=add_post" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>Заголовок</label>
            <input type="text" name="title" required placeholder="Введите заголовок">
        </div>

        <div class="form-group">
            <label>Категория</label>
            <select name="category_id">
                {foreach $categories as $cat}
                    <option value="{$cat.id}">{$cat.title}</option>
                {/foreach}
            </select>
        </div>

        <div class="form-group">
            <label>Подзаголовок</label>
            <textarea name="description" rows="3" required placeholder="Введите подзаголовок"></textarea>
        </div>

        <div class="form-group">
            <label>Контент</label>
            <textarea name="content" rows="8" required placeholder="Введите текст"></textarea>
        </div>

        <div class="form-group">
            <label>Обложка</label>
            <input type="file" name="image" accept="image/*">
        </div>
        
        <button type="submit" class="btn-submit">Опубликовать</button>
    </form>

</div>

{include file='footer.tpl'}