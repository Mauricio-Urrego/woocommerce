<?php


namespace Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes;

use Automattic\WooCommerce\Internal\RestApi\v4\ApiException;
use Automattic\WooCommerce\Internal\RestApi\v4\BaseQueryType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class ProductAttributeTerm extends BaseQueryType
{
	public function get_description()
	{
		return "A term of a product attribute.";
	}

	public function get_fields()
	{
		return [
			'id' => [
				'type' => Type::nonNull(Type::id()),
				'description' => 'Unique identifier for the resource.',
				'resolve' => function($resolvedTerm) {
					return $resolvedTerm['term_id'];
				}
			],
			'name' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Term name'
			],
			'slug' => [
				'type' => Type::string(),
				'description' => 'An alphanumeric identifier for the resource unique to its type.'
			],
			'description' => [
				'type' => Type::string(),
				'description' => 'HTML description of the resource.'
			],
			'menu_order' => [
				'type' => Type::int(),
				'description' => 'Menu order, used to custom sort the resource.'
			],
			'count' => [
				'type' => Type::int(),
				'description' => 'Number of published products for the resource.'
			],
			'taxonomy' => [
				'type' => Type::string(),
				'description' => 'The taxonomy this term belongs to.'
			],
		];
	}

	public function get_args()
	{
		return [
			'id' => [
				'type' => Type::nonNull(Type::id()),
				'description' => 'Unique identifier for the resource.'
			],
		];
	}

	public function resolve($args, $context, ResolveInfo $info)
	{
		$term = get_term($args['id'], '', ARRAY_A);
		if(!is_array($term)) {
			throw new ApiException("Can't get this term");
		}

		return $term;
	}
}
