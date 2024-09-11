<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use App\Models\Coupon;
use Illuminate\Support\Str;

class CouponController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Coupon';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Coupon());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('code', __('Code'))->filter('like');
        $grid->column('description', __('Description'))->limit(50);
        $grid->column('discount_type', __('Discount type'))->sortable();
        $grid->column('discount_value', __('Discount value'))->display(function ($value) {
            return number_format($value, 2);
        });
        $grid->column('minimum_order_value', __('Minimum order value'))->display(function ($value) {
            return number_format($value, 2);
        });
        $grid->column('start_date', __('Start date'))->sortable()->dateFormat('F d, Y h:i A');;
        $grid->column('end_date', __('End date'))->sortable()->dateFormat('F d, Y h:i A');;
        $grid->column('usage_limit', __('Usage limit'))->sortable();
        $grid->column('times_used', __('Times used'))->sortable();
        $grid->column('is_active', __('Is active'))->bool(['1' => 'Yes', '0' => 'No']);

        // Fix for applicable_products display
        $grid->column('applicable_products', __('Applicable products'))->display(function ($products) {
            // Check if $products is an array, if yes convert to string by joining with commas
            if (is_array($products)) {
                return implode(', ', $products);
            }

            // If $products is a JSON string, decode it and then convert it to a string
            if (is_string($products) && is_array(json_decode($products, true))) {
                return implode(', ', json_decode($products, true));
            }

            // If $products is neither an array nor a valid JSON string, return it as is or return an empty string
            return is_string($products) ? $products : '';
        });


        $grid->column('created_at', __('Created at'))->sortable()->dateFormat('F d, Y h:i A');;
        $grid->column('updated_at', __('Updated at'))->sortable()->dateFormat('F d, Y h:i A');;

        $grid->filter(function ($filter) {
            $filter->like('code', 'Code');
            $filter->equal('discount_type', 'Discount Type')->select([
                'fixed' => 'Fixed Amount',
                'percentage' => 'Percentage',
            ]);
            $filter->between('start_date', 'Start Date')->date();
            $filter->between('end_date', 'End Date')->date();
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
        $show = new Show(Coupon::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('code', __('Code'));
        $show->field('description', __('Description'));
        $show->field('discount_type', __('Discount type'));
        $show->field('discount_value', __('Discount value'))->as(function ($value) {
            return number_format($value, 2);
        });
        $show->field('minimum_order_value', __('Minimum order value'))->as(function ($value) {
            return number_format($value, 2);
        });
        $show->field('start_date', __('Start date'));
        $show->field('end_date', __('End date'));
        $show->field('usage_limit', __('Usage limit'));
        $show->field('times_used', __('Times used'));
        $show->field('is_active', __('Is active'))->using(['1' => 'Yes', '0' => 'No']);

        // Fix for applicable_products display
        $show->field('applicable_products', __('Applicable products'))->as(function ($products) {
            if (is_array($products)) {
                $productNames = Product::whereIn('id', $products)->pluck('name')->toArray();
                return implode(', ', $productNames);
            } elseif (is_string($products)) {
                $productIds = json_decode($products, true) ?: [];
                $productNames = Product::whereIn('id', $productIds)->pluck('name')->toArray();
                return implode(', ', $productNames);
            }
            return '';
        });

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
        $form = new Form(new Coupon());

        // Auto-generate coupon code
        $form->text('code', __('Code'))->default(function () {
            return Str::upper(Str::random(10));
        })->required()->help('This will be automatically generated if left blank.');

        // Description field
        $form->textarea('description', __('Description'));

        // Discount type selection
        $form->select('discount_type', __('Discount Type'))
            ->options([
                'fixed' => 'Fixed Amount',
                'percentage' => 'Percentage',
            ])
            ->required();

        // Discount value with a default of 0.00
        $form->decimal('discount_value', __('Discount value'))
            ->default(0.00)
            ->required()
            ->help('Enter the discount amount or percentage depending on the type selected.');

        // Minimum order value with a default of 0.00
        $form->decimal('minimum_order_value', __('Minimum order value'))
            ->default(0.00)
            ->required()
            ->help('The minimum order value required to use this coupon.');

        // Start date with a default to the current date
        $form->date('start_date', __('Start date'))
            ->default(date('Y-m-d'))
            ->required()
            ->help('The date from which the coupon becomes valid.');

        // End date with a default to the current date
        $form->date('end_date', __('End date'))
            ->default(date('Y-m-d'))
            ->required()
            ->help('The date until which the coupon is valid.');

        // Usage limit with a default of 1
        $form->number('usage_limit', __('Usage limit'))
            ->default(1)
            ->required()
            ->help('Maximum number of times this coupon can be used.');

        // Times used with a default of 0
        $form->number('times_used', __('Times used'))
            ->default(0)
            ->readonly()
            ->help('This field is automatically updated when the coupon is used.');

        // Active switch with a default value of true (1)
        $form->switch('is_active', __('Is active'))->default(1)
            ->help('Enable or disable this coupon.');

        // Transferable list for selecting applicable products
        $form->multipleSelect('applicable_products', __('Applicable products'))
            ->options(Product::all()->pluck('name', 'id'))
            ->default([])
            ->help('Select the products applicable for this coupon.');

        return $form;
    }
}
