<?php

namespace Automattic\WooCommerce\Internal\RestApi\v4;

use Automattic\WooCommerce\Internal\RestApi\v4\MutationTypes\AddProductAttributeTerm;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class RootMutationType extends BaseRootType
{
	protected function get_object_type_classes() {
		return [
			AddProductAttributeTerm::class
		];
	}

	protected function get_name()
	{
		return 'Mutation';
	}

	protected function get_description()
	{
		return 'The root query for implementing GraphQL mutations.';
	}

	protected function get_required_permission()
	{
		return 'write';
	}

	protected function get_resolve_method_name()
	{
		return 'execute';
	}
}
