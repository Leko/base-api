
## 雑記
### OAuth
- `authorize`メソッドでscopeを何も設定せず(`&scope=`で終わった状態で)送信するとデフォルト権限になる

### Categories
- 指定できるカテゴリ名は**最大30文字**、それ以上長い文字列を指定しても後ろが切り取られる
	- 半角でも全角でも30文字。バイト数ではなく文字列長で判断されている
- 指定できる`list_order`は**最大100000まで**。それ以上大きな値を指定しても100000に丸められる
- カテゴリ名が重複すると「バリデーションエラーです」と出る

### ItemCategories
- 既に登録されている`item_id`, `category_id`の組み合わせをaddしようとすると`不正なcategory_idです。`と言われる
