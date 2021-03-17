<?php


namespace Automattic\WooCommerce\Internal\RestApi\v4\QueryTypes;


use Automattic\WooCommerce\Internal\RestApi\v4\BaseObjectListType;
use GraphQL\Type\Definition\ResolveInfo;

class ProductAttributes extends BaseObjectListType
{
	private $attribute_taxonomies;

	public function get_object_class_name()
	{
		return ProductAttribute::class;
	}

	public function resolve_total_count($extra_args)
	{
		global $wpdb;

		$sql = "SELECT COUNT(1) FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name != '' ORDER BY attribute_name ASC";

		return $wpdb->get_var($sql);
	}

	public function resolve_items($offset, $count, $extra_args, $fieldSelection)
	{
		global $wpdb;

		$sql = $wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name != '' ORDER BY attribute_name ASC LIMIT %d OFFSET %d",
			$count, $offset);

		$rows = $wpdb->get_results($sql, ARRAY_A);

		return array_map(function($row) {
			/*$attribute->id           = (int) $data->attribute_id;
			$attribute->name         = $data->attribute_label;
			$attribute->slug         = wc_attribute_taxonomy_name( $data->attribute_name );
			$attribute->type         = $data->attribute_type;
			$attribute->order_by     = $data->attribute_orderby;
			$attribute->has_archives = (bool) $data->attribute_public;*/

			return [
				'id' => $row['attribute_id'],
				'name' => $row['attribute_label'],
				'slug' => wc_attribute_taxonomy_name( $row['attribute_name'] ),
				'type' => $row['attribute_type'],
				'order_by' => $row['attribute_orderby'],
				'has_archives' => (bool)$row['attribute_public']
			];
		}, $rows);
	}
}
