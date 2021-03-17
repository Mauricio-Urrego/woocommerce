<?php


namespace Automattic\WooCommerce\Internal\RestApi\v4;

use Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes\ProductAttribute;
use Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes\ProductAttributes;
use Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes\ProductAttributeTerm;
use Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes\ProductAttributeTerms;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class RootQueryType extends ObjectType
{
	/**
	 * @var array
	 */
	private $type_instances;

	private $container;

	public function __construct() {

		//We can't pass the container in the "init" method because that method
		//hasn't been called yet, and we need the container for get_fields.
		$this->container = wc_get_container();

		$config = [
			'name' => 'Query',
			'description' => "The query root of WooCommerce GraphQL API.",
			'fields' => $this->get_fields(),
			'resolveField' => function($value, $args, $context, ResolveInfo $info) {
				return Main::resolve_type($info->fieldName)->resolve($args, $context, $info);
			}
		];

		parent::__construct($config);
	}

	/*public final function init(\Psr\Container\ContainerInterface $container) {
		$this->container = $container;
	}*/

	private function get_fields()
	{
		$fields=[];
		$class_names = $this->get_object_type_classes();
		foreach($class_names as $class_name) {
			$type_object = $this->container->get($class_name);
			$fields[$type_object->get_name()] = [
				'type' => $type_object,
				'description' => $type_object->get_description(),
				'args' => $type_object->get_args()
			];
			$this->type_instances[$type_object->get_name()] = $type_object;
		}

		return $fields;
	}

	private function get_object_type_classes() {
		return [
			ProductAttribute::class,
			ProductAttributes::class,
			ProductAttributeTerm::class,
			ProductAttributeTerms::class
		];
	}

	private function get_object_type_classes_by_filename() {
		$file_names = scandir(__DIR__ . '/QueryTypes');
		$class_names = [];

		foreach($file_names as $file_name) {
			if($file_name[0] === '.') {
				continue;
			}
			$full_filename = __DIR__ . '/QueryTypes/' . $file_name;
			$path_parts = pathinfo($full_filename);
			$class_names[] = '\\' . __NAMESPACE__ . '\\QueryTypes\\' . $path_parts['filename'];
		}

		return $class_names;
	}
}
