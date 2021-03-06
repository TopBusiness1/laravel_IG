<?php if(isset($options['additional_button'])){?>
        <?= Form::button($options['additional_button']['text'],$options['additional_button']['options']);?>
<?php } ?>
<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    <div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
<?php endif; ?>



    <div class="form-line">

<?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
    <?= Form::customLabel($name, $options['label'], $options['label_attr']) ?>
<?php endif; ?>

<?php if ($showField): ?>

    <?= Form::textarea($name, $options['value'], $options['attr']) ?>

    <?php include 'help_block.php' ?>
<?php endif; ?>

<?php include 'errors.php' ?>

    </div>

<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    </div>
    <?php endif; ?>

    <?php if(isset($options['note'])): ?>
        <small class="field-info form-text">
            <?php echo ($options['note']);?>
        </small>
    <?php endif;?>

<?php endif; ?>
