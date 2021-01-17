<?php

/** @var ModelNew[] $news */
?>
<h2 id="responsive-containers">Администрирование новостей</h2>
<p>
    <a href="/admin/add">Создать новость</a>
</p>

<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">title</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($news as $new) { ?>
        <tr>
            <th scope="row"><?=$new->id?></th>
            <td><a href="/<?=$new->id?>"><?=htmlspecialchars($new->title)?></a></td>
            <td>
                <a href="/admin/edit/<?=$new->id?>">изменить</a>
                <a href="/admin/delete/<?=$new->id?>">удалить</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
