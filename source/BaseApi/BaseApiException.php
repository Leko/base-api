<?php

namespace Bolster\BaseApi;

/**
 * BASE APIへリクエストを行った時に発生した例外
 * @author Leko <leko.noor@gmail.com>
 */
class BaseApiException extends \RuntimeException {
	public function __construct($message, $code) {
		$this->code = $code;
		return parent::__construct($message);
	}
}
