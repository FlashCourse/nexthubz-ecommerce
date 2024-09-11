<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\CouponUsage;

class CouponUsageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'CouponUsage';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CouponUsage());

        $grid->column('id', __('Id'));
        $grid->column('coupon_id', __('Coupon id'));
        $grid->column('user_id', __('User id'));
        $grid->column('order_id', __('Order id'));
        $grid->column('times_used', __('Times used'));
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
        $show = new Show(CouponUsage::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('coupon_id', __('Coupon id'));
        $show->field('user_id', __('User id'));
        $show->field('order_id', __('Order id'));
        $show->field('times_used', __('Times used'));
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
        $form = new Form(new CouponUsage());

        $form->number('coupon_id', __('Coupon id'));
        $form->number('user_id', __('User id'));
        $form->number('order_id', __('Order id'));
        $form->number('times_used', __('Times used'))->default(1);

        return $form;
    }
}
