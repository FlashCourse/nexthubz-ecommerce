<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Address;

class AddressController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Address';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Address());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('first_name', __('First name'));
        $grid->column('last_name', __('Last name'));
        $grid->column('address1', __('Address1'));
        $grid->column('address2', __('Address2'));
        $grid->column('city', __('City'));
        $grid->column('state', __('State'));
        $grid->column('zip_code', __('Zip code'));
        $grid->column('country', __('Country'));
        $grid->column('phone', __('Phone'));
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
        $show = new Show(Address::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('first_name', __('First name'));
        $show->field('last_name', __('Last name'));
        $show->field('address1', __('Address1'));
        $show->field('address2', __('Address2'));
        $show->field('city', __('City'));
        $show->field('state', __('State'));
        $show->field('zip_code', __('Zip code'));
        $show->field('country', __('Country'));
        $show->field('phone', __('Phone'));
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
        $form = new Form(new Address());

        $form->number('user_id', __('User id'));
        $form->text('first_name', __('First name'));
        $form->text('last_name', __('Last name'));
        $form->text('address1', __('Address1'));
        $form->text('address2', __('Address2'));
        $form->text('city', __('City'));
        $form->text('state', __('State'));
        $form->text('zip_code', __('Zip code'));
        $form->text('country', __('Country'));
        $form->phonenumber('phone', __('Phone'));

        return $form;
    }
}
