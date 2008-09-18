vim: fenc=utf-8:

* アプリケーションの初期設定

defaulg ファイルをコピーしてネ！
- app/config/
 - core.php.default -> core.php
  - (開発時は debug とかを 2 とかに)
  - ドキュメントルート以外で開発する場合は注意が必要？(未検証ですが)
 - database.php.default -> database.php

* ディレクトリの書き込み権限
 
- tmp に apache が書き込めるように
 - ex.) chmod -R 777 tmp/

* DB の設定

dbsディレクトリを777に。
event.dbを666にしてください。
