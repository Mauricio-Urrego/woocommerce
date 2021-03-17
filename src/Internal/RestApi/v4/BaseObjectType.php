<?php


namespace Automattic\WooCommerce\Internal\RestApi\v4;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;

abstract class BaseObjectType extends ObjectType
{
	protected $container;

	public function __construct()
	{
		$this->container = wc_get_container();

		$config = [
			'name' => $this->get_name(),
			'description' => $this->get_description(),
			'fields' => $this->get_fields(),
		];

		if(!empty($this->get_args())) {
			$config['args'] = $this->get_args();
		}

		$config = array_merge($config, $this->get_extra_config());

		parent::__construct($config);
	}

	public function get_name() {
		return $this->tryInferName();
	}

	public abstract function get_description();

	public abstract function get_fields();

	public function get_args() {
		return [];
	}

	public function get_extra_config() {
		return [];
	}

	public abstract function resolve($args, $context, ResolveInfo $info);
}
