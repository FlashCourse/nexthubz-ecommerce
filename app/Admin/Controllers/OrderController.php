<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Order;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Order';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('address_id', __('Address id'));
        $grid->column('payment_method', __('Payment method'));
        $grid->column('subtotal', __('Subtotal'));
        $grid->column('tax', __('Tax'));
        $grid->column('shipping', __('Shipping'));
        $grid->column('total', __('Total'));
        $grid->column('due', __('Due'));
        $grid->column('paid', __('Paid'));
        $grid->column('status', __('Status'))->select([
            'initiated' => 'Initiated',
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'canceled' => 'Canceled',
        ]);
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
        $show = new Show(Order::findOrFail($id));

        // Basic order fields
        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('address_id', __('Address id'));
        $show->field('payment_method', __('Payment method'));
        $show->field('subtotal', __('Subtotal'));
        $show->field('tax', __('Tax'));
        $show->field('shipping_cost', __('Shipping'));
        $show->field('total', __('Total'));
        $show->field('due_amount', __('Due'));
        $show->field('paid_amount', __('Paid'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        // Divider for separation
        $show->divider();

        // Shipping Details (Using built-in fields)
        $show->field('shipping_first_name', __('Shipping First Name'));
        $show->field('shipping_last_name', __('Shipping Last Name'));
        $show->field('shipping_company', __('Shipping Company'));
        $show->field('shipping_address_line_1', __('Shipping Address Line 1'));
        $show->field('shipping_address_line_2', __('Shipping Address Line 2'));
        $show->field('shipping_city', __('Shipping City'));
        $show->field('shipping_state', __('Shipping State'));
        $show->field('shipping_postcode', __('Shipping Postcode'));
        $show->field('shipping_country', __('Shipping Country'));
        $show->field('shipping_phone', __('Shipping Phone'));
        $show->field('shipping_email', __('Shipping Email'));

        // Divider for separation
        $show->divider();

        // Billing Details (Using built-in fields)
        $show->field('billing_first_name', __('Billing First Name'));
        $show->field('billing_last_name', __('Billing Last Name'));
        $show->field('billing_company', __('Billing Company'));
        $show->field('billing_address_line_1', __('Billing Address Line 1'));
        $show->field('billing_address_line_2', __('Billing Address Line 2'));
        $show->field('billing_city', __('Billing City'));
        $show->field('billing_state', __('Billing State'));
        $show->field('billing_postcode', __('Billing Postcode'));
        $show->field('billing_country', __('Billing Country'));
        $show->field('billing_phone', __('Billing Phone'));
        $show->field('billing_email', __('Billing Email'));

        return $show;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order());

        $form->number('user_id', __('User id'));
        $form->number('address_id', __('Address id'));
        $form->text('payment_method', __('Payment method'))->default('undefined');
        $form->decimal('subtotal', __('Subtotal'));
        $form->decimal('tax', __('Tax'));
        $form->decimal('shipping', __('Shipping'));
        $form->decimal('total', __('Total'));
        $form->decimal('due', __('Due'));
        $form->decimal('paid', __('Paid'));
        $form->text('status', __('Status'))->default('initiated');

        return $form;
    }
}
