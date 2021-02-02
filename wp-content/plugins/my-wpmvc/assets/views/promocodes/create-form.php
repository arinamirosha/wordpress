<?php

use MyWpmvc\Models\Promocode;

$form_errors = $GLOBALS['form_errors'];
?>

<form action="" method="post" class="alignleft m-b-5 m-t-5">

    <div class="float-left m-r-10">
        <label for="title">Промокод</label>
        <input type="text" id="title" name="title" <?php if ( ! empty($form_errors) ) echo 'value="' . $_POST['title'] . '"'; ?> >

        <?php if ( ! empty($form_errors) && $form_errors['title'] ) : ?>
            <div class="danger text-center"><?php echo $form_errors['title'] ?></div>
        <?php endif; ?>
    </div>

    <div class="float-left m-r-10">
        <label for="discount">Скидка</label>
        <input type="number" id="discount" name="discount" min="0" step="0.1" <?php if ( ! empty($form_errors) ) echo 'value="' . $_POST['discount'] . '"'; ?> >

        <?php if ( ! empty($form_errors) && $form_errors['discount'] ) : ?>
            <div class="danger text-center"><?php echo $form_errors['discount'] ?></div>
        <?php endif; ?>
    </div>

    <div class="float-left m-r-10">
        <label for="type_discount">Тип скидки</label>

        <input type="radio" id="type_discount" name="type_discount" value="<?php echo Promocode::PERCENT ?>"
            <?php if ( ! $_POST['add_new'] || ( ! empty($form_errors) && $_POST['type_discount'] == Promocode::PERCENT) ) echo 'checked'; ?> >%

        <input type="radio" name="type_discount" value="<?php echo Promocode::MONEY ?>"
            <?php if ( ! empty($form_errors) && $_POST['type_discount'] == Promocode::MONEY ) echo 'checked'; ?> >руб.

        <?php if ( ! empty($form_errors) && $form_errors['type_discount'] ) : ?>
            <div class="danger text-center m-t-10"><?php echo $form_errors['type_discount'] ?></div>
        <?php endif; ?>
    </div>

    <div class="float-left">
        <input type="hidden" name="add_new">
        <button class="button" type="submit">Добавить</button>
    </div>

</form>