<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Category;

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
        // $grid->column('parent_id', __('Parent id'));
        $grid->column('slug', __('Slug'));
        $grid->column('created_at', __('Created at'))->dateFormat('F d, Y h:i A');
        $grid->column('updated_at', __('Updated at'))->dateFormat('F d, Y h:i A');


        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->between('created_at', __('Created at'))->datetime();
            $filter->between('updated_at', __('Updated at'))->datetime();
            // $filter->equal('parent_id', __('Parent Category'))->select(Category::pluck('name', 'id')->toArray());
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
        $show->field('parent_id', __('Parent id'));
        $show->field('slug', __('Slug'));
        $show->field('image', __('Image'));
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

        $form->text('name', __('Name'));
        // $form->number('parent_id', __('Parent id'));
        $form->image('image', __('Image'))->move('images/categories')->uniqueName();;
        $form->ckeditor('description')->options(['lang' => 'fr', 'height' => 500, 'contentsCss' => '/css/frontend-body-content.css']);


        return $form;
    }
}
