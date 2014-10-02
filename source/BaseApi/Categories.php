<?php

namespace Bolster\BaseApi;

/**
 * Categories APIのクライアントとなるクラス
 * @author Leko <leko.noor@gmail.com>
 */
class Categories extends Client
{
	/**
	 * カテゴリー情報の一覧を取得
	 * 
	 * GET /1/categories
	 * @see https://gist.github.com/baseinc/a3cf5ecd760922534cab
	 * 
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function categories() {
		return $this->request('get', '/1/categories', $params);
	}

	/**
	 * カテゴリー情報を登録
	 * 
	 * POST /1/categories/add
	 * @see https://gist.github.com/baseinc/983254bab52854cde97f
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param string name       カテゴリー名 (必須)
	 *   @param int    list_order カテゴリーの並び順 (任意)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function add(array $params = array()) {
		return $this->request('post', '/1/categories/add', $params);
	}

	/**
	 * カテゴリー情報を更新
	 * 
	 * POST /1/categories/edit
	 * @see https://gist.github.com/baseinc/bc7ff96fbe0dec990dd1
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param int    category_id カテゴリーID (必須)
	 *   @param string name        カテゴリー名 (任意)
	 *   @param int    list_order  カテゴリーの並び順 (任意)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function edit(array $params = array()) {
		return $this->request('post', '/1/categories/edit', $params);
	}

	/**
	 * カテゴリー情報を削除
	 * 
	 * POST /1/categories/delete
	 * @see https://gist.github.com/baseinc/c58b07a2489f77879e67
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param int category_id カテゴリーID (必須)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function delete(array $params = array()) {
		return $this->request('post', '/1/categories/delete', $params);
	}
}
