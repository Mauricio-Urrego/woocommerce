<?php


namespace Automattic\WooCommerce\Internal\RestApi\v4;


use Automattic\WooCommerce\Internal\RestApi\v4\InputTypes\AddProductAttributeTermInputType;
use Automattic\WooCommerce\Utilities\StringUtil;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

abstract class BaseMutationType extends ObjectType
{
	protected $container;

	public function __construct()
	{
		$this->container = wc_get_container();

		$config = [
			'name' => $this->get_name(),
			'description' => $this->get_description(),
			'fields' => $this->get_fields(),
			'args' => $this->get_args()
		];

		parent::__construct($config);
	}

	public function get_name() {
		return $this->tryInferName();
	}

	public function get_fields()
	{
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'The unique identifier of the resource created.'
			]
		];
	}

	public function get_args() {
		$my_class_name = StringUtil::class_name_without_namespace(get_class($this));
		$input_type_name = $my_class_name . 'InputType';
		$input_type = Main::resolve_type($input_type_name);

		return [
			'input' => $input_type
		];
	}

	public abstract function get_description();

	public abstract function execute($args, $context, ResolveInfo $info);
}
