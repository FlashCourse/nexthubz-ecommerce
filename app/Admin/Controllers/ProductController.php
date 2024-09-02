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

        $grid->column('image', __('Image'))->image('', '50', '50');
        $grid->column('sku', __('SKU'));
        $grid->column('name', __('Name'));
        // $grid->column('slug', __('Slug'));
        // $grid->column('short_description', __('Short Description'));
        // $grid->column('description', __('Description'));
        $grid->column('category_id', __('Category'))->display(function ($categoryId) {
            return Category::find($categoryId)->name ?? 'N/A';
        })->label('info');
        // $grid->column('sales_count', __('Sales Count'));
        $grid->column('stock', __('Stock'));
        $grid->column('regular_price', __('Regular Price'));
        $grid->column('sale_price', __('Sale Price'))->color('green');
        $grid->column('is_new', __('Is New'))->bool();
        $grid->column('is_featured', __('Is Featured'))->bool();
        $grid->column('is_best_selling', __('Is Best Selling'))->bool();
        $grid->column('active', __('Active'))->bool();

        // $grid->column('created_at', __('Created at'))->sortable();
        // $grid->column('updated_at', __('Updated at'))->sortable();



        // sort, search and filter
        $grid->model()->orderBy('created_at', 'desc');

        $grid->quickSearch('id', 'sku', 'name');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->between('created_at', __('Created at'))->datetime();
            $filter->between('updated_at', __('Updated at'))->datetime();
            $filter->equal('category_id', __('Category'))->select(Category::pluck('name', 'id')->toArray());
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
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('slug', __('Slug'));
        $show->field('short_description', __('Short Description'));
        $show->field('description', __('Description'));
        $show->field('category_id', __('Category'))->as(function ($categoryId) {
            return Category::find($categoryId)->name ?? 'N/A';
        });
        $show->field('image', __('Image'))->image();
        $show->field('regular_price', __('Regular Price'));
        $show->field('sale_price', __('Sale Price'));
        $show->field('stock', __('Stock'));
        $show->field('sales_count', __('Sales Count'));
        $show->field('is_new', __('Is New'));
        $show->field('is_featured', __('Is Featured'));
        $show->field('is_best_selling', __('Is Best Selling'));
        $show->field('active', __('Active'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        // Define a nested resource for Variants
        $show->variants('Variants', function ($variants) use ($id) {
            $variants->setResource('/admin/variants');

            $variants->quickCreate(function (QuickCreate $create) use ($id) {
                $create->hidden('product_id', 'Product ID')->default($id);
                $create->text('regular_price', 'Regular Price');
                $create->text('sale_price', 'Sale Price');
                $create->text('stock', 'Stock');
            });


            // Configure fields to display for Variants
            $variants->product()->name();
            $variants->sku();
            $variants->regular_price();
            $variants->sale_price();
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
        $form->text('name', __('Name'))->rules('required|max:255');
        $form->select('category_id', __('Category'))->options(function () {
            return Category::pluck('name', 'id');
        })->rules('required');
        $form->image('image', __('Image'))->move('images/products')->uniqueName()->rules('nullable|image');
        $form->decimal('regular_price', __('Regular Price'))->rules('required|numeric');
        $form->decimal('sale_price', __('Sale Price'))->rules('required|numeric');
        $form->number('stock', __('Stock'))->rules('required|integer|min:0');
        // $form->number('sales_count', __('Sales Count'))->default(0)->rules('required|integer|min:0');
        $form->switch('is_new', __('Is New'))->default(0);
        $form->switch('is_featured', __('Is Featured'))->default(0);
        $form->switch('is_best_selling', __('Is Best Selling'))->default(0);
        $form->switch('active', __('Active'))->default(1);
        $form->textarea('short_description', __('Short Description'))->rules('nullable');
        $form->ckeditor('description')->options(['lang' => 'fr', 'height' => 500, 'contentsCss' => '/css/frontend-body-content.css']);

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
