<div class="<?php echo $class ?>">
    <?php foreach ($elements as $element): ?>
        <?php echo $element->render(); ?>
    <?php endforeach; ?>
</div>