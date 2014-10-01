<?php

namespace Bolster\BaseApi;

/**
 * アクセストークンの有効期限が切れているときに発生する例外
 * @author Leko <leko.noor@gmail.com>
 */
class ExpiredAccessTokenException extends BaseApiException {}
