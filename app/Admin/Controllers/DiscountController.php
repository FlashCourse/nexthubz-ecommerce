<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Discount;

class DiscountController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Discount';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Discount());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('description', __('Description'));
        $grid->column('discount_type', __('Discount type'));
        $grid->column('discount_value', __('Discount value'));
        $grid->column('start_date', __('Start date'));
        $grid->column('end_date', __('End date'));
        $grid->column('is_active', __('Is active'));
        $grid->column('applicable_products', __('Applicable products'));
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
        $show = new Show(Discount::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
        $show->field('discount_type', __('Discount type'));
        $show->field('discount_value', __('Discount value'));
        $show->field('start_date', __('Start date'));
        $show->field('end_date', __('End date'));
        $show->field('is_active', __('Is active'));
        $show->field('applicable_products', __('Applicable products'));
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
        $form = new Form(new Discount());

        $form->text('name', __('Name'));
        $form->textarea('description', __('Description'));
        $form->text('discount_type', __('Discount type'));
        $form->decimal('discount_value', __('Discount value'));
        $form->date('start_date', __('Start date'))->default(date('Y-m-d'));
        $form->date('end_date', __('End date'))->default(date('Y-m-d'));
        $form->switch('is_active', __('Is active'))->default(1);
        $form->textarea('applicable_products', __('Applicable products'));

        return $form;
    }
}
