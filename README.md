# Git pipeline

## **About**

GitへのPUSHをトリガーに、EC2へのCodeDeployを実行するPipelineを作成
<br>画像イメージはS3バケットから配信する
<br>DBのパスワードやアクセスキーはAWS System Managerパラメータストアに保管

## **Inside Package**
* appspec.yml
* scripts
* [laravel-sample-app-s3](https://github.com/siwai0208/food-app-s3)

## **Dependency**
You have to execute [**launch-templete**](https://github.com/siwai0208/cloudformation/tree/main/launch-templete) stack and have your own S3bucket

## **How to use**

**1. launch-templeteを使い、CodeDeployエージェントのインストールされたEC2を1台起動する（マネコン使用）**

- EC2 ダッシュボード > 起動テンプレート > Codepipeline-template を選択
```
  アクション > テンプレートからインスタンスを起動
  ネットワーク設定 > サブネット > public-subnet-1a を選択
  テンプレートからインスタンスを起動
```

**2. AWS CLIの設定（EC2からパラメータストアの値を読み込む）**

- EC2にSSHでログインし、AWS CLIの初期設定
```
 $ aws configure
 AWS Access Key ID [None]: YOUR ACCESS KEY ID
 AWS Secret Access Key [None]: YOUR SECRET ACCESS KEY
 Default region name [None]: ap-northeast-1
 Default output format [None]: json 
```

**3. System Manager**

- AWS Systems Manager > パラメータストア > パラメータの作成
<br>以下の名前で紐づくパラメータを登録する
```
 名前：タイプ：値
 DB_DATABASE：文字列：DB名
 DB_USERNAME：文字列：DBユーザ名
 DB_PASSWORD：文字列：DBパスワード
 ACCESS_KEY_ID：文字列：AWS ACCESS KEY ID
 SECRET_ACCESS_KEY：文字列：AWS SECRET ACCESS KEY
 BUCKET：文字列：AWS S3 BUCKET（画像保存するバケット）
 DEFAULT_REGION：文字列：AWS DEFAULT REGION
```

**4. CodeDeployの作成**

- デベロッパー用ツール > CodeDeploy > アプリケーション > アプリケーションの作成
```
  アプリケーション名: laravel-app-s3
  コンピューティングプラットフォーム: EC2/オンプレミス
```

- 続けて　デプロイグループの作成
```
  デプロイグループ名: laravel-app-s3-dply-grp
  サービスロール: CodeDeployServiceRole
  環境設定: Amazon EC2 インスタンス
  タググループ 1  キー:name 値:codepipeline
  Load balancer: 無効
  デプロイグループの作成
```

**5. CodePipelineの作成**

- デベロッパー用ツール > CodePipeline > パイプライン > パイプラインを作成する
```
  パイプライン名: git-pipeline
  サービスロール: 新しいサービスロール
  アーティファクトストア: 作成済みのS3バケットを選択
  ソースプロバイダー: Github(バージョン2)
  バケット: 作成済みのS3バケットを選択
  接続: GitHub に接続する　をクリック
    以下ポップアップ画面での操作
    　接続名: git-pipeline > Githubに接続する
   　 GitHub アプリ: 新しいアプリをインストールする
   　 Githubにログインし、git-pipelineレポジトリへのアクセスを有効化し、接続
  リポジトリ名: 自アカウント/git-pipeline
  ブランチ名: ブランチ名を選択
  ビルドステージをスキップ
  デプロイプロバイダー: AWS CodeDeploy
  アプリケーション名: laravel-app-s3
  デプロイグループ: laravel-app-s3-dply-grp
  パイプラインを作成する
```

- PIPELINEの作成後、自動でSource→Deployに進む
```
  Source 成功しました
  ↓
  Deploy 成功しました
```

- EC2のグローバルIPにアクセスし、Laravelアプリが正常に表示されること

- 以降、GitレポジトリのPUSHをトリガーにCodePipelineでSource→Deployが実行される
