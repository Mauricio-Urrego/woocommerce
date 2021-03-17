<?php


namespace Automattic\WooCommerce\Internal\RestApi\v4;


use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

abstract class BaseObjectListType extends BaseObjectType
{
	const MAX_RESULTS_PER_QUERY = 100;

	protected $object_type_instance;

	public function __construct()
	{
		$this->container = wc_get_container();

		$this->object_type_instance = $this->container->get($this->get_object_class_name());
		parent::__construct($this->get_args());
	}

	public function get_description()
	{
		$object_name = $this->container->get($this->get_object_class_name())->get_name();
		$object_name_parts = explode('\\', $object_name);
		return "A collection of ". array_pop($object_name_parts) . '.';
	}

	public function get_fields()
	{
		return [
			'total_count' => [
				'type' => Type::int(),
				'description' => 'The total count.',
			],
			'items' => [
				'type' => Type::listOf($this->object_type_instance),
				'description' => 'The items themselves.'
			]
		];
	}

	public function get_args() {
		$args = [
			'offset' => [
				'type' => Type::int(),
				'description' => 'The offset.'
			],
			'count' => [
				'type' => Type::int(),
				'description' => 'The count.'
			]
		];

		return array_merge($args, $this->get_extra_args());
	}

	public function resolve($args, $context, ResolveInfo $info)
	{
		$this->pre_resolve($args, $context, $info);

		$extra_args = $args;
		unset($extra_args['offset']);
		unset($extra_args['count']);

		$result = [];

		$field_selection = $info->getFieldSelection();
		if(array_key_exists('total_count', $field_selection)) {
			$result['total_count'] = $this->resolve_total_count($extra_args);
		}
		if(array_key_exists('items', $field_selection)) {
			$result['items'] = $this->resolve_items(
				isset($args['offset']) ? $args['offset'] : 0,
				isset($args['count']) ? $args['count'] : self::MAX_RESULTS_PER_QUERY,
				$extra_args,
				$field_selection['items']
			);
		}

		return $result;
	}

	public function get_extra_args() {
		return [];
	}

	public abstract function get_object_class_name();

	public function pre_resolve($args, $context, ResolveInfo $info) {
	}

	public abstract function resolve_total_count($extra_args);

	public abstract function resolve_items($offset, $count, $extra_args, $fieldSelection);
}
