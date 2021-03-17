<?php

namespace Automattic\WooCommerce\Internal\RestApi\v4;

use Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes\ProductAttribute;
use Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes\ProductAttributes;
use Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes\ProductAttributeTerm;
use Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes\ProductAttributeTerms;

class RootQueryType extends BaseRootType
{
	protected function get_object_type_classes() {
		return [
			ProductAttribute::class,
			ProductAttributes::class,
			ProductAttributeTerm::class,
			ProductAttributeTerms::class
		];
	}

	protected function get_name()
	{
		return "Query";
	}

	protected function get_description()
	{
		return 'The query root of WooCommerce GraphQL API.';
	}

	protected function get_required_permission()
	{
		return 'read';
	}

	protected function get_resolve_method_name()
	{
		return 'resolve';
	}
}
