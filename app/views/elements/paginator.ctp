<?php if (isset($is_mobile) && $is_mobile): ?>

<p id='pagination'>
<?php echo $paginator->prev('<< '.__('[*]前へ', true),
    array('accesskey' => '*'),
    null,
    array('class'=>'disabled', 'tag' => 'span')
); ?>
 | 
<?php echo $paginator->next(__('[#]次へ', true).' >>', array('accesskey' => '#', 'rel' => 'next'), null, array('tag' => 'span', 'class' => 'disabled'));
?> 
</p>

<?php else: ?>

<p id='pagination'>
<?php echo $paginator->prev('<< '.__('previous', true),
    array(),
    null,
    array('class'=>'disabled', 'tag' => 'span')
); ?>
 | 
<?php echo $paginator->numbers().
' | '.
$paginator->next(__('next', true).' >>', array('rel' => 'next'), null, array('tag' => 'span', 'class' => 'disabled'));
?> 
</p>

<?php endif ?>
