<?php


namespace Automattic\WooCommerce\Internal\RestApi\v4;

use GraphQL\Error\ClientAware;

class ApiException extends \Exception implements ClientAware
{
	private $category;

	public function __construct($message = '', $category = 'request', $code = 0, Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
		$this->category = $category;
	}

	public function isClientSafe()
	{
		return true;
	}

	public function getCategory()
	{
		return $this->category;
	}

	public static function Unauthorized($message = null) {
		return new ApiException(is_null($message) ? "You don't have permission for the requested operation" : $message, 'authorization');
	}
}
