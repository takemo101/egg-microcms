#! /bin/bash

# エラーで処理中断
set -ex

# サービス名
export SERVICE=xxx

# プロジェクトID
export PROJECT=xxx

# リージョン
export REGION=us-central1

# Cloud RunのURL
export BASE_URL=http://localhost

# ポート
export PORT=8080

# MicroCMSのドメイン
export MICROCMS_DOMAIN=$1

# MicroCMSのApiキー
export MICROCMS_API_KEY=$2

# 置換対象の.envファイルパス
DOTENV_PATH="./.gcr/.deploy.env"

# 環境変数へ置換処理によって値を埋め込む
rm -rf ${DOTENV_PATH}
cp ./.gcr/.example.env ${DOTENV_PATH}
sed -i "" "s/=\${MICROCMS_DOMAIN}/=${MICROCMS_DOMAIN}/g" ${DOTENV_PATH}
sed -i "" "s/=\${MICROCMS_API_KEY}/=${MICROCMS_API_KEY}/g" ${DOTENV_PATH}

gcloud run deploy \
  ${SERVICE} \
  --project ${PROJECT} \
  --region ${REGION} \
  --port ${PORT} \
  --set-env-vars "MICROCMS_DOMAIN=${MICROCMS_DOMAIN}" \
  --set-env-vars "MICROCMS_API_KEY=${MICROCMS_API_KEY}" \
  --source .

# 値を埋め込んだ.envファイルを削除して終了
rm -rf ${DOTENV_PATH}
