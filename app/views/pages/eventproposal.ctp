<div id="main">
  <div class="section">
    <h2 id="content_1_1">events.php.gr.jpの提供</h2>
    <p>日本 PHP ユーザ会では、PHPの普及・促進に貢献する草の根的なユーザ活動支援の一環として、イベント管理システム<a href="http://events.php.gr.jp">events.php.gr.jp</a>の利用提供を行っております。</p>
        <p>イベント開催者は日本 PHP ユーザ会へ申請を行うことで、<a href="http://events.php.gr.jp">events.php.gr.jp</a>のイベント告知/管理ページを利用することができます。</p>
        <p><a href="http://events.php.gr.jp">events.php.gr.jp</a>では</p>
        <ul>
            <li>イベント告知/管理ページの提供</li>
            <li>参加者の管理</li>
            <li>日本 PHP ユーザ会のRSSを通じてのイベントページ配信</li>
        </ul>
        <p>などの機能が提供されます。</p>
  
    <h3>イベントの申請方法</h3>
        <p>イベントの申請は、日本 PHP ユーザ会のメーリングリスト <a href="http://ml.php.gr.jp/mailman/listinfo/phpug-admin">phpug-admin</a> にて受け付けております。</p>
        <p>開催申請の際には以下のフォーマットに従い必要条項を記入の上、件名を<strong>「イベント開催申請: (ご自分のお名前)」</strong>として<a href="http://ml.php.gr.jp/mailman/listinfo/phpug-admin">phpug-admin</a> のMLまでお送りください。</p>
        <p>申請された内容に関して、日本 PHP ユーザ会にて特に問題がないと判断した場合には、申請内容に従ってイベントページが作成されます。</p>
        <pre class="template">
---------------
イベント名:
イベント開催日時:
イベント終了日時:
告知開始日時:
募集開始日時:
イベント申込締切日時:
募集人数:
イベント概要(できるだけ詳細に):

