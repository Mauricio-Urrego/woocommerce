<?php
/**
 * GraphqlTypesServiceProvider class file.
 */

namespace Automattic\WooCommerce\Internal\DependencyManagement\ServiceProviders;

use Automattic\WooCommerce\Internal\DependencyManagement\AbstractServiceProvider;
use Automattic\WooCommerce\Internal\RestApi\v4\EnumTypes\ProductAttributeOrderBy;
use Automattic\WooCommerce\Internal\RestApi\v4\InputTypes\AddProductAttributeTermInputType;
use Automattic\WooCommerce\Internal\RestApi\v4\MutationTypes\AddProductAttributeTerm;
use Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes\ProductAttribute;
use Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes\ProductAttributes;
use Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes\ProductAttributeTerm;
use Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes\ProductAttributeTerms;


/**
 * Service provider for the type definition classes in the Automattic\WooCommerce\Internal\RestApi\v4 namespace.
 */
class GraphqlTypesServiceProvider extends AbstractServiceProvider {

	/**
	 * The classes/interfaces that are serviced by this service provider.
	 *
	 * @var array
	 */
	protected $provides = array(
		ProductAttribute::class,
		ProductAttributes::class,
		ProductAttributeOrderBy::class,
		ProductAttributeTerm::class,
		ProductAttributeTerms::class,
		AddProductAttributeTerm::class,
		AddProductAttributeTermInputType::class
	);

	/**
	 * Register the classes.
	 */
	public function register() {
		foreach($this->provides as $class_name) {
			$this->share($class_name);
		}
	}
}
