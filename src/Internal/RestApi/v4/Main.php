<?php

namespace Automattic\WooCommerce\Internal\RestApi\v4;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Utils\BuildSchema;
use GraphQL\Error\ClientAware;
use GraphQL\Error\Debug;
use GraphQL\Utils\AST;
use GraphQL\Language\Parser;
use Mockery\Exception;

defined( 'ABSPATH' ) || exit;

class Main
{
	private static $container;

	private static $subnamespaces = ['EnumTypes', 'QueryTypes'];

	public static function init() {
		self::$container = wc_get_container();

		add_action( 'rest_api_init', function () {
			register_rest_route( 'wc/v4', 'api', array(
				'methods' => 'POST',
				'callback' => function($request) {
					return call_user_func(self::class . '::handle_request', $request);
				}
			) );
		} );
	}

	public static function resolve_type($name) {
		foreach(self::$subnamespaces as $namespace) {
			$full_name = __NAMESPACE__ . '\\' . $namespace . '\\' . $name;
			if(self::$container->has($full_name)) {
				return self::$container->get($full_name);
			}
		}
		throw new \Exception("There's no way to resolve the type '".$name."'.");
	}

	private static function handle_request(\WP_REST_Request $request) {
		try {
			$input = json_decode($request->get_body(), true);
			if(is_null($input)) {
				throw new ApiException('Invalid input JSON: ' . json_last_error_msg());
			}
			if(!isset($input['query'])) {
				throw new ApiException("Invalid input JSON: no 'query' element");
			}

			$query = $input['query'];
			$variableValues = isset($input['variables']) ? $input['variables'] : null;

			$schema = new Schema([
				'query' => self::$container->get(RootQueryType::class),
				'typeLoader' => function($name) {
					return self::resolve_type($name);
				}
			]);

			$result = GraphQL::executeQuery($schema, $query, null, null, $variableValues);
			$output = $result->toArray(self::get_debug_config());
		} catch (\Exception $e) {
			$category = $e instanceof ClientAware ? $e->getCategory() : 'internal';

			if(self::get_debug_config()) {
				$output = [
					'errors' => [
						[
							'message' => $e->getMessage(),
							'category' => $category,
							'trace' => $e->getTrace()
						]
					]
				];
			}
			else {
				$output = [
					'errors' => [
						[
							'message' => 'Internal server error',
							'category' => $category
						]
					]
				];
			}
		}

		return $output;
	}

	private static function get_debug_config() {
		if(! isset($_GET['verbose_errors'])) {
			return false;
		}

		if(wc_current_user_has_role('administrator') || (defined('WP_DEBUG') && WP_DEBUG)) {
			return Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE;
		}

		return false;
	}
}
