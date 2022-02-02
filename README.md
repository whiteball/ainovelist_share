# AIのべりすと プロンプト共有(仮)

## これはなに？

https://ai-novelist-share.geo.jp/

上記サイトのリポジトリです。

## 動作させるために

Apache/MySQL環境を想定しています。
サーバー設定はリポジトリに含んでいないので、.envファイルを追加する必要があります。
最低限、以下の設定を含んだ.envファイルを作成すれば、`php spark serve`で動作確認ができます。

~~~
database.default.hostname = {MySQLのホストIP}
database.default.database = {MySQLのDB名}
database.default.username = {MySQLのユーザー名}
database.default.password = {MySQLのパスワード}
database.default.DBDriver = MySQLi

app.sessionDriver = "CodeIgniter\Session\Handlers\DatabaseHandler"
app.sessionSavePath = "ci_sessions"
~~~
