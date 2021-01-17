<?php

/** @var ModelNew[] $news */
?>
<h2 id="responsive-containers">Новости</h2>

<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">title</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($news as $new) { ?>
        <tr>
            <th scope="row"><?=$new->id?></th>
            <td><a href="/<?=$new->id?>"><?=htmlspecialchars($new->title)?></a></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
