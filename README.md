# AIのべりすと プロンプト共有

## これはなに？

<https://ai-novelist-share.geo.jp/>

上記サイトのリポジトリです。

## 動作させるために

Apache/MySQL環境を想定しています。
サーバー設定はリポジトリに含んでいないので、.envファイルを追加する必要があります。
最低限、以下の設定を含んだ.envファイルを作成すれば、`php spark serve`で動作確認ができます。

~~~toml
database.default.hostname = {MySQLのホストIP}
database.default.database = {MySQLのDB名}
database.default.username = {MySQLのユーザー名}
database.default.password = {MySQLのパスワード}
database.default.DBDriver = MySQLi

app.sessionDriver = "CodeIgniter\Session\Handlers\DatabaseHandler"
app.sessionSavePath = "ci_sessions"

email.fromEmail = {適当なメールアドレス}
~~~

## 使用素材

アイコン画像は下記サイトで配布されているものを利用しています。

<https://icooon-mono.com/license/>
