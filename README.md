# Egg MicroCMS
EggでテキトーなMicroCMSブログを作りました。
前提として、MicroCMSのテンプレートでブログを作成していることが前提です。

## 使い方
以下の手順で、ローカル環境で動かすことができます。

### 1. リポジトリをクローン
とりあえず、リポジトリをクローンして下さい。
```bash
git clone https://github.com/takemo101/egg-microcms.git
```

### 2. パッケージをインストール
リポジトリのディレクトリに移動して、phpのパッケージをインストールして下さい。
```bash
composer install
```
もしも、PHP環境がない場合は、以下コマンドでDockerComposeを使ってインストールして下さい。
```
make install
```

### 3. .envファイルを作成
.envファイルを作成して、環境変数を設定して下さい。
```bash
make setup
```
.envには、以下MicroCMSの環境変数を設定して下さい。
```properties
MICROCMS_DOMAIN=Apiのサブドメイン
MICROCMS_API_KEY=Apiのキー
```

### 4. マイグレーションを実行
マイグレーションを実行して、テーブルを作成して下さい。
```bash
php command cycle:migrate
```

### 5. MicroCMSのデータをインポート
MicroCMSのデータをデータベースにインポートして下さい。
```bash
php command seed:blog
```
環境立ち上げ後は、普通に```http://localhost/```にアクセスして完了です！

### その他
PHP環境がない場合は、以下コマンドでDockerComposeを使って環境を立ち上げてください。
```bash
make start
```
環境を停止する場合は、以下コマンドを実行してください。
```bash
make stop
```
