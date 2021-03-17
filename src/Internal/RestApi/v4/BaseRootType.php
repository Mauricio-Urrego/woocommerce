<?php


namespace Automattic\WooCommerce\Internal\RestApi\v4;


use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

abstract class BaseRootType extends ObjectType
{
	private $type_instances;

	private $container;

	public function __construct() {

		//We can't pass the container in the "init" method because that method
		//hasn't been called yet, and we need the container for get_fields.
		$this->container = wc_get_container();

		$config = [
			'name' => $this->get_name(),
			'description' => $this->get_description(),
			'fields' => $this->get_fields(),
			'resolveField' => function($value, $args, $context, ResolveInfo $info) {
				return $this->resolve_field($value, $args, $context, $info);
			}
		];

		parent::__construct($config);
	}

	private function resolve_field($value, $args, $context, ResolveInfo $info) {
		$user_has_permission = \WC_REST_Authentication::instance()->current_user_has_permission($this->get_required_permission());
		$user_has_permission = apply_filters('woocommerce_graphql_check_permissions', $user_has_permission, $info->fieldName, $this->get_required_permission(), $args);

		if(! $user_has_permission ) {
			throw ApiException::Unauthorized();
		}

		$resolve_method = $this->get_resolve_method_name();
		return Main::resolve_type($info->fieldName)->$resolve_method($args, $context, $info);
	}

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

	protected abstract function get_name();

	protected abstract function get_description();

	protected abstract function get_required_permission();

	protected abstract function get_resolve_method_name();

	protected abstract function get_object_type_classes();
}
