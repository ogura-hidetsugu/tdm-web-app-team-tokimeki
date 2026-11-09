# tdm-web-app-team-tokimeki
鳥取県デジタル人材育成プログラム 修了制作｜ときめきパワーアップガールズ ～今日もかわいくレベルアップ～

## アプリについて

アプリ名：クラスト（正式表記はチームで確認）。家族で買い物・在庫情報を共有するアプリを開発します。
現在はLaravelの初期構成を準備した段階で、商品管理や家族共有などの業務機能は未実装です。

## 初期構成と確認状況

2026年11月10日、小倉のWindows環境で以下を確認しました。

| 項目 | 内容 |
|---|---|
| Laravel | 12.71.0（composer.lockに記録された依存関係を使用） |
| PHP（確認時） | 8.2.12／XAMPPのCLI版。公開前にセキュリティ修正版への更新を確認する |
| Composer（確認時） | 2.11.1 |
| Git（確認時） | 2.55.0.windows.5 |
| 初期確認用DB | SQLite。製品用DBの選定とは別で、PostgreSQL案は未確定 |
| 動作確認 | コピー元・チーム用フォルダーの両方でLaravel初期画面が表示 |
| 初期テスト | 2 passed（2 assertions）。業務機能のテストではない |

教材のLaravel 10では既知の脆弱性による依存関係のブロックが発生したため、今回はLaravel 12を選択しました。教材との差分とチーム全体で使う版は講師にも共有・確認します。保護を無効化するオプションは使用していません。

## Windows PowerShellでの初回セットアップ

前提：リポジトリへのアクセス権、Git、Composer、および依存パッケージの要件を満たすPHPが必要です。現在の構成はPHP 8.2系で起動確認済みです。ZIP拡張が利用できることも確認してください。

以下は1行ずつ実行し、エラーが出たらその時点で止めてチームへ相談してください。`PS C:\...>`などのプロンプトは入力しません。

### 1. リポジトリを取得する

任意の作業用フォルダーで実行します。既存フォルダーを削除・上書きしないでください。

```powershell
git clone https://github.com/ogura-hidetsugu/tdm-web-app-team-tokimeki.git team-clast
Set-Location team-clast
```

初期構成がmainへ取り込まれる前に確認する場合のみ、次の作業ブランチへ移動します。取り込み済みならこの切り替えは不要です。

```powershell
git switch chore/laravel12-initial
```

### 2. 依存パッケージをインストールする

```powershell
composer install
```

初回セットアップでは`composer update`ではなく、共有された`composer.lock`を使う`composer install`を実行します。

### 3. 各PC用の設定を作成する

```powershell
if (-not (Test-Path -LiteralPath .env)) { Copy-Item -LiteralPath .env.example -Destination .env }
php artisan key:generate
```

上記は新しい開発環境の初回セットアップ用です。既存アプリのキーを不用意に再生成しないでください。`.env.example`のDB設定はSQLiteを前提としています。既存の`.env`を使う場合は接続先が意図したローカルDBか確認してください。

### 4. 初期確認用のSQLiteとテーブルを用意する

```powershell
if (-not (Test-Path -LiteralPath database/database.sqlite)) { New-Item -ItemType File -Path database/database.sqlite | Out-Null }
php artisan migrate
```

初期の利用者・キャッシュ・ジョブ関連テーブルを作成します。DB定義書にある商品・家族などの業務テーブルはまだ追加していません。

### 5. テストを実行する

```powershell
php artisan test
```

初期構成では2件のサンプルテストが成功することを確認しています。

### 6. 開発サーバーを起動する

```powershell
php artisan serve
```

表示されたURL（通常は`http://127.0.0.1:8000`）をブラウザで開きます。停止は、そのターミナルで`Ctrl + C`を押します。開発サーバーの外部公開はしません。

現時点の確認対象は初期画面です。Node.js・npmの動作検証とフロントエンドのビルド手順は未確認で、画面開発に入る際に追加で整理します。

## Gitに登録しないもの

- `.env`、パスワード、APIキー、アクセストークン
- ローカルDBの`*.sqlite*`
- `vendor`、`node_modules`
- ログ、生成キャッシュ、コンパイル済み画面

`.env.example`、`composer.json`、`composer.lock`、マイグレーションは共有します。現状の除外ルールは`.gitignore`と各サブフォルダーの`.gitignore`にあります。

## チームでの作業の進め方（初期案）

- mainへ直接変更せず、作業用ブランチからプルリクエストで共有する。
- 同じファイルを編集する際はSlackで声をかける。
- 詰まった場合は、実行したコマンドとエラーを共有する。ただし秘密情報は貼らない。
- DB製品・版、機能の採否、画面・担当範囲は、講師・チームの確認内容に合わせる。
- GitHubへコードがあることと、他のメンバーの招待・起動確認が完了したことは分けて管理する。
