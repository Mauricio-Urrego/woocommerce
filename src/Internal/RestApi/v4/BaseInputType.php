<?php


namespace Automattic\WooCommerce\Internal\RestApi\v4;


use Automattic\WooCommerce\Utilities\StringUtil;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

abstract class BaseInputType extends InputObjectType
{
	protected $container;

	public function __construct()
	{
		$this->container = wc_get_container();

		$config = [
			'name' => $this->get_name(),
			'description' => $this->get_description(),
			'fields' => $this->get_fields(),
		];

		parent::__construct($config);
	}

	public function get_name() {
		return $this->tryInferName();
	}

	public function get_description() {
		$my_class_name = StringUtil::class_name_without_namespace(get_class($this));
		$mutation_name = preg_replace('~InputType$~', '', $my_class_name);
		return "Input type for the {$mutation_name} mutation.";
	}

	public abstract function get_fields();
}
