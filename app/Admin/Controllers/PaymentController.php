<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Payment;

class PaymentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Payment';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Payment());

        $grid->column('id', __('Id'));
        $grid->column('invoice_id', __('Invoice id'));
        $grid->column('transaction_id', __('Transaction id'));
        $grid->column('order_id', __('Order id'));
        $grid->column('amount', __('Amount'));
        $grid->column('currency', __('Currency'));
        $grid->column('payment_method', __('Payment method'));
        $grid->column('payment_date', __('Payment date'));
        $grid->column('status', __('Status'));
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
        $show = new Show(Payment::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('invoice_id', __('Invoice id'));
        $show->field('transaction_id', __('Transaction id'));
        $show->field('order_id', __('Order id'));
        $show->field('amount', __('Amount'));
        $show->field('currency', __('Currency'));
        $show->field('payment_method', __('Payment method'));
        $show->field('payment_date', __('Payment date'));
        $show->field('status', __('Status'));
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
        $form = new Form(new Payment());

        $form->text('invoice_id', __('Invoice id'))->default(uniqid());
        $form->text('transaction_id', __('Transaction id'));
        $form->number('order_id', __('Order id'));
        $form->decimal('amount', __('Amount'));
        $form->text('currency', __('Currency'))->default('BDT');
        $form->text('payment_method', __('Payment method'))->default('cash');
        $form->datetime('payment_date', __('Payment date'))->default(date('Y-m-d H:i:s'));
        $form->text('status', __('Status'))->default('completed');

        return $form;
    }
}
