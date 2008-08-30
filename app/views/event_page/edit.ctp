<h2>Event Wiki Page</h2>
<div>
<?php echo $page['EventPage']['html']; ?>
</div>

<div>
<?php echo $form->create('EventPage', array('type' => 'post', 'action' => 'edit')); ?>
<?php echo $form->hidden('EventPage.event_id', $event_id); ?>
<?php echo $form->textarea('EventPage.content', array('cols' => '50', 'rows' => '9')); ?>
<?php echo $form->submit('preview', array('name' => 'preview')); ?>
<?php echo $form->end('submit'); ?>
</div>

<div>
<?php $help = <<<EOD
**見出し
 *h3
 **h4
 ***h5
 ****h6
*h3
**h4
***h5
****h6

**リスト
 -list
 -list2
 --list2-1
 --list2-2
-list
-list2
--list2-1
--list2-2

**番号付きリスト
 +olist
 +olist2
 ++olist2-1
 ++olist2-2
+olist
+olist2
++olist2-1
++olist2-2

**引用
 >引用文
 >example
>引用文
>example

**pre
頭にスペースを入れる
 def takahashi
   'takahashi'
 end

**定義語
 :apple|リンゴ
 :orange|オランゲ
:apple|リンゴ
:orange|オランゲ

**リンク
 [[example:http://example.com]]
[[example:http://example.com]]
EOD;
echo $pukiwiki->toHtml($help);
?>
</div>
