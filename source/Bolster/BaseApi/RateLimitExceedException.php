<?php

namespace Bolster\BaseApi;

/**
 * BASE APIの利用回数制限を超えたときに発生する例外
 * @author Leko <leko.noor@gmail.com>
 */
class RateLimitExceedException extends BaseApiException {}
