<?php


namespace Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes;

use Automattic\WooCommerce\Internal\RestApi\v4\ApiException;
use Automattic\WooCommerce\Internal\RestApi\v4\BaseQueryListType;
use GraphQL\Type\Definition\Type;

class ProductAttributeTerms extends BaseQueryListType
{
	public function get_object_class_name()
	{
		return ProductAttributeTerm::class;
	}

	public function get_extra_args() {
		return [
			'taxonomy' => [
				'type' => Type::string(),
				'description' => 'Return only the terms for the given taxonomy.'
			]
		];
	}

	public function resolve_total_count($extra_args)
	{
		return $this->resolve_terms(['hide_empty' => false, 'fields' => 'count'], $extra_args);
	}

	public function resolve_items($offset, $count, $extra_args, $fieldSelection)
	{
		$result = $this->resolve_terms(['hide_empty' => false, 'fields' => 'all', 'offset' => $offset, 'number' => $count===-1 ? 0 : $count], $extra_args);
		if(!is_array($result)) {
			throw new ApiException("Can't get terms");
		}
		return array_map(function($term) { return (array)$term;}, $result);
	}

	private function resolve_terms($args_for_get_terms, $extra_args) {
		if(isset($extra_args['taxonomy'])) {
			$args_for_get_terms['taxonomy'] = $extra_args['taxonomy'];
		}

		$result = get_terms($args_for_get_terms);
		if($result instanceof \WP_Error) {
			throw new ApiException($result->get_error_message());
		}
		return $result;
	}
}
