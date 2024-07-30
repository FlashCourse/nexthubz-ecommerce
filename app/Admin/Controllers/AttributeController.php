<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Attribute;

class AttributeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Attribute';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Attribute());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
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
        $show = new Show(Attribute::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        // Define a nested resource for Variants
        $show->attributeValues('AttributeValues', function ($attributeValues) use ($id) {
            $attributeValues->setResource('/admin/attribute-values');
            // Configure fields to display for Variants
            $attributeValues->attribute_id();
            $attributeValues->value();
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
        $form = new Form(new Attribute());

        $form->text('name', __('Name'));

        // Callback
        $form->saved(function (Form $form) {
            $attribute = $form->model();
            $url = admin_url('attributes') . '/' . $attribute->id;
            return redirect($url);
        });

        return $form;
    }
}
