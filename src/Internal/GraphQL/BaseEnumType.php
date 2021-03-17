<?php


namespace Automattic\WooCommerce\Internal\RestApi\v4;

use GraphQL\Type\Definition\EnumType;


abstract class BaseEnumType extends EnumType
{
	protected $container;

	public function __construct()
	{
		$this->container = wc_get_container();

		$config = [
			'name' => $this->get_name(),
			'description' => $this->get_description(),
			'values' => $this->get_enum_values(),
		];

		parent::__construct($config);
	}

	public function get_name() {
		return $this->tryInferName();
	}

	public function get_comma_separated_value_names() {
		return '`' . implode('`, `', $this->get_enum_value_names()) . '`';
	}

	public function get_enum_value_names() {
		$enum_values = $this->get_enum_values();
		$names = [];

		foreach($enum_values as $key => $value) {
			if(is_string($value)) {
				$names[] = $value;
			}
			else if(is_string($key)) {
				$names[] = $key;
			}
			else {
				$names[] = $value['name'];
			}
		}

		return $names;
	}

	public abstract function get_description();

	public abstract function get_enum_values();
}
