<p>
このOpenIDアカウントで登録されている情報がなかったので新規作成を行います。ニックネームを入力して送信ボタンを押してください。
</p>
<p>
<strong>すでに何度か利用している方でこのメッセージが表示された場合はアカウント作成を行う前に別のOpenIDサービスでのログインを試してみてください。古くからの利用者の場合はTypeKeyでログインした後、ユーザ設定で別のOpenIDサービスを指定すると今までのデータをひきついだ状態で別の認証サービスを利用する事ができます。</strong>
</p>
<?php
  echo $form->create('User', array('action' => 'openid_add'));
  echo $form->input('nickname');
  echo $form->submit();
  echo $form->end();
?>
