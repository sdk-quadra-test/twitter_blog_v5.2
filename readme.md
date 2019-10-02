# 大体の概要
・loginページでユーザーを新規作成して、loginするとtweetできます。
・loginしなくても、閲覧だけならできます。

# 最初にclone(or zip DL)したら
composer updateしてください.(vendorフォルダ作成する)

# php 5.6.19で動きます
・企業の環境に合わせたのが理由です。

## herokuで表面的な部分だけ見られます
・http://tweetboard556.herokuapp.com/timeline
・ログインしようとすると「そんな関数ない」って怒られます。なのでここでは「ログインしない時」の挙動のみ見られます。herokuがphp5.6をサポートしていないのが原因、、AWSだったら古いphp指定できるらしい。

## .envファイルって
どうやって相手に渡すの？
