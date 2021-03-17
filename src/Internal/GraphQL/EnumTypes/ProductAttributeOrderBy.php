<?php

namespace Automattic\WooCommerce\Internal\RestApi\v4\EnumTypes;

use Automattic\WooCommerce\Internal\RestApi\v4\BaseEnumType;

class ProductAttributeOrderBy extends BaseEnumType
{
	public function get_description()
	{
		return "Default sort order for product attribute terms.";
	}

	public function get_enum_values()
	{
		return [
			'menu_order' => [
				'description' => 'Order by name.'
			],
			'name' => [
				'description' => 'Order by name.'
			],
			'name_num' => [
				'description' => 'Order by name (numeric).'
			],
			'id' => [
				'description' => 'Order by id.'
			]
		];
	}
}
