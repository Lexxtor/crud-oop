<?php
/**
 * @var ModelNew $new
 * @var string $title
 */
?>
<div class="container">
    <h3><?=$title?></h3>
    <?php foreach ($new->getErrors() as $error) { ?>
        <p class="text-danger"><?=$error?></p>
    <?php } ?>
    <form method="post">
        <div class="mb-3">
            <label for="title" class="form-label">Заголовок</label>
            <input name="title" class="form-control" id="title" placeholder="" value="<?=htmlspecialchars($new->title)?>">
        </div>
        <div class="mb-3">
            <label for="text" class="form-label">Текст</label>
            <textarea name="text" class="form-control" id="text" rows="4"><?=htmlspecialchars($new->text)?></textarea>
        </div>
        <input class="btn btn-primary" type="submit" value="Сохранить">
    </form>
</div>
