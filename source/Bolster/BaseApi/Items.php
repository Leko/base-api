<?php

namespace Bolster\BaseApi;

/**
 * Items APIのクライアントとなるクラス
 * @author Leko <leko.noor@gmail.com>
 */
class Items extends Client
{
	/**
	 * 商品情報の一覧を取得
	 * 
	 * GET /1/items
	 * @see https://gist.github.com/baseinc/9910430
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param XXXXX order 並び替え項目。list_order か created のいずれか (任意 デフォルト: list_order)
	 *   @param XXXXX sort 並び順。asc か desc のいずれか (任意 デフォルト: asc)
	 *   @param XXXXX limit リミット (任意 デフォルト: 20, MAX: 100)
	 *   @param XXXXX offset オフセット (任意 デフォルト: 0)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function items(array $params = array()) {
		return $this->request('get', '/1/items', $params);
	}

	/**
	 * 商品情報を取得
	 * 
	 * GET /1/items/detail/:item_id
	 * @see https://gist.github.com/baseinc/9912650
	 * 
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function :item_id(array $params = array()) {
		return $this->request('get', '/1/items/detail/:item_id', $params);
	}

	/**
	 * 商品情報を登録
	 * 
	 * POST /1/items/add
	 * @see https://gist.github.com/baseinc/10241083
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param XXXXX title 商品名 (必須)
	 *   @param XXXXX detail 商品説明 (任意)
	 *   @param XXXXX price 価格 (必須)
	 *   @param XXXXX stock 在庫数 (必須)
	 *   @param XXXXX visible 1:表示、0:非表示 (任意 デフォルト:1)
	 *   @param XXXXX identifier 商品コード (任意)
	 *   @param XXXXX list_order 表示順 (任意 デフォルト:0)
	 *   @param XXXXX variation[0] ... バリエーションの種類 (任意)
	 *   @param XXXXX variation_stock[0] ... バリエーションの在庫数 (任意)
	 *   @param XXXXX variation_identifier[0] ... バリエーションの商品コード (任意)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function add(array $params = array()) {
		return $this->request('post', '/1/items/add', $params);
	}

	/**
	 * 商品情報を更新
	 * 
	 * POST /1/items/edit
	 * @see https://gist.github.com/baseinc/10253466
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param XXXXX item_id 商品ID (必須)
	 *   @param XXXXX title 商品名 (任意)
	 *   @param XXXXX detail 商品説明 (任意)
	 *   @param XXXXX price 価格 (任意)
	 *   @param XXXXX stock 在庫数 (任意)
	 *   @param XXXXX visible 表示:1、非表示:0 (任意)
	 *   @param XXXXX list_order 表示順 (任意)
	 *   @param XXXXX identifier 商品コード (任意)
	 *   @param XXXXX variation_id[0] ... バリエーションID。バリエーションを追加したい場合は空文字列。 (任意 バリエーションを更新する場合は必須)
	 *   @param XXXXX variation[0] ... バリエーションの種類 (任意)
	 *   @param XXXXX variation_stock[0] ... バリエーションの在庫数 (任意)
	 *   @param XXXXX variation_identifier[0] ... バリエーションの商品コード (任意)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function edit(array $params = array()) {
		return $this->request('post', '/1/items/edit', $params);
	}

	/**
	 * 商品情報を削除
	 * 
	 * POST /1/items/delete
	 * @see https://gist.github.com/baseinc/10254181
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param XXXXX item_id 商品ID (必須)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function delete(array $params = array()) {
		return $this->request('post', '/1/items/delete', $params);
	}

	/**
	 * 商品情報の画像を登録
	 * 
	 * POST /1/items/add_image
	 * @see https://gist.github.com/baseinc/10247116
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param XXXXX item_id 商品ID (必須)
	 *   @param XXXXX image_no 画像番号 1~5 (必須)
	 *   @param XXXXX image_url 画像URL。jpgかpngかgif。容量4M以内。推奨サイズは640×640px。 (必須)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function add_image(array $params = array()) {
		return $this->request('post', '/1/items/add_image', $params);
	}

	/**
	 * 商品情報の画像を削除
	 * 
	 * POST /1/items/delete_image
	 * @see https://gist.github.com/baseinc/10251257
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param XXXXX item_id 商品ID (必須)
	 *   @param XXXXX image_no 画像番号 1~5 (必須)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function delete_image(array $params = array()) {
		return $this->request('post', '/1/items/delete_image', $params);
	}

	/**
	 * 商品情報の在庫数を更新
	 * 
	 * POST /1/items/edit_stock
	 * @see https://gist.github.com/baseinc/10623305
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param XXXXX item_id 商品ID (必須)
	 *   @param XXXXX stock 在庫数 (必須 バリエーションの在庫数を更新したい場合は任意)
	 *   @param XXXXX variation_id バリエーションID (任意 バリエーションの在庫数を更新したい場合は必須)
	 *   @param XXXXX variation_stock バリエーションの在庫数 (任意 バリエーションの在庫数を更新したい場合は必須)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function edit_stock(array $params = array()) {
		return $this->request('post', '/1/items/edit_stock', $params);
	}
}
