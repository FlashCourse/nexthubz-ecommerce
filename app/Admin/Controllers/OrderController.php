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
        $grid->column('payment_status', __('Payment status'));
        $grid->column('subtotal', __('Subtotal'));
        $grid->column('tax', __('Tax'));
        $grid->column('shipping', __('Shipping'));
        $grid->column('total', __('Total'));
        $grid->column('status', __('Status'))->select([
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

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('address_id', __('Address id'));
        $show->field('payment_method', __('Payment method'));
        $show->field('payment_status', __('Payment status'));
        $show->field('subtotal', __('Subtotal'));
        $show->field('tax', __('Tax'));
        $show->field('shipping', __('Shipping'));
        $show->field('total', __('Total'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

         // Include address details
         $show->address('Address', function ($relation) {
            $relation->first_name();
            $relation->last_name();
            $relation->address1();
            $relation->address2();
            $relation->city();
            $relation->state();
            $relation->zip_code();
            $relation->country();
            $relation->phone();
        });

        
        // Display related order items directly within the order detail view
       // Define a nested resource for Order Items
       $show->orderItems('Order Items', function ($relation) {
        $relation->resource('/admin/order-items');
        // Configure fields to display for Order Items
        $relation->id();
        $relation->name();
        $relation->quantity();
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
        $form = new Form(new Order());

        $form->number('user_id', __('User id'));
        $form->number('address_id', __('Address id'));
        $form->text('payment_method', __('Payment method'))->default('cash');
        $form->text('payment_status', __('Payment status'))->default('pending');
        $form->decimal('subtotal', __('Subtotal'));
        $form->decimal('tax', __('Tax'));
        $form->decimal('shipping', __('Shipping'));
        $form->decimal('total', __('Total'));
        $form->text('status', __('Status'))->default('initiated');

        return $form;
    }
}
