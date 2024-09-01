<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Order;
use App\Models\User;

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

        $grid->column('id', __('ID'));
        $grid->column('order_Number', __('Order Number'));
        $grid->column('user_id', __('User'))->display(function ($userId) {
            $user = User::find($userId);
            return $user ? $user->name : 'N/A';
        });
        $grid->column('subtotal', __('Subtotal'));
        $grid->column('tax', __('Tax'))->color('red');
        $grid->column('shipping_cost', __('Shipping Cost'))->color('red');
        $grid->column('due_amount', __('Due Amount'));
        $grid->column('paid_amount', __('Paid Amount'))->color('green');
        $grid->column('total', __('Total'));
        $grid->column('status', __('Status'))->select([
            'initiated' => 'Initiated',
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'canceled' => 'Canceled',
        ]);
        $grid->column('created_at', __('Created at'))->dateFormat('F d, Y h:i A');
        $grid->column('updated_at', __('Updated at'))->dateFormat('F d, Y h:i A');

        $grid->quickSearch('id', 'user_id', 'status');
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableEdit();
            $actions->disableDelete();
        });

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
        $show->field('order_number', __('Order Number'));
        $show->field('user_id', __('User id'));
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

        $show->orderItems('OrderItems', function ($relation) {
            $relation->resource('/admin/order-items');

            $relation->product_id(__('Product ID'));
            $relation->variant_id(__('Variant ID'));
            $relation->price(__('Price'));
            $relation->quantity(__('Quantity'));
            $relation->discount(__('Discount'));

            $relation->disableCreateButton();
            $relation->actions(function ($actions) {
                $actions->disableEdit();
                $actions->disableDelete();
            });
        });

        $show->panel()
            ->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableDelete();
            });

        return $show;
    }
}
