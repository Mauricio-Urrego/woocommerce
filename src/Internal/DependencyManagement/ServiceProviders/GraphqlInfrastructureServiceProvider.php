<?php
/**
 * GraphqlInfrastructureServiceProvider class file.
 */

namespace Automattic\WooCommerce\Internal\DependencyManagement\ServiceProviders;

use Automattic\WooCommerce\Internal\DependencyManagement\AbstractServiceProvider;
use Automattic\WooCommerce\Internal\RestApi\v4\BaseEnumType;
use Automattic\WooCommerce\Internal\RestApi\v4\BaseObjectListType;
use Automattic\WooCommerce\Internal\RestApi\v4\BaseObjectType;
use Automattic\WooCommerce\Internal\RestApi\v4\RootQueryType;

/**
 * Service provider for the infrastructure classes in the Automattic\WooCommerce\Internal\RestApi\v4 namespace.
 */
class GraphqlInfrastructureServiceProvider extends AbstractServiceProvider {

	/**
	 * The classes/interfaces that are serviced by this service provider.
	 *
	 * @var array
	 */
	protected $provides = array(
		BaseEnumType::class,
		BaseObjectListType::class,
		BaseObjectType::class,
		RootQueryType::class
	);

	/**
	 * Register the classes.
	 */
	public function register() {
		foreach($this->provides as $class_name) {
			$this->share_with_auto_arguments($class_name);
		}
	}
}
