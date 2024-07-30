<?php

namespace App\Admin\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\VariantAttribute;

class VariantAttributeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'VariantAttribute';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new VariantAttribute());

        $grid->column('id', __('Id'));
        $grid->column('variant_id', __('Variant id'));
        $grid->column('attribute_id', __('Attribute id'));
        $grid->column('attribute_value_id', __('Attribute value id'));
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
        $show = new Show(VariantAttribute::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('variant_id', __('Variant id'));
        $show->field('attribute_id', __('Attribute id'));
        $show->field('attribute_value_id', __('Attribute value id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new VariantAttribute());

        $variantId = request()->query('variant_id');

        $form->number('id', __('Id'));
        $form->number('variant_id', __('Variant id'))->default($variantId);
        $form->select('attribute_id', __('Attribute'))->options(function () {
            $attributes = Attribute::all();
            return $attributes->pluck('name', 'id');
        });
        $form->select('attribute_value_id', __('Attribute Values'))->options(function () {
            $attributeValues = AttributeValue::all();
            return $attributeValues->pluck('value', 'id');
        });

        $form->saved(function (Form $form) {
            $variant = $form->model()->variant_id;
            $url = admin_url('variants') . '/' .  $variant;
            return redirect($url);
        });

        return $form;
    }
}
