<?php

namespace Automattic\WooCommerce\Internal\RestApi\v4\MutationTypes;

use Automattic\WooCommerce\Internal\RestApi\v4\ApiException;
use Automattic\WooCommerce\Internal\RestApi\v4\BaseMutationType;
use Automattic\WooCommerce\Internal\RestApi\v4\InputTypes\AddProductAttributeTermInputType;
use Automattic\WooCommerce\Internal\RestApi\v4\Main;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class AddProductAttributeTerm extends BaseMutationType
{

	public function get_description()
	{
		return "Creates a new product attribute term.";
	}

	public function execute($args, $context, ResolveInfo $info)
	{
		return ['id' => 34];

		//TODO: Implement inserting 'menu order' too

		$input = $args['input'];

		$attribute = wc_get_attribute($input['attribute_id']);
		if(is_null($attribute)) {
			throw new ApiException('Invalid attribute id');
		}

		$insert_args = [];
		if(isset($input['description'])) {
			$insert_args['description'] = $input['description'];
		}
		if(isset($input['slug'])) {
			$insert_args['slug'] = $input['slug'];
		}

		$term = wp_insert_term( $input['name'], $attribute->slug, $insert_args );
		if ( is_wp_error( $term ) ) {
			throw new ApiException("Can't create term: " . $term->get_error_message());
		}

		return ['id' => $term['term_id']];
	}
}
