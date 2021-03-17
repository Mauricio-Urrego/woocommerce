<?php

namespace Automattic\WooCommerce\Internal\RestApi\v4\InputTypes;

use Automattic\WooCommerce\Internal\RestApi\v4\BaseInputType;
use GraphQL\Type\Definition\Type;

class AddProductAttributeTermInputType extends BaseInputType
{
	public function get_fields()
	{
		return [
			'attribute_id' => [
				'type' => Type::nonNull(Type::id()),
				'description' => 'Unique identifier of the product attribute the term will be added to.',
			],
			'name' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Term name.'
			],
			'slug' => [
				'type' => Type::string(),
				'description' => 'An alphanumeric identifier for the resource unique to its type.'
			],
			'description' => [
				'type' => Type::string(),
				'description' => 'HTML description of the resource.'
			],

			//TODO: Add 'menu order' when implementing insertion in AddProductAttributeTerm
			/*'menu_order' => [
				'type' => Type::int(),
				'description' => 'Menu order, used to custom sort the resource.'
			]*/
		];
	}
}
