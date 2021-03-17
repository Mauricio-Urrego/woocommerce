<?php


namespace Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes;

use Automattic\WooCommerce\Internal\RestApi\v4\ApiException;
use Automattic\WooCommerce\Internal\RestApi\v4\BaseQueryType;
use Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes\ProductAttributeTerms;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Automattic\WooCommerce\Internal\RestApi\v4\EnumTypes\ProductAttributeOrderBy;

class ProductAttribute extends BaseQueryType
{
	public function get_description()
	{
		return "A product attribute.";
	}

	public function get_fields()
	{
		$terms_args = $this->container->get(ProductAttributeTerms::class)->get_args();
		unset($terms_args['taxonomy']);

		$order_by_instance = $this->container->get(ProductAttributeOrderBy::class);
		$order_by_comma_separated = $order_by_instance->get_comma_separated_value_names();
		$order_by_default = $order_by_instance->get_enum_value_names();

		return [
			'id' => [
				'type' => Type::nonNull(Type::id()),
				'description' => 'Unique identifier for the resource.'
			],
			'name' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Attribute name.'
			],
			'slug' => [
				'type' => Type::string(),
				'description' => 'An alphanumeric identifier for the resource unique to its type.'
			],
			'type' => [
				'type' => Type::string(),
				'description' => 'Type of attribute. By default only `select` is supported.'
			],
			'order_by' => [
				'type' => $this->container->get(ProductAttributeOrderBy::class),
				'description' => 'Default sort order. Options: ' . $order_by_comma_separated . '. Default is `' . $order_by_default . '`.'
			],
			'has_archives' => [
				'type' => Type::boolean(),
				'description' => 'Enable/Disable attribute archives. Default is `false`.'
			],
			'terms' => [
				'type' => $this->container->get(ProductAttributeTerms::class),
				'description' => 'The terms for this attribute.',
				'args' => $terms_args,
				'resolve' => function($resolvedAttribute, $args, $context, ResolveInfo $info) {
					$args['taxonomy'] = $resolvedAttribute['slug'];
					return $this->container->get(ProductAttributeTerms::class)->resolve($args, $context, $info);
				}
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
		$attribute = (array)wc_get_attribute($args['id']);
		if(is_null($attribute)) {
			throw new ApiException("Can't get this term");
		}

		if(array_key_exists('terms', $info->getFieldSelection())) {
			$terms = get_terms($attribute->slug, array( 'hide_empty' => false, 'fields' => 'all', 'count' => true ));
			$attribute['terms'] = array_map(function($term) {return (array)$term;}, $terms);
		}

		return $attribute;
	}
}
