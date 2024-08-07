<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Product;
use OpenAdmin\Admin\Grid\Tools\QuickCreate;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Product';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());

        $grid->column('id', __('Id'));
        $grid->column('image', __('Image'))->image();
        $grid->column('name', __('Name'));
        $grid->column('slug', __('Slug'));
        $grid->column('description', __('Description'));
        $grid->column('category_id', __('Category id'));
        $grid->column('price', __('Price'));
        $grid->column('discount', __('Discount'));
        $grid->column('stock', __('Stock'));
        $grid->column('active', __('Active'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        // Sort the grid by 'created_at' in descending order
        $grid->model()->orderBy('created_at', 'desc');

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
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('slug', __('Slug'));
        $show->field('description', __('Description'));
        $show->field('category_id', __('Category id'));
        $show->field('image', __('Image'))->image();
        $show->field('price', __('Price'));
        $show->field('discount', __('Discount'));
        $show->field('stock', __('Stock'));
        $show->field('active', __('Active'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        // Define a nested resource for Variants
        $show->variants('Variants', function ($variants) use ($id) {
            $variants->setResource('/admin/variants');

            $variants->quickCreate(function (QuickCreate $create) use ($id) {
                $create->text('product_id', 'Product ID')->default($id);
                $create->text('sku', 'SKU')->default(uniqid());
                $create->text('price', 'Price');
                $create->text('stock', 'Stock');
            });


            // Configure fields to display for Variants
            $variants->product()->name();
            $variants->sku();
            $variants->price();
            $variants->stock();
            // Add more fields as needed


        });

        // Define a nested resource for Reviews
        $show->reviews('Reviews', function ($relation) {
            $relation->resource('/admin/reviews');
            // Configure fields to display for Reviews
            $relation->id();
            $relation->rating();
            $relation->comment();
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
        $form = new Form(new Product());

        $form->text('name', __('Name'));
        $form->textarea('description', __('Description'));
        // Define a select dropdown for the category_id field
        $form->select('category_id', __('Category'))->options(function () {
            // Retrieve all categories from your Category model
            $categories = Category::all();
            // Map categories to an array suitable for the options method
            return $categories->pluck('name', 'id');
        });
        $form->image('image', __('Image'))->move('images/products')->uniqueName();
        $form->decimal('price', __('Price'));
        $form->decimal('discount', __('Discount'));
        $form->number('stock', __('Stock'));
        $form->switch('active', __('Active'))->default(1);

        // Customize the footer
        $form->footer(function ($footer) {
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });

        // Callback
        $form->saved(function (Form $form) {
            $product = $form->model();
            $url = admin_url('products') . '/' . $product->id;
            return redirect($url);
        });

        return $form;
    }
}
