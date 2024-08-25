<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Promotion;

class PromotionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Promotion';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Promotion());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('type', __('Type'));
        $grid->column('code', __('Code'));
        $grid->column('amount', __('Amount'));
        $grid->column('start', __('Start'));
        $grid->column('end', __('End'));
        $grid->column('limit', __('Limit'));
        $grid->column('count', __('Count'));
        $grid->column('active', __('Active'));
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
        $show = new Show(Promotion::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('type', __('Type'));
        $show->field('code', __('Code'));
        $show->field('amount', __('Amount'));
        $show->field('start', __('Start'));
        $show->field('end', __('End'));
        $show->field('limit', __('Limit'));
        $show->field('count', __('Count'));
        $show->field('active', __('Active'));
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
        $form = new Form(new Promotion());

        $form->text('name', __('Name'));
        $form->text('type', __('Type'));
        $form->text('code', __('Code'));
        $form->decimal('amount', __('Amount'));
        $form->datetime('start', __('Start'))->default(date('Y-m-d H:i:s'));
        $form->datetime('end', __('End'))->default(date('Y-m-d H:i:s'));
        $form->number('limit', __('Limit'));
        $form->number('count', __('Count'));
        $form->switch('active', __('Active'))->default(1);

        return $form;
    }
}
