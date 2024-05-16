<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
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

        $show->variantAttributes('Variant Attributes', function ($variantAttributes) {
            $variantAttributes->setResource('/admin/variant-attributes');
            // Configure fields to display for Variants
            $variantAttributes->variant_id();
            $variantAttributes->attribute_id();
            $variantAttributes->attribute_value_id();
            // Add more fields as needed
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
