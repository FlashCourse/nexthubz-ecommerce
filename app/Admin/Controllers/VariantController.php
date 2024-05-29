<?php

namespace App\Admin\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Grid\Tools\QuickCreate;
use OpenAdmin\Admin\Show;
use \App\Models\Variant;

class VariantController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Variant';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Variant());

        $grid->column('id', __('Id'));
        $grid->column('product_id', __('Product id'));
        $grid->column('sku', __('Sku'));
        $grid->column('price', __('Price'));
        $grid->column('stock', __('Stock'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Variant::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('product_id', __('Product id'));
        $show->field('sku', __('Sku'));
        $show->field('price', __('Price'));
        $show->field('stock', __('Stock'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        $show->variantAttributes('Variant Attributes', function ($variantAttributes) use ($id) {
            $variantAttributes->setResource('/admin/variant-attributes');


            // Logging the attributes and attribute values to debug
            $attributes = Attribute::pluck('name', 'id');
            $attributeValues = AttributeValue::pluck('value', 'id');


            $variantAttributes->quickCreate(function (QuickCreate $create) use ($id, $attributes, $attributeValues) {
                $create->text('variant_id', 'Variant ID')->default($id);
                $create->select('attribute_id', __('Attribute'))->options($attributes);
                $create->select('attribute_value_id', __('Attribute Value'))->options($attributeValues);
            });

            // Inject custom CSS for QuickCreate row height
            $variantAttributes->tools(function ($tools) {
                $tools->append("
                    <style>
                        .quick-create td {
                            height: 400px!important;
                        }

                        body .choices.form-control-sm .choices__inner {
                            line-height: unset !important;
                        }

                    </style>
                ");
            });

            // Configure fields to display for Variant Attributes
            $variantAttributes->variant()->id();
            $variantAttributes->attribute()->name();
            $variantAttributes->AttributeValue()->value();
        });

        return $show;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Variant());

        $productId = request()->query('product_id'); // Get product_id from the query parameter

        $form->number('product_id', __('Product id'))->default($productId);
        $form->text('sku', __('Sku'))->default(uniqid());
        $form->decimal('price', __('Price'));
        $form->number('stock', __('Stock'));

        return $form;
    }
}