開催者名:
連絡先メールアドレス: 
---------------
(※開催者名、メールアドレスはユーザ会側での管理に利用され、イベントページでは公開されません。)</pre>
        <p class="notice">なお、日本 PHP ユーザ会は運営の専属団体が存在するわけではなく、善意の協力者のみによって運営されております。そのため、申請に対して即時の返答が得られない場合があります。</p>

		<h3>events.php.gr.jpの利用方法</h3>
        <p>イベントの申請が承認されると、<a href="http://events.php.gr.jp">events.php.gr.jp</a>内に申請内容に従ってイベント概要が記述されたイベント用Wikiページが開設されます。</p>
        <p>ここでは、<a href="http://events.php.gr.jp">events.php.gr.jp</a>のイベント用Wikiページの基本的な利用方法を解説いたします。</p>

			<h4>1.OpenIDによるログイン</h4>
				<p>events.php.gr.jpでは、OpenIDを使った認証をおこなっています。以下のサービスのいずれかにアカウントをお持ちであれば、そのアカウントのユーザをevents.php.gr.jpでそのまま利用できます。</p>
				<ul>
					<li><a href="http://www.sixapart.com/typekey/">TypeKey(http://www.sixapart.com/typekey/)</a></li>
					<li><a href="http://www.hatena.ne.jp/">はてな(http://www.hatena.ne.jp/)</a></li>
					<li><a href="http://mixi.jp/">mixi(http://mixi.jp/)</a></li>
				</ul>
				<p>(なお、events.php.gr.jp独自のアカウントは存在しません。このシステムを利用するには上記のいずれかのOpenIDが必ず必要になります。)</p>

				<p>イベントへ参加登録するには、まずはOpenIDを使ってログインする必要があります。ページ上部の[Login]ボタンを押すと、「OpenIDによるログイン」画面が表示されます。</p>
				
				<div><img src="/img/proposal/fig01.png"></div>

				<p>以下に、各OpenIDのログイン方法を簡単に解説します。</p>

				<h5>TypeKeyのアカウントでログインする</h5>
				<p>1.「TypeKeyでログイン」にある入力欄にユーザ名を入力し、[Login]ボタンを押す</p>
				<div><img src="/img/proposal/fig02.png"></div>
				<p>2. TypeKeyのログイン画面が表示されるので、ユーザ名・パスワードを入力し、[Sign In]ボタンを押す</p>
				<div><img src="/img/proposal/fig03.png"></div>

				<h5>はてなのアカウントでログインする</h5>
				<p>1.「はてなでログイン」にある入力欄にユーザ名を入力し、[はてなでlogin]ボタンを押す</p>
				<div><img src="/img/proposal/fig04.png"></div>
				<p>2．「OpenIDでのログイン確認」画面が表示されるので、[今回のみ許可]ボタンを押す。</p>
				<P>(今後この行程を省略する場合には、[常に許可]を押してください。)</p>
				<div><img src="/img/proposal/fig05.png"></div>

				<h5>mixiのアカウントでログインする</h5>
				<p>1.「mixiでログイン」にある[mixiでlogin]ボタンを押す</p>
				<div><img src="/img/proposal/fig06.png"></div>
				<p>2．mixiのログイン画面が表示されるので、e-mail・パスワードを入力し、[ログイン]ボタンを押す</p>
				<div><img src="/img/proposal/fig07.png"></div>
				<p>3．「mixi OpenID利用同意」画面が表示されるので、[今回は同意する]ボタンを押す。</p>
				<div><img src="/img/proposal/fig08.png"></div>

			<h4>2.ニックネームの登録</h4>
				<p>初めてevents.php.gr.jpにログインすると、ニックネームを登録する画面が表示されますので、希望するニックネームを入力し、[Submit]ボタンを押してください。</p>
				<div><img src="/img/proposal/fig09.png"></div>
				<p>ここで登録されたニックネームは、イベントの参加者一覧やコメント一覧で使用されます。</p>

			<h4>3.イベントの参加登録</h4>
				<p>ニックネームの登録が完了したら、次にイベントの参加登録を行います</p>
				<p>ログイン後に個別のイベントページへ進むと、[参加メンバー一覧]の下に、[イベントに参加する]と書かれた参加登録用フォームが表示されます。このフォームに<strong>コメントと懇親会への参加可否を入力</strong>し、[Join]を押します。これで、イベントへの参加登録は完了です。</p>
				<p>また、[コメント一覧]の下にコメント登録用のフォームがあります。イベント主催者などの連絡はこちらをご利用ください。</p>

			<h4>4.イベント用Wikiの編集</h4>
        <p>イベントへの参加登録を行うと、イベント用Wikiページの編集が可能になります。イベント参加者がログインすると、イベント用Wikiページのヘッダー部分に、[Wikiページを編集する]というボタンが利用可能になり、こちらからWikiページが編集できます。</p>
        <p>イベント用Wikiページの開設時には内容は一切記載されていません。ここで編集した内容は、作成時に申請したイベント概要と[参加メンバー一覧]の間に追加されます。この欄には主に、開催するイベントの発表内容等を記入します。Wikiの書式に関しては、編集ページ下部を参考にしてください。</p>
        <p>※なお、<a href="http://events.php.gr.jp">events.php.gr.jp</a>のイベントシステムでは、基本的にイベント開催者も一参加者として扱われ、特別な権限等はなく、Wikiの編集権限は参加者全員に与えられます。そのため、<strong>イベント申請を行った主催者・および発表者でも、イベント用Wikiの編集を行うにはイベントへの参加登録を行う必要があります。</strong></p>

			<h4>5.登録後のイベント/懇親会への参加・不参加の変更</h4>
        <p>イベントや懇親会への参加登録後、参加が不可能になった場合はキャンセルすることができます。また、イベント登録の際に懇親会へ不参加として登録していた場合も、これを変更することができます。</p>
				<p>イベントと懇親会のキャンセルは、いずれも個別のイベントページから行います。</p>

        <p>イベントへの参加をキャンセルする場合、[参加メンバー一覧]内の自分のアカウント右側ある[キャンセル]リンクをクリックします。</p>
				<p>懇親会への参加をキャンセルする場合、[キャンセル]の隣に表示されている[懇親会のみ辞退]のリンクをクリックします。</p>
				<p>懇親会への参加を希望する場合、同じ個所にある[懇親会に追加参加]のリンクをクリックします。</p>

			<h4>付記.イベント参加の注意･マナー</h4>
        <p>イベントの参加は、安易な参加・キャンセルの繰り返しを防ぐため、<strong>一度キャンセルすると2度と復帰できない</strong>仕様になっています。安易な参加とキャンセルが続くと主催者や他の参加者・参加希望者の方にも迷惑になるため、参加登録はきちんと参加する意思がある場合のみに留めるよう、お願いいたします。</p>

    <h3>イベント申請の注意事項</h3>
        <p><a href="http://events.php.gr.jp">events.php.gr.jp</a>は、PHPユーザによる活動の普及・促進を主な目的としております。</p>
        <p>そのため、以下のような内容のイベントに関しては、協議の上イベントの申請が却下されることがあります。</p>
        <ul>
            <li>PHP言語およびWebアプリケーション構築技術と関連のないもの</li>
            <li>収益・宣伝を目的としたもの</li>
            <li>企業スポンサーによるセミナー(会場提供はこの限りに含まれません)</li>
            <li>日本 PHP ユーザ会で実態を把握できないもの</li>
        </ul>
        <p>その他、不明な点に関しては<a href="http://ml.php.gr.jp/mailman/listinfo/phpug-admin">phpug-admin</a> のMLにてご質問ください。</p>

  </div>
</div>
