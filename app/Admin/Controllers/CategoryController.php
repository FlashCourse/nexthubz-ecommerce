<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use App\Models\Category;

class CategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Category';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category());

        $grid->quickSearch('name');

        $grid->column('id', __('Id'));
        $grid->column('image', __('Image'))->image('', '50', '50');
        $grid->column('name', __('Name'));
        $grid->column('parent_id', __('Parent Category'))->display(function ($parentId) {
            $parent = Category::find($parentId);
            return $parent ? $parent->name : '-';
        });
        $grid->column('slug', __('Slug'));
        $grid->column('created_at', __('Created at'))->dateFormat('F d, Y h:i A');
        $grid->column('updated_at', __('Updated at'))->dateFormat('F d, Y h:i A');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->between('created_at', __('Created at'))->datetime();
            $filter->between('updated_at', __('Updated at'))->datetime();
            $filter->equal('parent_id', __('Parent Category'))->select(Category::pluck('name', 'id')->toArray());
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
        $show = new Show(Category::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('parent_id', __('Parent Category'))->as(function ($parentId) {
            $parent = Category::find($parentId);
            return $parent ? $parent->name : '-';
        });
        $show->field('slug', __('Slug'));
        $show->field('image', __('Image'))->image();
        $show->field('icon', __('Icon'))->image();  // Show the icon
        $show->field('description', __('Description'));
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
        $form = new Form(new Category());

        $form->text('name', __('Name'))->required();

        // Allow null by adding an empty option and handling the null case directly.
        $form->select('parent_id', __('Parent Category'))
            ->options(Category::pluck('name', 'id')->toArray())
            ->default('') // Allows an empty selection
            ->help('Leave empty to make this a top-level category.');

        $form->image('image', __('Image'))->move('images/categories')->uniqueName();
        $form->image('icon', __('Icon'))->move('images/icons')->uniqueName();  // Icon field added
        $form->ckeditor('description', __('Description'))->options(['lang' => 'fr', 'height' => 500, 'contentsCss' => '/css/frontend-body-content.css']);
        $form->text('slug', __('Slug'))->required();

        return $form;
    }
}
