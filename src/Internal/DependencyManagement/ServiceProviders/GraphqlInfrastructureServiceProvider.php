<?php
/**
 * GraphqlInfrastructureServiceProvider class file.
 */

namespace Automattic\WooCommerce\Internal\DependencyManagement\ServiceProviders;

use Automattic\WooCommerce\Internal\DependencyManagement\AbstractServiceProvider;
use Automattic\WooCommerce\Internal\RestApi\v4\BaseEnumType;
use Automattic\WooCommerce\Internal\RestApi\v4\BaseInputType;
use Automattic\WooCommerce\Internal\RestApi\v4\BaseMutationType;
use Automattic\WooCommerce\Internal\RestApi\v4\BaseQueryListType;
use Automattic\WooCommerce\Internal\RestApi\v4\BaseQueryType;
use Automattic\WooCommerce\Internal\RestApi\v4\BaseRootType;
use Automattic\WooCommerce\Internal\RestApi\v4\RootQueryType;
use Automattic\WooCommerce\Internal\RestApi\v4\RootMutationType;

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
		BaseInputType::class,
		BaseMutationType::class,
		BaseQueryListType::class,
		BaseQueryType::class,
		RootQueryType::class,
		BaseRootType::class,
		RootMutationType::class
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
